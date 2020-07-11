<?php

namespace Modules\Platform\Core\Traits;

use Modules\Platform\Core\Entities\Comment;

/**
 * Trait CanComment
 * @package Modules\Platform\Core\Traits
 */
trait CanComment
{

    /**
     * @param $commentable
     * @param string $commentText
     * @param int $rate
     * @return $this
     */
    public function comment($commentable, $commentText = '', $rate = 0)
    {
        $comment = new Comment([
            'comment' => $commentText,
            'approved' => ($commentable->mustBeApproved() && !$this->isAdmin()) ? false : true,
            'commented_id' => $this->id,
            'commented_type' => get_class()
        ]);

        $commentable->comments()->save($comment);

        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }
}
