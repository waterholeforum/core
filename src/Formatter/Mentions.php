<?php

namespace Waterhole\Formatter;

use Waterhole\Models\User;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class Mentions
{
    const TEMPLATE = '<a href="{$MENTION_URL}{@name}" data-user-id="{@id}">
        <xsl:attribute name="class">mention<xsl:if test="@id = $USER_ID"> mention--self</xsl:if></xsl:attribute>
        @<xsl:value-of select="@name"/>
    </a>';

    public static function configure(Configurator $config): void
    {
        $config->rendering->parameters['MENTION_URL'] = url('u').'/';

        $config->Preg->match('/\B@(?<name>[a-z0-9_-]+)(?!#)/i', 'MENTION');

        $tag = $config->tags->add('MENTION');
        $tag->attributes->add('name');
        $tag->attributes->add('id');
        $tag->filterChain->prepend([static::class, 'filterMention']);
        $tag->template = static::TEMPLATE;
    }

    public static function filterMention($tag): bool
    {
        if ($user = User::where('name', 'like', $tag->getAttribute('name'))->first()) {
            $tag->setAttribute('id', $user->id);

            return true;
        }
        return false;
    }

    public static function rendering(Renderer $renderer, string &$xml, array $context): void
    {
        $xml = Utils::replaceAttributes($xml, 'MENTION', function ($attributes) use ($context) {
            if (isset($attributes['id'])) {
                $mentionedUser = ($context['post']->mentions ?? User::query())->find($attributes['id']);
                $attributes['name'] = $mentionedUser?->name;
            }

            return $attributes;
        });
    }
}
