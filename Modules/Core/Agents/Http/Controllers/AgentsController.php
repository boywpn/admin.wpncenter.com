<?php

namespace Modules\Core\Agents\Http\Controllers;

use App\Models\Old\Domains;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Modules\Core\Agents\Datatables\AgentsDatatable;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Agents\Entities\AgentsShare;
use Modules\Core\Agents\Entities\AgentsShareVar;
use Modules\Core\Agents\Http\Forms\AgentsForm;
use Modules\Core\Agents\Http\Requests\AgentsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class AgentsController extends ModuleCrudController
{
    protected $datatable = AgentsDatatable::class;
    protected $formClass = AgentsForm::class;
    protected $storeRequest = AgentsRequest::class;
    protected $updateRequest = AgentsRequest::class;
    protected $entityClass = Agents::class;

    protected $moduleName = 'CoreAgents';

    protected $permissions = [
        'browse' => 'core.agents.browse',
        'create' => 'core.agents.create',
        'update' => 'core.agents.update',
        'destroy' => 'core.agents.destroy'
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'core.agents.status.index', 'label' => 'settings.status']
    ];

    protected $settingsPermission = 'core.agents.settings';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'ref' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'phone' => ['type' => 'text', 'col-class' => 'col-lg-3'],
            'email' => ['type' => 'text', 'col-class' => 'col-lg-3'],

            'parent_id' => ['type' => 'manyToOne', 'relation' => 'agentsParent', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'partner_id' => ['type' => 'manyToOne', 'relation' => 'agentsPartner', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'status_id' => ['type' => 'manyToOne', 'relation' => 'agentsStatus', 'column' => 'name', 'col-class' => 'col-lg-3'],
            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-3'],

            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'core/agents::agents';

    protected $routes = [
        'index' => 'core.agents.index',
        'create' => 'core.agents.create',
        'show' => 'core.agents.show',
        'edit' => 'core.agents.edit',
        'store' => 'core.agents.store',
        'destroy' => 'core.agents.destroy',
        'update' => 'core.agents.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    protected $relationTabs = [
        'shareconfig' => [
            'icon' => 'donut_small',
            'permissions' => [
                'browse' => 'core.agents.shareconfig'
            ],
            'custom' => [
                'view' => 'core/agents::shareConfig',
                'database' => Agents::class
            ]
        ],
    ];

    public function afterStore($request, &$entity)
    {

        // Create Token
        $str = $entity->id;
        \cryptor::setKey(config('app.salt_api'), config('app.key_api'));
        $token = \cryptor::encrypt($str);

        $repository = $this->getRepository();

        $repository->updateEntity(['token' => $token], $entity);

    }

    public function afterUpdate($request, &$entity)
    {
        // Update Token
        $str = $entity->id;
        \cryptor::setKey(config('app.salt_api'), config('app.key_api'));
        $token = \cryptor::encrypt($str);

        $repository = $this->getRepository();

        $repository->updateEntity(['token' => $token], $entity);
    }

    public function agentToken($id){

        $entity = Agents::findOrFail($id);

        return $entity->token;

    }

    /**
     * For Old System
    */
    public function copyOld($partner){

        $domains = Domains::where('new_id', $partner)
            ->with(['domainAgent'], function($query){
                $query->select('*');
            })
            ->get()->toArray();

        $repository = $this->getRepository();

        $data = [];
        foreach ($domains as $domain){

            foreach ($domain['domain_agent'] as $agent){

                $exist_agent = Agents::where('old_id', $agent['id'])->first();
                if($exist_agent){
                    continue;
                }

                $arrData = array(
                    'old_id' => $agent['id'],
                    'partner_id' => $partner,
                    'name' => (!empty($agent['agent_name'])) ? ucfirst($agent['agent_name']) : ucfirst($agent['name']),
                    'ref' => $agent['name'],
                    'phone' => $agent['phone'],
                    'status_id' => ($agent['id'] == $agent['reference']) ? '2' : '1'
                );

                $repository->createEntity($arrData, \App::make(Agents::class));

                $data[] = $arrData;

            }

        }

        return $data;

    }

    public function saveShareConfig ($member_id, Request $request){

        $data = $request->all();

        $repository = $this->getRepository();

        $input = [];
        $input['agent_id'] = $member_id;

        $entity = $repository->createEntity($input, \App::make(AgentsShare::class));

        $var = [];
        $var['share_id'] = $entity->id;
        foreach ($data['type_id'] as $game => $type){

            $var['game_id'] = $game;
            $var['values'] = json_encode($type, JSON_FORCE_OBJECT);

            $repository->createEntity($var, \App::make(AgentsShareVar::class));

        }

        flash(trans('core::core.entity.updated'))->success();
        return redirect()->route($this->routes['show'], $member_id);

    }
}
