<?php

namespace Modules\Api\Http\Controllers;


use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Api\Http\Requests\LeadApiRequest;
use Modules\Leads\Entities\Lead;

/**
 * Leads API Controller
 *
 * Class LeadsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class LeadsApiController extends CrudApiController
{

    // Entity Class
    protected $entityClass = Lead::class;

    // Module name
    protected $moduleName = 'leads';

    // Language pattern
    protected $languageFile = 'leads::leads';

    // What relations to get with single entity
    protected $with = [
      'leadStatus'
    ];

    // Permissions (same as in Web Crud Controller)
    protected $permissions = [
        'browse' => 'leads.browse',
        'create' => 'leads.create',
        'update' => 'leads.update',
        'destroy' => 'leads.destroy'
    ];

    // Needed for notifications
    protected $showRoute = 'leads.leads.show';

    protected $storeRequest = LeadApiRequest::class;

    protected $updateRequest = LeadApiRequest::class;

    public function __construct()
    {
        parent::__construct();
    }

}