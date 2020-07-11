<?php

namespace Modules\Api\Http\Controllers\Admin\V1\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Partners\Entities\Partners;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class AgentsController extends AdminController
{

    protected $entityClass = Agents::class;

    public function lists($request){

        $partner = $request['partner'];
        $per_page = $request['per_page'];

        if(empty($partner)){
            $agents = Agents::leftJoin('core_partners', 'core_agents.partner_id', '=', 'core_partners.id')
                ->where('core_partners.owner_id', Auth::user()->owner_id)
                ->select(
                    'core_agents.id',
                    'core_agents.name',
                    'core_agents.ref',
                    'core_agents.email',
                    'core_agents.phone',
                    'core_agents.notes',
                    'core_agents.status_id',
                    'core_agents.is_active',
                    'core_agents.created_at',
                    'core_agents.updated_at',
                    'core_agents.partner_id',
                    'core_agents.company_id'
                )
                ->orderBy('core_agents.id', 'asc')
                ->paginate($per_page);
        }else {
            $agents = Agents::leftJoin('core_partners', 'core_agents.partner_id', '=', 'core_partners.id')
                ->where('core_partners.owner_id', Auth::user()->owner_id)
                ->where('core_partners.id', $partner)
                ->select(
                    'core_agents.id',
                    'core_agents.name',
                    'core_agents.ref',
                    'core_agents.email',
                    'core_agents.phone',
                    'core_agents.notes',
                    'core_agents.status_id',
                    'core_agents.is_active',
                    'core_agents.created_at',
                    'core_agents.updated_at',
                    'core_agents.partner_id',
                    'core_agents.company_id'
                )
                ->orderBy('core_agents.id', 'asc')
                ->paginate($per_page);
        }

        return $this->success(0, $agents);

    }

    public function editAgent($request){

        $repository = $this->getRepository(Agents::class);

        $validator = Validator::make($request, [
            'agent' => 'required',
            'name' => 'required|string|max:20',
            'status_id' => 'required|numeric',
            'is_active' => 'required|numeric',
            'partner_id' => 'required|numeric',
        ]);

        if($validator->fails()){
            return $this->error(2001, $validator->errors());
        }

        $entity = Agents::where('ref', $request['agent'])->first();
        if(!$entity){
            return $this->error(2002);
        }

        $arrData = [
            'name' => $request['name'],
            'phone' => (!empty($request['phone'])) ? $request['phone'] : null,
            'email' => (!empty($request['email'])) ? $request['email'] : null,
            'notes' => (!empty($request['notes'])) ? $request['notes'] : null,
            'status_id' => $request['status_id'],
            'is_active' => $request['is_active'],
            'partner_id' => $request['partner_id'],
        ];

        $entity = $repository->updateEntity($arrData, $entity);

        return $this->success(0);

    }

}