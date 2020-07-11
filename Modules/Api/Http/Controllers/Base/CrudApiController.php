<?php

namespace Modules\Api\Http\Controllers\Base;


use App\Http\Controllers\Controller;
use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Api\Traits\RespondTrait;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Core\Datatable\Scope\OwnableEntityScope;
use Modules\Platform\Core\Helper\ValidationHelper;
use Modules\Platform\Core\Repositories\GenericRepository;
use Modules\Platform\Core\Traits\CrudEventsTrait;
use Modules\Platform\Core\Traits\ModuleOwnableTrait;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;
use Stringy\Stringy;

/**
 * Generic API Controller

 * Class CrudApiController
 *
 * @package Modules\Api\Http\Controllers\Base
 */
abstract class CrudApiController extends Controller
{
    use RespondTrait, ModuleOwnableTrait, CrudEventsTrait;

    protected $skipAuth = false;

    /**
     * Permissions
     * @var array
     */
    protected $permissions = [
        'browse' => '',
        'create' => '',
        'update' => '',
        'destroy' => ''
    ];
    /**
     * Path to language files
     * @var
     */
    protected $languageFile;

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    /**
     * Module Entity Class
     * @var
     */
    protected $entityClass;

    /**
     * Module Store Request
     * @var
     */
    protected $storeRequest;
    /**
     * Module Update Request
     * @var
     */
    protected $updateRequest;

    /**
     * Return entity with relations
     *
     * @var array
     */
    protected $with = [];

    /**
     * Module name - same as module folder
     * Example
     * - User Module = "expenses"
     * @var
     */
    protected $moduleName;

    /**
     * Module Entity
     * @var
     */
    protected $entity;

    /**
     * @var
     */
    protected $entityIdentifier;

    /**
     * Show entity web route (for notifications)
     *
     * @var null
     */
    protected $showRoute = null;

    protected $checkValidate = true;

    /**
     * SettingsCrudController constructor.
     */
    public function __construct()
    {
        if($this->checkValidate) {
            $this->validateModule();
        }

        if(!$this->skipAuth) {
            $this->middleware('jwt.auth');
        }
    }

    /**
     * Validate module controller setup
     * @throws \Exception
     */
    public function validateModule()
    {
        if ($this->repository == null && $this->entityClass == null) {

            throw new \Exception('Please set repository');
        }
        if ($this->storeRequest == null || $this->updateRequest == null) {

            throw new \Exception('Please set storeRequest and updateRequest');
        }
    }

    /**
     * Show module DataTable
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        $result = App::make($this->entityClass)->with($this->with);

        if ($this->scopedAccess()) { // Entity scope
            $scope = new OwnableEntityScope(\Auth::user(),$this->moduleName);
            $result = $scope->apply($result);
        }

        return $this->respond(true,[$result->paginate()],[],['message' =>  trans('core::core.entity.records_found')]);
    }

    /**
     * Get Entity details
     *
     * @param $identifier
     * @return array
     */
    public function get($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        $repository = $this->getRepository();

        $entity = $repository->with($this->with)->findWithoutFail($identifier);

        $this->entity = $entity;

        if (empty($entity)) {

            return $this->respond(false, [], ['error' => 'entity_not_found'], ['message' => trans('core::core.entity.entity_not_found')]);
        }

        if ($this->blockEntityOwnableAccess()) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        return $this->respond(true, [$this->entity], [], ['message' => trans('core::core.entity.entity_found')]);
    }

    /**
     * Create instance of module repository or use generic repository
     *
     * @return mixed
     */
    protected function getRepository()
    {
        if ($this->repository == GenericRepository::class) {
            $repository = \App::make($this->repository);
            $repository->setupModel($this->entityClass);
        } else {
            $repository = \App::make($this->repository);
        }

        return $repository;
    }

    /**
     * Store entity
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {

        $request = \App::make($this->storeRequest ?? Request::class);

        if(!$this->skipAuth) {
            if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
                return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
            }
        }

        $repository = $this->getRepository();

        $input = $request->all();

        $this->beforeStoreInput($request, $input);

        $entity = $repository->createEntity($input, \App::make($this->entityClass));

        $entity = $this->setupAssignedTo($entity, $request->all(), true);
        $entity->save();

        $this->entity = $repository->with($this->with)->findWithoutFail($entity->id);

        if (config('bap.record_assigned_notification_enabled')) {

            if ($entity instanceof Ownable) {
                if ($entity->getOwner() != null && $entity->getOwner() instanceof User) {
                    if ($entity->getOwner()->id != \Auth::user()->id) { // Dont send notification for myself
                        try {
                            $commentOn = $entity->name;
                            $commentOn = ' - ' . $commentOn;
                        } catch (\Exception $exception) {
                            $commentOn = '';
                        }

                        $placeholder = new NotificationPlaceholder();

                        $placeholder->setRecipient($entity->getOwner());
                        $placeholder->setAuthorUser(\Auth::user());
                        $placeholder->setAuthor(\Auth::user()->name);
                        $placeholder->setColor('bg-green');
                        $placeholder->setIcon('assignment');
                        $placeholder->setContent(trans('notifications::notifications.new_record', ['user' => \Auth::user()->name]) . $commentOn);

                        $placeholder->setUrl(route($this->showRoute, $entity->id));

                        $entity->getOwner()->notify(new GenericNotification($placeholder));
                    }
                }
            }
        }
        return $this->respond(true, [$this->entity], [], ['message' => trans('core::core.entity.created')]);
    }

    /**
     * Setup Assigned (User|Group)
     *
     * @param mixed $entity - Entity object
     * @param array $input Values from request
     * @param bool $creating - creating mode
     *
     * @return mixed
     */
    protected function setupAssignedTo($entity, $input, $creating = false)
    {
        if ($entity instanceof Ownable) {
            if (isset($input['owned_by'])) {
                $owner = Stringy::create($input['owned_by']);
            } else {
                $owner = '';
            }

            if ($owner != '') {
                if ($owner->startsWith('user-')) {
                    $owner = $owner->replace('user-', '');

                    $entity->changeOwnerTo(User::find($owner));
                } else {
                    $owner = $owner->replace('group-', '');
                    $entity->changeOwnerTo(Group::find($owner));
                }
            } else {
                if (!$creating) {
                    $entity->abandonOwner();
                }
            }
        }

        return $entity;
    }


    /**
     * Update entity
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        $request = \App::make($this->updateRequest ?? Request::class);

        if ($this->permissions['update'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['update'])) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        $repository = $this->getRepository();

        $entity = $repository->findWithoutFail($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            return $this->respond(false, [], ['error' => 'entity_not_found'], ['message' => trans('core::core.entity.entity_not_found')]);
        }

        if ($this->blockEntityOwnableAccess()) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        $input = $request->all();

        $currentOwner = null;
        if ($entity instanceof Ownable && $entity->hasOwner()) {
            $currentOwner = $entity->getOwner();
        }

        $entity = $this->setupAssignedTo($entity, $input);

        $repository = $this->getRepository();

        $entity = $repository->updateEntity($input, $entity);

        $this->entity = $repository->with($this->with)->findWithoutFail($identifier);

        return $this->respond(true, [$this->entity], [], ['message' => trans('core::core.entity.updated')]);
    }

    /**
     * @param $identifier
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($identifier)
    {
        if ($this->permissions['destroy'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['destroy'])) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        $repository = $this->getRepository();

        $entity = $repository->findWithoutFail($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            return $this->respond(false, [], ['error' => 'entity_not_found'], ['message' => trans('core::core.entity.entity_not_found')]);
        }

        if ($this->blockEntityOwnableAccess()) {
            return $this->respond(false, [], ['error' => 'user_dont_no_access'], ['message' => trans('core::core.you_dont_have_access')]);
        }

        if (config('bap.validate_fk_on_soft_delete')) {
            $validator = ValidationHelper::validateForeignKeys($entity);

            if (count($validator) > 0) {
                return $this->respond(false, [], ['error' => 'constraints_error'], ['message' => trans('core::core.cant_delete_check_fk_keys', ['fk_keys' => StringHelper::validationArrayToString($validator)])]);
            }
        }

        $repository->delete($entity->id);

        return $this->respond(false, [], [], ['message' => trans('core::core.entity.deleted')]);

    }

}
