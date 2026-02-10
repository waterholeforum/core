<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Bookmark;
use Waterhole\Models\User;

/**
 * Methods to make a model bookmarkable by users.
 */
trait Bookmarkable
{
    protected static function bootBookmarkable(): void
    {
        $deleteBookmarks = function (self $model) {
            $model->bookmarks()->delete();
        };

        if (method_exists(static::class, 'forceDeleting')) {
            static::forceDeleting($deleteBookmarks);
        } else {
            static::deleting($deleteBookmarks);
        }
    }

    /**
     * Relationship with all bookmarks for this model.
     */
    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'content');
    }

    /**
     * Relationship with the bookmark for a specific user.
     *
     * Defaults to the current user when no user is specified, mirroring the
     * behavior of HasUserState relationships.
     */
    public function bookmark(?User $user = null): MorphOne
    {
        $relation = $this->morphOne(Bookmark::class, 'content');

        if ($userId = $user->id ?? Auth::id()) {
            $relation
                ->where($relation->qualifyColumn('user_id'), $userId)
                ->withDefault(['user_id' => $userId]);
        } else {
            $relation->whereRaw('1 = 0');
        }

        return $relation;
    }

    /**
     * Load the bookmark relationship for the given user.
     */
    public function loadBookmark(User $user): static
    {
        $this->setRelation('bookmark', $this->bookmark($user)->getResults());

        return $this;
    }

    /**
     * Determine whether this model is bookmarked by the current user.
     */
    public function isBookmarked(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        if ($this->relationLoaded('bookmark')) {
            return (bool) $this->bookmark?->exists;
        }

        return $this->bookmark()->exists();
    }

    /**
     * Link target to use for this model in Saved lists.
     */
    abstract public function bookmarkUrl(): string;

    /**
     * Title to display for this model in Saved lists.
     */
    abstract public function bookmarkTitle(): string;

    /**
     * Optional excerpt to display for this model in Saved lists.
     */
    public function bookmarkExcerpt(): ?string
    {
        return $this->body_text ?? null;
    }

    /**
     * Optional icon to display for this model in Saved lists.
     */
    public function bookmarkIcon(): ?string
    {
        return null;
    }

    /**
     * Optional user to display for this model in Saved lists.
     */
    public function bookmarkUser(): ?User
    {
        return method_exists($this, 'user') ? $this->user : null;
    }

    /**
     * Relationships to eager-load when rendering this model as bookmarked
     * content.
     *
     * @return array<int, string>
     */
    public static function bookmarkMorphWith(): array
    {
        return [];
    }
}
