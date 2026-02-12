<?php

namespace Waterhole\Formatter;

use Illuminate\Support\Str;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser\Tag;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;
use Waterhole\Models\Group;
use Waterhole\Models\User;
use function Waterhole\get_contrast_color;
use function Waterhole\username;

/**
 * User mention parsing utilities.
 *
 * These allow users to be mentioned by their name using the @ prefix.
 */
abstract class FormatMentions
{
    public const TAG_NAME = 'MENTION';

    public const TYPE_USER = 'user';
    public const TYPE_GROUP = 'group';
    public const TYPE_HERE = 'here';

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
        $tag->attributes->add('id', ['required' => false]);
        $tag->attributes->add('type', ['required' => false]);
        $tag->attributes->add('group_color', ['required' => false]);
        $tag->attributes->add('group_contrast', ['required' => false]);
        $tag->filterChain->prepend([static::class, 'filterMention']);

        // data-hovercard-type="user" is necessary to make @github/paste-markdown
        // prevent mentions being converted into Markdown links when pasted.
        $tag->template = <<<'xsl'
            <xsl:choose>
                <xsl:when test="@type = 'group'">
                    <span data-group-id="{@id}">
                        <xsl:attribute name="class">
                            mention mention--group
                            <xsl:if test="@id and contains(concat(',', $USER_GROUPS, ','), concat(',', @id, ','))">mention--self</xsl:if>
                        </xsl:attribute>
                        <xsl:if test="@group_color">
                            <xsl:attribute name="style">
                                --group-color: <xsl:value-of select="@group_color"/>;
                                --group-color-contrast: <xsl:value-of select="@group_contrast"/>;
                            </xsl:attribute>
                        </xsl:if>
                        @<xsl:value-of select="@name"/>
                    </span>
                </xsl:when>
                <xsl:when test="@type = 'here'">
                    <span>
                        <xsl:attribute name="class">
                            mention mention--here
                            <xsl:if test="$USER_ID">mention--self</xsl:if>
                        </xsl:attribute>
                        @<xsl:value-of select="@name"/>
                    </span>
                </xsl:when>
                <xsl:when test="@id">
                    <a href="{$MENTION_URL}{@id}" data-user-id="{@id}" data-hovercard-type="user">
                        <xsl:attribute name="class">
                            mention mention--user
                            <xsl:if test="@id and @id = $USER_ID">mention--self</xsl:if>
                        </xsl:attribute>
                        @<xsl:value-of select="@name"/>
                    </a>
                </xsl:when>
                <xsl:otherwise>
                    @<xsl:value-of select="@name"/>
                </xsl:otherwise>
            </xsl:choose>
        xsl;
    }

    /**
     * Determine during parsing whether a mention tag should be kept.
     */
    public static function filterMention(Tag $tag): bool
    {
        $name = str_replace("\xc2\xa0", ' ', $tag->getAttribute('name'));
        $type = static::TYPE_USER;

        $lowerName = Str::lower($name);

        if (Str::startsWith($lowerName, 'group:')) {
            $type = static::TYPE_GROUP;
            $name = trim(substr($name, strlen('group:')));
        } elseif ($lowerName === 'here') {
            $type = static::TYPE_HERE;
        }

        $tag->setAttribute('type', $type);

        if ($type === static::TYPE_HERE) {
            $tag->setAttribute('name', 'here');

            return true;
        }

        if (!$name) {
            return false;
        }

        $operator = (new User())->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        if ($type === static::TYPE_GROUP) {
            if ($group = Group::where('is_public', true)->firstWhere('name', $operator, $name)) {
                $tag->setAttribute('id', $group->id);
                $tag->setAttribute('name', $group->name);

                return true;
            }

            return false;
        }

        if ($user = User::firstWhere('name', $operator, $name)) {
            $tag->setAttribute('id', $user->id);
            $tag->setAttribute('name', $user->name);

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
        $xml = Utils::replaceAttributes($xml, 'MENTION', function ($attributes) use ($context) {
            $type = $attributes['type'] ??= static::TYPE_USER;

            if ($type === static::TYPE_HERE) {
                // TODO: translate
                return ['name' => 'here'] + $attributes;
            }

            if (empty($attributes['id'])) {
                return $attributes;
            }

            if ($type === static::TYPE_USER) {
                $user = $context?->model?->mentions
                    ->loadMissing('mentionable')
                    ?->where('mentionable_type', (new User())->getMorphClass())
                    ->firstWhere('mentionable_id', $attributes['id'])?->mentionable;

                if (!$user) {
                    unset($attributes['id']);

                    return $attributes;
                }

                $attributes['name'] = username($user);

                return $attributes;
            }

            if ($type === static::TYPE_GROUP) {
                $group = $context?->model?->mentions
                    ->loadMissing('mentionable')
                    ?->where('mentionable_type', (new Group())->getMorphClass())
                    ->firstWhere('mentionable_id', $attributes['id'])?->mentionable;

                if (!$group) {
                    unset($attributes['id'], $attributes['type']);

                    return $attributes;
                }

                $attributes['name'] = $group->name;

                if ($group->color) {
                    $attributes['group_color'] = '#' . $group->color;
                    $attributes['group_contrast'] = get_contrast_color($group->color);
                }
            }

            return $attributes;
        });
    }

    /**
     * Get all the mention tags in a piece of content.
     *
     * This is used in the `HasBody` model trait to populate the `mentions`
     * relationship so that it can be eager loaded when the content is
     * displayed, and the rendering function above can make use of the data.
     */
    public static function getMentions(string $xml): array
    {
        $mentions = [];

        Utils::replaceAttributes($xml, static::TAG_NAME, function ($attributes) use (&$mentions) {
            return $mentions[] = $attributes + ['type' => static::TYPE_USER];
        });

        return $mentions;
    }
}
