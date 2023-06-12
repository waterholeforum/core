<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\HtmlString;
use Waterhole\Formatter\FormatMentions;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Models\Model;
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
 * the most up-to-date usernames.
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

    public static function bootHasBody(): void
    {
        // Whenever the model is saved, sync the users and uploads mentioned in
        // the body into their respective relationships. We register `created`
        // and `updated` handlers instead of using the `saved` event, because we
        // want this to run as early as possible.

        $sync = function (Model $model) {
            $model->mentions()->sync(FormatMentions::getMentionedUsers($model->parsed_body));

            $model->attachments()->sync(
                Upload::query()
                    ->whereIn('filename', FormatUploads::getAttachedUploads($model->parsed_body))
                    ->pluck('id'),
            );
        };

        static::created($sync);
        static::updated($sync);

        static::deleted(function (Model $model) {
            $model->mentions()->detach();
            $model->attachments()->detach();
        });
    }

    /**
     * Relationship with the users who were mentioned in the body.
     */
    public function mentions(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'mentions');
    }

    /**
     * Relationship with the uploads that were attached in the body.
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Upload::class, 'content', 'attachments');
    }
}
