<?php

namespace Modules\Contacts\Service;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Support\Facades\DB;
use Modules\Contacts\Entities\Contact;
use Modules\Contacts\Entities\ContactStatus;

/**
 * Class ContactService
 * @package Modules\Contacts\Service
 */
class ContactService
{

    /**
     * Count All Contact Where status is not null
     * @return int
     */
    public function countAllContacts(){
        return Contact::count();
    }

    /**
     * Group contacts by status
     * @return array
     */
    public function groupByStatus()
    {

        $contacts = DB::table('contacts')
            ->selectRaw('count(1) as total, contact_status_id')
            ->groupBy('contact_status_id');

        if (Landlord::hasTenant('company_id')) {
            $contacts->where('company_id', Landlord::getTenantId('company_id'));
        }

        $contacts = $contacts->get();
        $contactStatus = ContactStatus::all();

        $grouped = [];
        $result = [];

        foreach ($contacts as $contact){
            $grouped[$contact->contact_status_id] = $contact->total;
        }

        foreach ($contactStatus as $status){
            try {
                $result[$status->id] = isset($grouped[$status->id]) ? $grouped[$status->id] : 0;
            }catch (\Exception $e){
                $result[$status->id] = 0;
            }
        }

        return $result;

    }

    /**
     * Count contact by status
     * @param $status
     * @return mixed
     */
    public function countByStatus($status)
    {
        $contacts = Contact::where('contact_status_id', $status);

        if (Landlord::hasTenant('company_id')) {
            $contacts->where('company_id', Landlord::getTenantId('company_id'));
        }

        return $contacts->count();
    }
}
