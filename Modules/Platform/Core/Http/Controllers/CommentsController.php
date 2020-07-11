<?php

namespace Modules\Platform\Core\Http\Controllers;

use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\Core\Repositories\CommentsRepository;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\User\Entities\User;
use Modules\Platform\User\Repositories\UserRepository;

class CommentsController extends AppBaseController
{

    /**
     * @var CommentsRepository
     */
    private $commentsRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(CommentsRepository $commentRepo, UserRepository $userRepo)
    {
        parent::__construct();

        $this->commentsRepository = $commentRepo;
        $this->userRepository = $userRepo;
    }

    public function getComments(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);


        $comments = $this->commentsRepository->findWhere([
            'commentable_type' => $entityClass,
            'commentable_id' => $entity->id
        ]);

        $resultComments = [];

        foreach ($comments as $comment) {
            $resultComments[] = $this->commentsRepository->convertCommentToPluginResult($comment);
        }

        return \Response::json($resultComments);
    }


    public function getUsers()
    {
        $users = $this->userRepository->get();

        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'fullname' => $user->name,
                'email' => $user->email,
                'profile_picture_url' => UserHelper::getUserProfileImage($user->id)
            ];
        }

        return \Response::json($result);
    }


    /**
     * Post Comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postComment(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $comment = $request->get('comment');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);

        $path = $request->get('path');

        if (isset($comment['parent']) && $comment['parent'] > 0) {
            $parentCommentId = $comment['parent'];
        } else {
            $parentCommentId = null;
        }

        $comment = $this->commentsRepository->create([
            'commentable_id' => $entity->id,
            'commentable_type' => get_class($entity),
            'commented_id' => $user->id,
            'commented_type' => get_class($user),
            'comment' => $comment['content'],
            'parent_id' => $parentCommentId,
            'approved' => 1,
            'upvote' => 0,
            'created_at' => Carbon::now()
        ]);

        $this->commentsRepository->commentCreatedNotification($comment);

        $resultComment = $this->commentsRepository->convertCommentToPluginResult($comment);

        if (config('bap.comment_notification_enabled')) { // Check if comment notification is enabled
            if ($entity instanceof Ownable) { // Entity is ownable and we can send notification

                if ($entity->getOwner() instanceof User) {

                    if ($entity->getOwner()->id != \Auth::user()->id) { // Dont send notification for myself
                        try {
                            $commentOn = $entity->name;
                            $commentOn = ' ' . trans('core::core.on') . ' ' . $commentOn;
                        } catch (\Exception $exception) {
                            $commentOn = '';
                        }

                        $placeholder = new NotificationPlaceholder();

                        $placeholder->setRecipient($entity->getOwner());
                        $placeholder->setAuthorUser($user);
                        $placeholder->setAuthor($user->name);
                        $placeholder->setColor('bg-green');
                        $placeholder->setIcon('comment');
                        $placeholder->setContent(trans('notifications::notifications.new_comment', ['user' => $user->name]) . $commentOn);
                        $placeholder->setExtraContent($comment->comment);
                        $placeholder->setUrl($path);

                        $entity->getOwner()->notify(new GenericNotification($placeholder));
                    }
                }
            }
        }


        return \Response::json($resultComment);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $comment = $request->get('comment');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);

        if (isset($comment['parent'])) {
            $parentCommentId = $comment['parent'];
        } else {
            $parentCommentId = null;
        }

        $dbComment = $this->commentsRepository->findWithoutFail($comment['id']);

        if ($dbComment != null) {
            $dbComment = $this->commentsRepository->update([
                'comment' => $comment['content']
            ], $dbComment->id);

            $resultComment = $this->commentsRepository->convertCommentToPluginResult($dbComment);

            return \Response::json($resultComment);
        } else {
            return \Response::json([
                'error' => 'comment_not_found'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $comment = $request->get('comment');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);


        $dbComment = $this->commentsRepository->findWithoutFail($comment['id']);

        if ($dbComment != null) {
            $this->commentsRepository->delete($dbComment->id);

            return \Response::json([
                'result' => 'success'
            ]);
        } else {
            return \Response::json([
                'error' => 'comment_not_found'
            ]);
        }
    }

    /**
     *
     * Upvote/Downvote comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upvoteComment(Request $request)
    {
        $commentId = $request->get('commentId', null);


        $user = \Auth::user();

        if ($commentId != null) {
            $comment = $this->commentsRepository->findWithoutFail($commentId);

            if ($comment != null) {
                if ($this->commentsRepository->userHasUpvoted($comment, $user)) {
                    $this->commentsRepository->deleteUserUpvote($comment, $user);
                } else {
                    $this->commentsRepository->insertUserUpvote($comment, $user);
                }

                $resultComment = $this->commentsRepository->convertCommentToPluginResult($comment);

                return \Response::json($resultComment);
            } else {
                return \Response::json([
                    'error' => 'comment_not_found'
                ]);
            }
        }

        return \Response::json([
            'error' => 'comment_not_found'
        ]);
    }
}
