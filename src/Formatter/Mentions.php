<?php

namespace Waterhole\Formatter;

use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser\Tag;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;
use Waterhole\Models\User;

/**
 * User mention parsing utilities.
 *
 * These allow users to be mentioned by their name using the @ prefix.
 */
abstract class Mentions
{
    const TAG_NAME = 'MENTION';

    /**
     * Formatter configuration callback.
     *
     * Set up a regular expression to parse plain-text mentions into an XML tag
     * with a `name` attribute. Also apply a filter to the tag, to look up the
     * username and populate an `id` attribute on the tag – or remove it if
     * the user doesn't exist.
     */
    public static function configure(Configurator $config): void
    {
        $config->rendering->parameters['MENTION_URL'] = rtrim(route('waterhole.users.show', ['user' => '_']), '_');

        $config->Preg->match('/\B@(?<name>[a-z0-9_-]+)/i', static::TAG_NAME);

        $tag = $config->tags->add(static::TAG_NAME);
        $tag->attributes->add('name');
        $tag->attributes->add('id');
        $tag->filterChain->prepend([static::class, 'filterMention']);

        $tag->template = implode([
            '<a href="{$MENTION_URL}{@name}" data-user-id="{@id}" data-mention="" data-id="{@name}">',
            '<xsl:attribute name="class">mention<xsl:if test="@id = $USER_ID"> mention--self</xsl:if></xsl:attribute>',
            '@<xsl:value-of select="@name"/>',
            '</a>',
        ]);
    }

    /**
     * Determine whether a mention tag should be kept.
     */
    public static function filterMention(Tag $tag): bool
    {
        if ($user = User::where('name', 'like', $tag->getAttribute('name'))->first()) {
            $tag->setAttribute('id', $user->id);

            return true;
        }

        return false;
    }

    /**
     * Formatter rendering callback.
     *
     * It is possible for a user's name to change after the creation of content
     * that mentions them. So whenever we render content, we go through the
     * mention tags and update the `name` attribute to the user's current name.
     * This assumes that the `mentions` relationship is already loaded on the
     * content model – otherwise we would run into an N+1 query problem.
     */
    public static function rendering(Renderer $renderer, string &$xml, ?Context $context): void
    {
        if (! $context?->model?->relationLoaded('mentions')) {
            return;
        }

        $xml = Utils::replaceAttributes($xml, 'MENTION', function ($attributes) use ($context) {
            if (isset($attributes['id'])) {
                $attributes['name'] = $context->model->mentions->find($attributes['id'])?->name;
            }

            return $attributes;
        });
    }

    /**
     * Get all the user IDs that have been mentioned in a piece of content.
     *
     * This is used in the `HasBody` model trait to populate the `mentions`
     * relationship so that it can be eager loaded when the content is
     * displayed, and the rendering function above can make use of the data.
     */
    public static function getMentionedUsers(string $xml): array
    {
        return Utils::getAttributeValues($xml, static::TAG_NAME, 'id');
    }
}
