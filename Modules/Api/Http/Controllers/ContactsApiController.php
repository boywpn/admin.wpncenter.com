<?php

namespace Modules\Api\Http\Controllers;


use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Api\Http\Requests\ContactApiRequest;
use Modules\Contacts\Entities\Contact;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class ContactsApiController extends CrudApiController
{
    protected $entityClass = Contact::class;

    protected $moduleName = 'contacts';

    protected $languageFile = 'contacts::contacts';

    protected $with = [
        'contactStatus'
    ];

    protected $permissions = [
        'browse' => 'contacts.browse',
        'create' => 'contacts.create',
        'update' => 'contacts.update',
        'destroy' => 'contacts.destroy'
    ];

    protected $showRoute = 'contacts.contacts.show';

    protected $storeRequest = ContactApiRequest::class;

    protected $updateRequest = ContactApiRequest::class;

    public function __construct()
    {
        parent::__construct();
    }

}