<?php

namespace Waterhole\Formatter;

use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser\Tag;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;
use Waterhole\Models\User;

use function Waterhole\username;

/**
 * User mention parsing utilities.
 *
 * These allow users to be mentioned by their name using the @ prefix.
 */
abstract class FormatMentions
{
    public const TAG_NAME = 'MENTION';

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
        $config->rendering->parameters['MENTION_URL'] = rtrim(
            route('waterhole.users.show', ['user' => '_']),
            '_',
        );

        $config->Preg->match('/\B@(?<name>[^\s]*[^\s\.,!?:;)"\'])/i', static::TAG_NAME);

        $tag = $config->tags->add(static::TAG_NAME);
        $tag->attributes->add('name');
        $tag->attributes->add('id');
        $tag->filterChain->prepend([static::class, 'filterMention']);

        // data-hovercard-type="user" is necessary to make @github/paste-markdown
        // prevent mentions being converted into Markdown links when pasted.
        $tag->template = <<<'xsl'
            <xsl:choose>
                <xsl:when test="@id">
                    <a href="{$MENTION_URL}{@id}" data-user-id="{@id}" data-hovercard-type="user">
                        <xsl:attribute name="class">
                            mention <xsl:if test="@id and @id = $USER_ID">mention--self</xsl:if>
                        </xsl:attribute>
                        @<xsl:value-of select="@name"/>
                    </a>
                </xsl:when>
                <xsl:otherwise>
                    <span class="mention">
                        @<xsl:value-of select="@name"/>
                    </span>
                </xsl:otherwise>
            </xsl:choose>
        xsl;
    }

    /**
     * Determine whether a mention tag should be kept.
     */
    public static function filterMention(Tag $tag): bool
    {
        $name = str_replace("\xc2\xa0", ' ', $tag->getAttribute('name'));

        if ($user = User::firstWhere('name', 'like', $name)) {
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
        if (!$context?->model?->relationLoaded('mentions')) {
            return;
        }

        $mentions = $context->model->getRelation('mentions');

        $xml = Utils::replaceAttributes($xml, 'MENTION', function ($attributes) use ($mentions) {
            if (isset($attributes['id'])) {
                $attributes['name'] = username($user = $mentions->find($attributes['id']));

                if (!$user) {
                    unset($attributes['id']);
                }
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
