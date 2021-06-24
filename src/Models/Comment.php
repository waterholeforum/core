<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use Waterhole\Actions\Deletable;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasMentions;

class Comment extends Model implements Deletable
{
    use HasLikes, HasMentions;

    const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        // TODO: eager limit
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->with('parent');
    }

    public static function rules(): array
    {
        return [
            'parent_id' => ['nullable', Rule::exists('comments', 'id')],
            'body' => ['required', 'string'],
        ];
    }
}
