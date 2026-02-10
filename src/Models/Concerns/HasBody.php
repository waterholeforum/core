<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Formatter\FormatMentions;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Formatter\HeadingSlugs;
use Waterhole\Models\Comment;
use Waterhole\Models\Group;
use Waterhole\Models\Mention;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\Upload;
use Waterhole\Models\User;

/**
 * Methods to give a model a formatted text `body`.
 *
 * This trait assumes a `body` column exists on the model. When this attribute
 * is set on the model, the content will be parsed into an XML document by the
 * Formatter, and stored in the database in this form. When the `body` attribute
 * is retrieved, it is unparsed back into the original plain-text version.
 *
 * This trait also adds a `mentions` relationship to store a list of the users
 * mentioned in the body using the @ prefix. This relationship can then be
 * loaded before the body is rendered so that the Formatter can substitute in
 * the most up-to-date names.
 *
 * @property string $body The original unformatted version of the body.
 * @property-read HtmlString $body_html The formatted HTML version of the body
 *   for the current user.
 * @property string $parsed_body The intermediary parsed XML document.
 * @property-read string $body_text The parsed body with formatting removed.
 * @property-read \Illuminate\Database\Eloquent\Collection $mentions
 * @property-read \Illuminate\Database\Eloquent\Collection $attachments
 */
trait HasBody
{
    use UsesFormatter;

    /**
     * Extract headings from the parsed body XML.
     */
    public function bodyHeadings(array $levels = [2, 3]): Collection
    {
        return HeadingSlugs::extractHeadings($this->parsed_body, $levels);
    }

    public static function bootHasBody(): void
    {
        // Whenever the model is saved, sync the users and uploads mentioned in
        // the body into their respective relationships. We register `created`
        // and `updated` handlers instead of using the `saved` event, because we
        // want this to run as early as possible.

        $onSave = function (Model $model) {
            if (!$model->wasRecentlyCreated && !$model->wasChanged('body')) {
                return;
            }

            $mentions = collect(FormatMentions::getMentions($model->parsed_body));
            $mentionRows = collect();
            $channel = $model instanceof Post || $model instanceof Comment ? $model->channel : null;
            $actor = $model instanceof Post || $model instanceof Comment ? $model->user : null;

            $mentionRows->push(
                ...User::whereKey($mentions->where('type', FormatMentions::TYPE_USER)->pluck('id'))
                    ->pluck('id')
                    ->map(
                        fn($id) => [
                            'mentionable_type' => (new User())->getMorphClass(),
                            'mentionable_id' => $id,
                        ],
                    ),
            );

            $mentionRows->push(
                ...Group::whereKey(
                    $mentions->where('type', FormatMentions::TYPE_GROUP)->pluck('id'),
                )
                    ->withCount('users')
                    ->get()
                    ->filter(
                        fn(Group $group) => !$actor || $actor->can('mention', [$group, $channel]),
                    )
                    ->map(
                        fn(Group $group) => [
                            'mentionable_type' => $group->getMorphClass(),
                            'mentionable_id' => $group->id,
                        ],
                    ),
            );

            $model->mentions()->delete();

            if (count($mentionRows = $mentionRows->unique())) {
                $contentType = $model->getMorphClass();
                $contentId = $model->getKey();

                Mention::insert(
                    $mentionRows
                        ->map(
                            fn(array $row) => $row + [
                                'content_type' => $contentType,
                                'content_id' => $contentId,
                            ],
                        )
                        ->all(),
                );
            }

            $model->attachments()->sync(
                Upload::query()
                    ->whereIn('filename', FormatUploads::getAttachedUploads($model->parsed_body))
                    ->pluck('id'),
            );
        };

        static::created($onSave);
        static::updated($onSave);

        $onDelete = function (Model $model) {
            $model->mentions()->delete();
            $model->attachments()->detach();
        };

        if (method_exists(static::class, 'forceDeleted')) {
            static::forceDeleted($onDelete);
        } else {
            static::deleted($onDelete);
        }
    }

    /**
     * Relationship with the mentionables that were mentioned in the body.
     */
    public function mentions(): MorphMany
    {
        return $this->morphMany(Mention::class, 'content');
    }

    /**
     * Relationship with the uploads that were attached in the body.
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Upload::class, 'content', 'attachments');
    }

    /**
     * Resolve mentioned users.
     */
    protected function mentionedUsers(): Collection
    {
        if (!$this instanceof Post && !$this instanceof Comment) {
            return collect();
        }

        if (!$this->user) {
            return collect();
        }

        $actor = $this->user;
        $channel = $this->channel;

        $mentionables = $this->mentions
            ->load('mentionable')
            ->loadMorph('mentionable', [
                User::class => ['groups'],
                Group::class => ['users.groups'],
            ])
            ->map->mentionable->filter();

        return $mentionables
            ->filter(function ($mentionable) use ($actor, $channel) {
                if ($mentionable instanceof User) {
                    return $actor->can('mention', $mentionable);
                }

                return $actor->can('mention', [$mentionable, $channel]);
            })
            ->flatMap(
                fn($mentionable) => $mentionable instanceof User
                    ? [$mentionable]
                    : $mentionable->users,
            )
            ->where('id', '!=', $actor->id)
            ->unique('id')
            ->filter(fn(User $user) => $this->isVisibleTo($user))
            ->values();
    }

    public function isVisibleTo(User $user): bool
    {
        return true;
    }
}
