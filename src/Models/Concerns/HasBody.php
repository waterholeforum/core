<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\HtmlString;
use Waterhole\Models\User;

trait HasBody
{
    public function mentions(): MorphMany
    {
        return $this->morphMany(User::class, 'mentions');
    }

    public function getBodyHtmlAttribute(): HtmlString
    {
        return new HtmlString($this->body);
    }
}
