<?php

namespace Modules\Platform\Core\Http\Controllers;

use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Core\Helper\FileHelper;
use Modules\Platform\Core\Http\Requests\AttachmentRequest;
use Modules\Platform\Core\Repositories\AttachmentsRepository;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\User\Entities\User;

class AttachmentsController extends AppBaseController
{

    /**
     * @var AttachmentsRepository
     */
    private $attachmentRepository;

    /**
     * AttachmentsController constructor.
     * @param AttachmentsRepository $repo
     */
    public function __construct(AttachmentsRepository $repo)
    {
        parent::__construct();

        $this->attachmentRepository = $repo;
    }

    /**
     * @param $entityClass
     * @param $entityId
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttachment($entityClass, $entityId, $key)
    {
        $user = \Auth::user();

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);


        if ($entity != null) {
            $attachment = $entity->attachment($key);

            if ($attachment != null) {
                $result = $attachment->delete();

                if ($result) {
                    $message = 'attachment_deleted';
                } else {
                    $message = 'error_while_deleting_attachment';
                }

                return \Response::json([
                    'message' => $message,
                ]);
            }
        } else {
            return \Response::json([
                'message' => 'entity_not_found'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttachments(Request $request)
    {
        $user = \Auth::user();

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);


        if ($entity != null) {
            $files = [];

            foreach ($entity->attachments as $attachment) {
                $files[] = $this->prepareAttachment($attachment, $entity, $entityId);
            }

            return \Response::json([
                'files' => $files,
            ]);
        }

        return \Response::json([
            'message' => 'no_attachments_found'
        ]);
    }

    /**
     * @param $attachment
     * @param $entity
     * @param $entityId
     * @return array
     */
    private function prepareAttachment($attachment, $entity, $entityId)
    {
        $entityId = $entity->id;


        return [

            'url' => $attachment->url,
            'thumbnailUrl' => FileHelper::displayAttachmentIcon($attachment),
            'name' => $attachment->filename,
            'type' => $attachment->filetype,
            'size' => $attachment->filesize,
            'deleteUrl' => route('core.ext.attachments.delete-attachment', [
                'entityClass' => get_class($entity),
                'entityId' => $entityId,
                'key' => $attachment->key
            ]),
            'deleteType' => 'delete'
        ];
    }

    /**
     * Attachment request
     *
     * @param AttachmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAttachments(AttachmentRequest $request)
    {
        $user = \Auth::user();


        if(!$user->hasRoleAdmin()){

            if($user->company != null && $user->company->storage_limit != null && $user->company->storage_limit > 0 ) {

                $allFileSize = $this->attachmentRepository->countFileSizeForCompany($user->company); // count file sizes

                if ($allFileSize >= $user->company->storage_limit) {

                    return \Response::json([
                        'files' => [
                            [
                                'url' => '/bap/images/file_icon.png',
                                'thumbnailUrl' => '/bap/images/file_icon.png',
                                'name' => trans('core::core.you_exceeded_storage_please_switch_plan',['gigs' => $user->company->storage_limit]),
                                'size' => 1234,
                                'type' => 'png'
                            ]
                        ],
                    ]);

                }
            }

        }


        if (config('bap.demo')) {
            return \Response::json([
                'files' => [
                    [
                        'url' => '/bap/images/file_icon.png',
                        'thumbnailUrl' => '/bap/images/file_icon.png',
                        'name' => trans('core::core.you_cant_do_that_its_demo'),
                        'size' => 1234,
                        'type' => 'png'
                    ]
                ],
            ]);
        }

        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');

        $entityClass = str_replace('&quot;', '', $entityClass);


        $entity = app($entityClass)->find($entityId);

        $path = $request->get('path');

        if ($entity != null) {

            $params = [];

            if($user->company != null && $user->company->id != null){
                $params = [
                    'company_id' => $user->company_id
                ];
            }

            $attachment = $entity->attach(\Request::file('files'),$params);


            $files[] = $this->prepareAttachment($attachment, $entity, $entityId);

            if(config('bap.attachment_notification_enabled')) { // Check if attachment notification is enabled
                if ($entity instanceof Ownable) { // Entity is ownable and we can send notification

                    if ($entity->getOwner() instanceof User) {

                        if($entity->getOwner()->id != \Auth::user()->id) { // Dont send notification for myself
                            try {
                                $commentOn = $entity->name;
                                $commentOn = ' ' . trans('core::core.on') . ' ' . $commentOn;
                            } catch (\Exception $exception) {
                                $commentOn = '';
                            }

                            $placeholder = new NotificationPlaceholder()
                            ;

                            $placeholder->setRecipient($entity->getOwner());
                            $placeholder->setAuthorUser($user);
                            $placeholder->setAuthor($user->name);
                            $placeholder->setColor('bg-deep-orange');
                            $placeholder->setIcon('attach_file');
                            $placeholder->setContent(trans('notifications::notifications.new_attachment', ['user' => $user->name]) . $commentOn);

                            $placeholder->setUrl($path);

                            $entity->getOwner()->notify(new GenericNotification($placeholder));
                        }
                    }
                }
            }

            return \Response::json([
                'files' => $files,
            ]);
        } else {
            return \Response::json([
                'message' => 'entity_not_found'
            ]);
        }
    }
}
