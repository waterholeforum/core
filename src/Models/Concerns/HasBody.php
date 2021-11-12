<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\HtmlString;
use Waterhole\Formatter\Formatter;
use Waterhole\Formatter\Mentions;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\PostUser;
use Waterhole\Models\User;
use Waterhole\Notifications\Mention;

trait HasBody
{
    protected static Formatter $formatter;

    private array $renderCache = [];

    public static function bootHasBody()
    {
        static::saved(function (Model $model) {
            $model->mentions()->sync(
                Mentions::getMentionedUsers($model->parsed_body)
            );

            if ($model->wasRecentlyCreated && ($model instanceof Post || $model instanceof Comment)) {
                $post = $model instanceof Post ? $model : $model->post;

                $users = $model->mentions
                    ->except($model->user_id)
                    ->filter(function (User $user) use ($post) {
                        return Post::visibleTo($user)->whereKey($post->id)->exists();
                    });

                $postUserRows = $users->map(fn(User $user) => [
                    'post_id' => $post->getKey(),
                    'user_id' => $user->getKey(),
                    'mentioned_at' => now(),
                ])->all();

                PostUser::upsert($postUserRows, ['post_id', 'user_id'], ['mentioned_at']);

                Notification::send($users, new Mention($model));
            }
        });
    }

    public function mentions(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'mentions');
    }

    public function getBodyAttribute(string $value): string
    {
        return static::$formatter->unparse($value);
    }

    public function getBodyHtmlAttribute(): HtmlString
    {
        return $this->render(Auth::user());
    }

    public function getParsedBodyAttribute(): string
    {
        return $this->attributes['body'];
    }

    public function setBodyAttribute(string $value)
    {
        $context = ['model' => $this];

        $this->attributes['body'] = $value ? static::$formatter->parse($value, $context) : null;
    }

    public function setParsedBodyAttribute(string $value)
    {
        $this->attributes['body'] = $value;
    }

    public function render(User $actor = null): HtmlString
    {
        $key = $actor->id ?? 0;

        if (! isset($this->renderCache[$key])) {
            $context = ['model' => $this, 'actor' => $actor];

            $this->renderCache[$key] = static::$formatter->render($this->parsedBody, $context);
        }

        return new HtmlString($this->renderCache[$key]);
    }

    public static function getFormatter(): Formatter
    {
        return static::$formatter;
    }

    public static function setFormatter(Formatter $formatter)
    {
        static::$formatter = $formatter;
    }
}
