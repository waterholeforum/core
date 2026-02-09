<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $content_type
 * @property int $content_id
 * @property string $mentionable_type
 * @property int $mentionable_id
 * @property null|\Illuminate\Database\Eloquent\Model $content
 * @property null|\Illuminate\Database\Eloquent\Model $mentionable
 */
class Mention extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    public function content(): MorphTo
    {
        return $this->morphTo();
    }

    public function mentionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getKey(): string
    {
        return implode(':', [
            $this->content_type,
            $this->content_id,
            $this->mentionable_type,
            $this->mentionable_id,
        ]);
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query
            ->where('content_type', $this->content_type)
            ->where('content_id', $this->content_id)
            ->where('mentionable_type', $this->mentionable_type)
            ->where('mentionable_id', $this->mentionable_id);
    }
}
