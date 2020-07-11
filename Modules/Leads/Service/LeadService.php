<?php

namespace Modules\Leads\Service;

use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\ContactEmails\Entities\ContactEmail;
use Modules\Contacts\Entities\Contact;
use Modules\Leads\Entities\Lead;

/**
 * Class LeadService
 * @package Modules\Leads\Service
 */
class LeadService
{

    /**
     * Create Contact from Lead
     *
     * @param $leadId
     * @return Contact
     */
    public function convertToContact($leadId){

        $lead = Lead::findOrFail($leadId);

        $contact = new Contact();

        $contact->fill($lead->toArray());

        $contact->street = $lead->addr_street;
        $contact->state = $lead->addr_state;
        $contact->country = $lead->addr_country;
        $contact->city = $lead->addr_city;
        $contact->zip_code = $lead->addr_zip;

        if($lead->owner != null ) {
            $contact->changeOwnerTo($lead->owner);
        }
        $contact->save();

        foreach ($lead->leadEmails as $leadEmail){
            $contactEmail = new ContactEmail();
            $contactEmail->email = $leadEmail->email;
            $contactEmail->is_default = $leadEmail->is_default;
            $contactEmail->is_active = $leadEmail->is_active;
            $contactEmail->is_marketing = $leadEmail->is_marketing;
            $contactEmail->notes = $leadEmail->notes;
            $contactEmail->contact()->associate($contact);
            $contactEmail->save();
        }


        return $contact;

    }


    /**
     * Count lead by status
     *
     * @param $status
     * @return mixed
     */
    public function countByStatus($status)
    {
        $leads = Lead::where('lead_status_id', $status);

        if (Landlord::hasTenant('company_id')) {
            $leads->where('company_id', Landlord::getTenantId('company_id'));
        }

        return $leads->count();
    }

    /**
     * @return mixed
     */
    public function groupByStatus()
    {
        $result = \DB::table('leads_dict_status')
            ->select('leads_dict_status.name', \DB::raw('count(leads.id) as counter'))
            ->leftJoin('leads', 'leads.lead_status_id', '=', 'leads_dict_status.id')
            ->groupBy('leads_dict_status.name')
            ->where(function($q){
                $q->whereMonth('leads.created_at', '=', Carbon::today()->month);
                $q->orWhere('leads.created_at','=',null);
            });

        if (Landlord::hasTenant('company_id')) {
            $result->where('leads.company_id', Landlord::getTenantId('company_id'));
        }

        $result = $result->orderBy('counter','asc')->get()->toArray();


        return $result;
    }
}
