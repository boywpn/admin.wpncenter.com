<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 08.09.18
 * Time: 18:14
 */

namespace Modules\Api\Http\Controllers\Saas;


use Modules\Api\Http\Controllers\Base\SaasApiBaseController;
use Modules\Api\Http\Requests\Saas\ActivateCompanyRequest;
use Modules\Api\Http\Requests\Saas\DeactivateCompanyRequest;
use Modules\Api\Http\Requests\Saas\RegisterCompanyRequest;
use Modules\Api\Http\Requests\Saas\UpdateCompanyPlanRequest;
use Modules\Api\Service\Saas\SaasService;

class SaasApiController extends SaasApiBaseController
{

    public function __construct(SaasService $service)
    {
        parent::__construct($service);
    }

    /**
     * Register company (W)
     *
     * @param RegisterCompanyRequest $request
     */
    public function registerCompany(RegisterCompanyRequest $request)
    {

        $this->validateSecret($request);

        $companyName = $request->get('company_name');
        $userFirstName = $request->get('user_first_name');
        $userEmail     = $request->get('user_email');
        $userPassword  = $request->get('user_password');
        $userLimit      = $request->get('user_limit');
        $storageLimit = $request->get('storage_limit');


        if(!$this->service->companyNameIsFree($request->get('company_name'))){
            $this->respondWithError('Company Name Is Taken');
        }
        if(!$this->service->mainAccountIsFree($request->get('user_email'))){
            $this->respondWithError('This e-mail is already registered');
        }

        $result = $this->service->register($companyName,$userFirstName,$userEmail,$userPassword,$userLimit,$storageLimit);

        if(!empty($result)){
            $this->respondWithSuccess('Company Registered',[
                'company' => $result['company'],
                'user' => $result['user']
            ]);
        }

        $this->respondWithError('Whoops! Something went terrible wrong! Please contact Us');
    }


    /**
     * Update Plan (W)
     *
     * @param UpdateCompanyPlanRequest $request
     */
    public function updatePlan(UpdateCompanyPlanRequest $request){

        $this->validateSecret($request);

        $companyId = $request->get('company_id');
        $usersLimit = $request->get('users_limit');
        $storageLimit = $request->get('storage_limit');

        $result = $this->service->updateCompanyPlan($usersLimit,$storageLimit,$companyId);

        if(!empty($result)){
            $this->respondWithSuccess('Account Updated');
        }

        $this->respondWithError('Whoops! Something went terrible wrong! Please contact Us');

    }

    public function deactivateAccount(DeactivateCompanyRequest $request){

        $this->validateSecret($request);

        $companyId = $request->get('company_id');

        $result = $this->service->deactivateCompany($companyId);

        if(!empty($result)){
            $this->respondWithSuccess('Account Deactivated');
        }

        $this->respondWithError('Whoops! Something went terrible wrong! Please contact Us');
    }

    public function resumeAccount(ActivateCompanyRequest $request){

        $this->validateSecret($request);

        $companyId = $request->get('company_id');

        $result = $this->service->activateCompany($companyId);

        if(!empty($result)){
            $this->respondWithSuccess('Account Activated');
        }

        $this->respondWithError('Whoops! Something went terrible wrong! Please contact Us');
    }

}