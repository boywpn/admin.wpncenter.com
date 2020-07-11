<?php

namespace Modules\Platform\Core\Repositories;

use Carbon\Carbon;
use Modules\Platform\Core\Entities\Comment;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class CommentsRepository
 * @package Modules\Platform\Core\Repositories
 */
class CommentsRepository extends PlatformRepository
{
    public function model()
    {
        return Comment::class;
    }

    /**
     * New comment notification
     *
     * @param Comment $comment Comment entity
     */
    public function commentCreatedNotification(Comment $comment)
    {

        $currentUser = \Auth::user();

        //TODO Implement Comment notifications
        // IF Commented entity is Ownable ..
        // If entity ownable is user && ownable <> current user // create new notification
        // If entity ownable is group -> foreach users in group // is current_user <> user in group create new notification
    }

    /**
     * Return response in javascript plugin format
     *
     * @param $comment
     * @return array
     */
    public function convertCommentToPluginResult($comment)
    {
        $user = \Auth::user();

        $result = [
            'id' => $comment->id,
            'parent' => $comment->parent_id,
            'created' => UserHelper::formatUserDateTime($comment->created_at),
            'modified' => UserHelper::formatUserDateTime($comment->updated_at),
            'content' => $this->convertCommentContent($comment->comment),
            'pings' => [], //TODO implement ping
            'creator' => $comment->commented->id,
            'fullname' => $comment->commented->name,
            'profile_picture_url' => UserHelper::getUserProfileImage($comment->commented->id),
            'created_by_admin' => $user->hasPermissionTo('settings.access'),
            'created_by_current_user' => $user->id == $comment->commented->id,
            'upvote_count' => $comment->upvote,
            'user_has_upvoted' => $this->userHasUpvoted($comment, $user)
        ];

        return $result;
    }

    /**
     * @param $content
     * @return mixed
     */
    private function convertCommentContent($content)
    {
        return $content;
    }

    /**
     * Check user upvoted
     *
     * @param $comment
     * @param $user
     * @return bool
     */
    public function userHasUpvoted($comment, $user)
    {
        $result = \DB::table(Comment::UPVOTES_TABLE_NAME)
            ->where('comment_id', '=', $comment->id)
            ->where('user_id', '=', $user->id)->get()->count();

        if ($result > 0) {
            return true;
        }
        return false;
    }

    /**
     * Delete user upvote
     *
     * @param $comment
     * @param $user
     * @return int
     */
    public function deleteUserUpvote($comment, $user)
    {
        $comment->timestamps = false;
        $comment->upvote = $comment->upvote - 1;
        $comment->save();

        return \DB::table(Comment::UPVOTES_TABLE_NAME)
            ->where('comment_id', '=', $comment->id)
            ->where('user_id', '=', $user->id)->delete();
    }

    /**
     * Insert user upvote
     * @param $comment
     * @param $user
     * @return bool
     */
    public function insertUserUpvote($comment, $user)
    {
        $comment->timestamps = false;
        $comment->upvote = $comment->upvote + 1;
        $comment->save();

        return \DB::table(Comment::UPVOTES_TABLE_NAME)->insert([
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'created_at' => Carbon::now()
        ]);
    }
}
