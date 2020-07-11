<?php

namespace Modules\Platform\User\Http\Controllers\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;
use Modules\Platform\Companies\Datatables\Scope\CurrentCompanyScope;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\User\Datatables\UsersDatatable;
use Modules\Platform\User\Http\Forms\ChangePasswordForm;
use Modules\Platform\User\Http\Forms\UserForm;
use Modules\Platform\User\Http\Requests\UserCreateRequest;
use Modules\Platform\User\Http\Requests\UserUpdateRequest;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * CRUD UserController
 *
 * Class UserController
 * @package Modules\Platform\User\Http\Controllers\User
 */
class UserController extends SettingsCrudController
{

    protected $demoMode = true;

    protected $datatable = UsersDatatable::class;

    protected $formClass = UserForm::class;

    protected $storeRequest = UserCreateRequest::class;

    protected $updateRequest = UserUpdateRequest::class;

    protected $repository = UserRepository::class;

    protected $moduleName = 'user';

    protected $jsFiles = [
        'BAP_Users.js'
    ];

    protected $includeViews = [
        'user::users.changePassword'
    ];

    protected $languageFile = 'user::users';


    protected $showFields = [
        'login_and_role' => [
            'first_name' => ['type' => 'text',  'col-class' => 'col-lg-4'],
            'last_name' => ['type' => 'text',  'col-class' => 'col-lg-4'],
            'email' => ['type' => 'email',  'col-class' => 'col-lg-4'],
            'company_id' => ['type' => 'manyToOne', 'relation' => 'company', 'column' => 'name','col-class'=>'col-lg-4', 'permission' => 'settings.access'],
            'is_active' => ['type' => 'checkbox', 'col-class' => 'col-lg-4'],
            'access_to_all_entity' => ['type' => 'checkbox', 'col-class' => 'col-lg-4'],
            'roles' => ['type' => 'oneToMany', 'relation' => 'roles', 'column' => 'display_name','col-class'=>'col-lg-6 clear-both'],
            'token' => ['type' => 'text',  'col-class' => 'col-lg-12', 'permission' => 'settings.access'],
            'owner_id' => ['type' => 'manyToOne', 'relation' => 'owner', 'column' => 'name','col-class'=>'col-lg-6'],
        ],
        'address' => [
            'address_country' => ['type' => 'text'],
            'address_state' => ['type' => 'text'],
            'address_city' => ['type' => 'text'],
            'address_postal_code' => ['type' => 'text'],
            'address_street' => ['type' => 'text'],
        ],
        'more' => [
            'title' => ['type' => 'text'],
            'department' => ['type' => 'text'],
            'office_phone' => ['type' => 'text'],
            'mobile_phone' => ['type' => 'text'],
            'home_phone' => ['type' => 'text'],
            'signature' => ['type' => 'text'],
            'fax' => ['type' => 'text'],
            'secondary_email' => ['type' => 'email'],
        ],
        'settings' => [
            'theme' => ['type' => 'text'],
            'language_id' => ['type' => 'manyToOne', 'relation' => 'language', 'column' => 'name','dont_translate' => true],
            'date_format_id' => ['type' => 'manyToOne', 'relation' => 'dateFormat', 'column' => 'name','dont_translate' => true],
            'time_format_id' => ['type' => 'manyToOne', 'relation' => 'timeFormat', 'column' => 'name','dont_translate' => true],
            'time_zone' => ['type' => 'text']
        ],
        'profile' => [
            'profile_pic_conf' => ['type' => 'localized_text'],
            'profile_picture' => ['type' => 'none', 'hide_in_show' => true]

        ],
        'others' => [
            'created_at' => ['type'=>'datetime','hide_in_form'=>true],
            'updated_at' => ['type'=>'datetime','hide_in_form'=>true]
        ]
    ];


    protected $routes = [
        'index' => 'settings.users.index',
        'show' => 'settings.users.show',
        'create' => 'settings.users.create',
        'edit' => 'settings.users.edit',
        'update' => 'settings.users.update',
        'store' => 'settings.users.store',
        'destroy' => 'settings.users.destroy'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Overwritten function for add password form
     * @param $identifier
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($identifier)
    {
        if(!\Auth::user()->hasPermissionTo('settings.access')){
            $user = $request = \App::make($this->repository)->findWithoutFail($identifier);
            if($user->company_id != \Auth::user()->company_id){
                flash(trans($this->languageFile . '.unaproved'))->error();
                return redirect(route($this->routes['index']));
            }
        }

        $view = parent::show($identifier);

        $passwordForm = $this->form(ChangePasswordForm::class, [
            'method' => 'POST',
            'url' => route('settings.users.change-password', $identifier),
            'id' => 'user_change_password'
        ]);

        $view->with('passwordForm', $passwordForm);

        return $view;
    }

    public function index()
    {
        $datatable = \App::make($this->datatable);

        $datatable->addScope(new CurrentCompanyScope());

        $indexView = $this->views['index'];

        return $datatable->render($indexView);
    }

    /**
     * overwritten function to apply password
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {

        $user = \Auth::user();

        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }
        $request = \App::make($this->storeRequest ?? Request::class);

        $input = $request->all();

        $randomPassword = UserHelper::randomPassword(6);

        $input['password'] = bcrypt($randomPassword);


        if(!$user->hasRoleAdmin()){

            $userRepo = App::make(UserRepository::class);

            $usersCount = $userRepo->countUsersForCompany($user->company);

            if($usersCount >= $user->company->user_limit){
                flash(trans('user::users.you_cant_have_more_users',['planCount' => $user->company->user_limit,'currentCount' => $usersCount]))->error();

                return redirect()->back()->withInput($input);
            }

        }


        $entity = \App::make($this->repository)->create($input);

        if (is_null($request->get('roles'))) {
            $entity->syncRoles([]);
        } else {

            //Check if user is admin and can create user with admin role

            if(\Auth::user()->hasPermissionTo('settings.access')){
                $entity->syncRoles($request->get('roles'));
            }else{
                if (in_array('admin',$request->get('roles'))){
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['show'], $entity));
                }
                $entity->syncRoles($request->get('roles'));
            }


        }

        flash(trans($this->languageFile . '.created', ['password'=>$randomPassword]))->success();

        return redirect(route($this->routes['index']));
    }

    public function edit($identifier)
    {

        if(!\Auth::user()->hasPermissionTo('settings.access')){
            $user = $request = \App::make($this->repository)->findWithoutFail($identifier);
            if($user->company_id != \Auth::user()->company_id){
                flash(trans($this->languageFile . '.unaproved'))->error();
                return redirect(route($this->routes['index']));
            }
        }

        return parent::edit($identifier);
    }

    public function genToken($identifier)
    {
        $repository = \App::make($this->repository);

        $user = $repository->findWithoutFail($identifier);

        $data = [
            'id' => $user->id,
            'company_id' => $user->company_id,
            'owner_id' => $user->owner_id,
        ];

        \cryptor::setKey(config('app.salt_api'), config('app.key_api'));

        $str = json_encode($data, JSON_UNESCAPED_UNICODE);
        $token = \cryptor::encrypt($str);

        $repository->updateEntity(['token' => $token], $user);

        return compact('user', 'token');
    }

    public function destroy($identifier)
    {
        if(!\Auth::user()->hasPermissionTo('settings.access')){
            $user = $request = \App::make($this->repository)->findWithoutFail($identifier);
            if($user->company_id != \Auth::user()->company_id){
                flash(trans($this->languageFile . '.unaproved'))->error();
                return redirect(route($this->routes['index']));
            }
        }

        return parent::destroy($identifier);
    }

    /**
     * Update Entity
     * overwritten function to apply syncRoles
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        if(!\Auth::user()->hasPermissionTo('settings.access')){
            $user = $request = \App::make($this->repository)->findWithoutFail($identifier);
            if($user->company_id != \Auth::user()->company_id){
                flash(trans($this->languageFile . '.unaproved'))->error();
                return redirect(route($this->routes['index']));
            }
        }


        $request = \App::make($this->updateRequest ?? Request::class);

        $repository = \App::make($this->repository);

        $entity = $repository->find($identifier);


        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->success();

            return redirect(route($this->routes['index']));
        }

        $entity = \App::make($this->repository)->update($request->all(), $identifier);


        if (is_null($request->get('roles'))) {
            $entity->syncRoles([]);
        } else {

            //Check if user is admin and can create user with admin role

            if(\Auth::user()->hasPermissionTo('settings.access')){
                $entity->syncRoles($request->get('roles'));
            }else{
                if (in_array('admin',$request->get('roles'))){
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['show'], $entity));
                }
                $entity->syncRoles($request->get('roles'));
            }
        }

        // UPLOAD PROFILE PICTURE
        $profilePicture = $request->file('profile_picture');

        if ($profilePicture != null) {
            $image = 'profile_' . $identifier . '_.' . $profilePicture->getClientOriginalExtension();

            $uploadSuccess = $profilePicture->move(UserHelper::PROFILE_PICTURE_UPLOAD, $image);

            if ($uploadSuccess) {

                // Resize uploaded image to 100x100
                $img = Image::make(base_path() . '/public/' . UserHelper::PROFILE_PICTURE_UPLOAD . $image)->resize(
                    100,
                    100
                );
                $img->save(base_path() . '/public/' . UserHelper::PROFILE_PICTURE_UPLOAD . $image);

                $entity = \App::make($this->repository)->update([
                    'profile_image_path' => $image
                ], $identifier);
            }
        }

        flash(trans($this->languageFile . '.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
                'data-toggle' => 'modal',
                'data-target' => '#defaultModal'
            ],
            'label' => trans('user::users.change_password')
        );

        $this->customShowButtons[] = array(
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
                'onclick' => 'BAP_Users.loginAsUser("' . $this->entity->id . '")',
            ],
            'label' => trans('user::users.ghost_login'),
        );

        $this->customShowButtons[] = array(
            'href' => route('settings.users.activity', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
            ],
            'label' => trans('user::users.activity_log')
        );
    }
}
