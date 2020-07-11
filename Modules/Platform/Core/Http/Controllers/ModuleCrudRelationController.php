<?php

namespace Modules\Platform\Core\Http\Controllers;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Modules\Platform\Core\Datatable\Scope\OwnableEntityScope;
use Modules\Platform\Core\Helper\EntityAccessHelper;
use Modules\Platform\Core\Repositories\GenericRepository;

/**
 * Class ModuleCrudRelationController
 * @package Modules\Platform\Core\Http\Controllers
 */
abstract class ModuleCrudRelationController extends AppBaseController
{
    const RT_MANY_TO_MANY = 'manyToMany';

    const RT_ONE_TO_MANY = 'oneToMany';

    const WHERE_TYPE__COLUMN = 'column';
    const WHERE_TYPE__IN     = 'in';

    use FormBuilderTrait;

    /**
     * Relation Datatable
     * @var
     */
    protected $datatable;

    /**
     * Owner Model Entity
     * @var
     */
    protected $ownerModel;

    /**
     * Related model Entity
     * @var
     */
    protected $relationModel;

    /**
     * Related module name
     * Optional
     * Needed to verify if user has permission to link,unlink,edit,delete related record.
     * @var
     */
    protected $relatedModuleName;

    /**
     * Owner module name
     * @var
     */
    protected $ownerModuleName;

    /**
     * Scope linked record
     * @var
     */
    protected $scopeLinked;

    /**
     * Name of relation in owner module (eg. "documents" - Lead class has "documents")
     * @var
     */
    protected $modelRelationName;

    protected $relationType = self::RT_MANY_TO_MANY;

    protected $belongsToName;

    /**
     * Where condition
     * @var
     */
    protected $whereCondition;

    protected $whereType;

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function link()
    {

        $error = false;

        $ownerEntityId = request()->get('entityId', null);
        $relationEntityIds = request()->get('relationEntityIds', null);

        $relationEntityIds = json_decode($relationEntityIds);


        if ($ownerEntityId == null || $relationEntityIds == null) {
            throw new \Exception('Entity Id or Related Entity IDS is null');
        }

        $ownerEntityRepo = \App::make(GenericRepository::class);
        $ownerEntityRepo->setupModel($this->ownerModel);


        $ownerEntity = $ownerEntityRepo->findWithoutFail($ownerEntityId);

        $relationEntityRepos = \App::make(GenericRepository::class);
        $relationEntityRepos->setupModel($this->relationModel);

        foreach ($relationEntityIds as $relatedId) {
            $relationEntity = $relationEntityRepos->findWithoutFail($relatedId);

            if(!empty($this->relatedModuleName)) {
                if (EntityAccessHelper::blockEntityOwnableAccess($this->relatedModuleName, $relationEntity, \Auth::user())) {
                    return response()->json([
                        'type' => 'error',
                        'message' => trans('core::core.entity.you_dont_have_access'),
                        'action' => 'show_message'
                    ]);
                }
            }

            if ($ownerEntity != null) {
                if($this->relationType == self::RT_MANY_TO_MANY) {
                    $ownerEntity->{$this->modelRelationName}()->attach($relationEntity->id);
                }else{
                    $relationEntity->{$this->belongsToName}()->associate($ownerEntity->id);
                    $relationEntity->save();
                }
            } else {
                $error = true;
            }
        }

        if ($error) {
            return response()->json([
                'type' => 'error',
                'message' => trans('core::core.entity.entity_not_found'),
                'action' => 'show_message'
            ]);
        }

        return response()->json([
            'type' => 'success',
            'message' => trans('core::core.entity.entity_linked'),
            'action' => 'refresh_datatable'
        ]);
    }

    /**
     * Unlink related record
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function unlink()
    {
        $ownerEntityId = request()->get('entityId', null);
        $relationEntityId = request()->get('relationEntityId', null);

        if ($ownerEntityId == null || $relationEntityId == null) {
            throw new \Exception('Entity Id or Related Entity ID is null');
        }

        $ownerEntityRepo = \App::make(GenericRepository::class);
        $ownerEntityRepo->setupModel($this->ownerModel);

        $ownerEntity = $ownerEntityRepo->findWithoutFail($ownerEntityId);

        $relationEntityRepos = \App::make(GenericRepository::class);
        $relationEntityRepos->setupModel($this->relationModel);

        $relationEntity = $relationEntityRepos->findWithoutFail($relationEntityId);


        if(!empty($this->relatedModuleName)) {
            if (EntityAccessHelper::blockEntityOwnableAccess($this->relatedModuleName, $relationEntity, \Auth::user())) {
                return response()->json([
                    'type' => 'error',
                    'message' => trans('core::core.entity.you_dont_have_access'),
                    'action' => 'show_message'
                ]);
            }
        }

        if ($ownerEntity != null) {
            if ($this->relationType == self::RT_MANY_TO_MANY) {
                $ownerEntity->{$this->modelRelationName}()->detach($relationEntity->id);
            } else {
                $relationEntity->{$this->belongsToName}()->dissociate();
                $relationEntity->save();
            }

            return response()->json([
                'type' => 'success',
                'message' => trans('core::core.entity.entity_unlinked'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => trans('core::core.entity.entity_not_found'),
                'action' => 'show_message'
            ]);
        }
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete()
    {
        $ownerEntityId = request()->get('entityId', null);
        $relationEntityId = request()->get('relationEntityId', null);

        if ($ownerEntityId == null || $relationEntityId == null) {
            throw new \Exception('Entity Id or Related Entity ID is null');
        }

        $ownerEntityRepo = \App::make(GenericRepository::class);
        $ownerEntityRepo->setupModel($this->ownerModel);

        $ownerEntity = $ownerEntityRepo->findWithoutFail($ownerEntityId);

        $relationEntityRepos = \App::make(GenericRepository::class);
        $relationEntityRepos->setupModel($this->relationModel);

        $relationEntity = $relationEntityRepos->findWithoutFail($relationEntityId);


        if(!empty($this->relatedModuleName)) {
            if (EntityAccessHelper::blockEntityOwnableAccess($this->relatedModuleName, $relationEntity, \Auth::user())) {
                return response()->json([
                    'type' => 'error',
                    'message' => trans('core::core.entity.you_dont_have_access'),
                    'action' => 'show_message'
                ]);
            }
        }

        if ($ownerEntity != null) {

            $relationEntity->delete();

            return response()->json([
                'type' => 'success',
                'message' => trans('core::core.entity.entity_deleted'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => trans('core::core.entity.entity_not_found'),
                'action' => 'show_message'
            ]);
        }
    }

    /**
     * Return linked records from another module
     *
     * @param $entityId
     * @return mixed
     */
    public function linked($entityId)
    {


        $ownerModel = \App::make($this->ownerModel);

        $datatable = \App::make($this->datatable);
        $datatable->allowUnlink = true;
        $datatable->setEntityData(get_class($ownerModel), $entityId, '');


        $repository = \App::make(GenericRepository::class);
        $repository->setupModel($this->ownerModel);


        $entity = $repository->findWithoutFail($entityId);


        if ($this->scopeLinked != null) {
            $scope = \App::make($this->scopeLinked);
            $scope->relation($entity->{$this->modelRelationName},$this->whereCondition,$this->whereType,$entityId);

            $datatable->addScope($scope);
        }
        if (EntityAccessHelper::scopedAccess($this->ownerModuleName, \Auth::user())) {
            $datatable->addScope(new OwnableEntityScope(\Auth::user(), $this->ownerModuleName,$this->relationModel));
        }

        return $datatable->render('core::crud.relation.relation');
    }

    /**
     * @param $entityId
     * @return mixed
     */
    public function selection($entityId)
    {
        $ownerModel = \App::make($this->ownerModel);

        $datatable = \App::make($this->datatable);
        $datatable->allowSelect = true;
        $datatable->setEntityData(get_class($ownerModel), $entityId, '');

        $filters = request()->get('rules', null);

        if (!empty($filters)) {

            $datatable->applyFilterRules($filters);
        }


        $repository = \App::make(GenericRepository::class);
        $repository->setupModel($this->ownerModel);

        if (EntityAccessHelper::scopedAccess($this->ownerModuleName, \Auth::user())) {
            $datatable->addScope(new OwnableEntityScope(\Auth::user(), $this->ownerModuleName,$this->relationModel));
        }

        return $datatable->render('core::crud.relation.relation');
    }
}
