<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\HtmlString;
use Waterhole\Formatter\Mentions;
use Waterhole\Models\Model;
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
 */
trait HasBody
{
    use UsesFormatter;

    public static function bootHasBody(): void
    {
        // Whenever the model is saved, sync the users mentioned in the body
        // into the `mentions` relationship.
        static::saved(function (Model $model) {
            $model->mentions()->sync(Mentions::getMentionedUsers($model->parsed_body));
        });
    }

    /**
     * Relationship with the users who were mentioned in the body.
     */
    public function mentions(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'mentions');
    }
}
