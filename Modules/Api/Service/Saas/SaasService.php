<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 08.09.18
 * Time: 18:38
 */

namespace Modules\Api\Service\Saas;

use Illuminate\Support\Facades\Hash;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Companies\Repositories\CompanyRepository;
use Modules\Platform\User\Entities\User;
use Modules\Platform\User\Repositories\UserRepository;

class SaasService
{

    private $companyRepo;

    private $userRepo;

    public function __construct(CompanyRepository $companyRepository, UserRepository $userRepository)
    {
        $this->companyRepo = $companyRepository;
        $this->userRepo = $userRepository;
    }

    /**
     * Deactivate company
     *
     * @param $companyId
     * @return null
     */
    public function deactivateCompany($companyId)
    {

        $company = $this->companyRepo->findWithoutFail($companyId);

        if (!empty($company)) {

            $company->is_enabled = false;
            $company->save();

            $users = $this->userRepo->findWhere(['company_id' => $company->id
]);

            foreach ($users as $u) {
                $u->is_active = false;
                $u->save();
            }

            return $company;
        }


        return null;
    }

    /**
     * Deactivate company
     *
     * @param $companyId
     * @return null
     */
    public function activateCompany($companyId)
    {

        $company = $this->companyRepo->findWithoutFail($companyId);

        if (!empty($company)) {

            $company->is_enabled = true;
            $company->save();

            $users = $this->userRepo->findWhere(['company_id' => $company->id]);

            foreach ($users as $u) {
                $u->is_active = true;
                $u->save();
            }

            return $company;
        }


        return null;
    }

    /**
     * Register new company
     *
     * @param $companyName
     * @param $userFirstName
     * @param $userEmail
     * @param $userPassword
     * @param $userLimit
     * @return array|null
     */
    public function register($companyName, $userFirstName, $userEmail, $userPassword, $userLimit,$storageLimit)
    {


        $company = new Company();
        $company->name = $companyName;
        $company->is_enabled = true;

        if (!empty($userLimit)) {
            $company->user_limit = $userLimit;
        }
        if(!empty($storageLimit)){
            $company->storage_limit = $storageLimit;
        }

        $company->save();

        if ($company) {

            $user = new User();
            $user->first_name = $userFirstName;
            $user->email = $userEmail;
            $user->password = Hash::make($userPassword);
            $user->company()->associate($company);

            $user->save();

            $user->roles()->attach(config('api.saas.defaultRole'));

            return [
                'company' => $company,
                'user' => $user
            ];
        }

        return null;
    }


    /**
     * Update Company Plan
     *
     * @param $userLimit
     * @param $companyId
     * @return null
     */
    public function updateCompanyPlan($userLimit,$storageLimit,$companyId){

        $company = $this->companyRepo->findWithoutFail($companyId);

        if (!empty($company)) {

            if(!empty($userLimit)){
                $company->user_limit = $userLimit;
            }else{
                $company->user_limit = null;
            }

            if(!empty($storageLimit)){
                $company->storage_limit = $storageLimit;
            }else{
                $company->storage_limit = null;
            }

            $company->save();

            return $company;
        }


        return null;
    }

    public function validateSecret($secret)
    {

        $fromConfig = config('app.bap_saas_secret');

        if ($secret != $fromConfig) {
            return [
                'status' => 'error',
                'message' => 'Invalid secret'
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'All Good!'
            ];
        }

    }


    public function mainAccountIsFree($email)
    {

        $user = $this->userRepo->findWhere([
            'email' => $email
        ])->first();

        if (!empty($user)) {
            return false;
        }
        return true;
    }

    public function companyNameIsFree($companyName)
    {

        $company = $this->companyRepo->findWhere([
            'name' => strtolower($companyName)
        ])->first();

        if (!empty($company)) {
            return false;
        }
        return true;

    }

}