<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 23.11.18
 * Time: 11:12
 */

namespace Modules\LeadEmails\Service;

use Modules\LeadEmails\Entities\LeadEmail;
use Modules\Leads\Entities\Lead;

class LeadEmailService
{

    /**
     * @param Lead $lead
     */
    public function manageLeadEmail(Lead $lead)
    {

        $emailPrimary = $lead->leadEmails()->where('email', '=', $lead->email)->first();

        if (empty($emailPrimary)) {
            $emailPrimary = $lead->leadEmails()->create([
                'email' => $lead->email,
                'lead_id' => $lead->id,
                'company_id' => $lead->company_id,
                'is_active' => true,
                'is_default' => true
            ]);
        } else {

            $update = false;

            if (!$emailPrimary->is_default) {
                $emailPrimary->is_default = true;
                $update = true;
            }
            if (!$emailPrimary->is_active) {
                $emailPrimary->is_active = true;
                $update = true;
            }
            if ($update) {
                $emailPrimary->save();
            }
        }

        $lead->leadEmails()->where('id', '<>', $emailPrimary->id)
            ->where('is_default','=',true)
            ->update([
                'is_default' => false
            ]);

    }

    /**
     * @param LeadEmail $leadEmail
     */
    public function manageLeadMultiEmail(LeadEmail $leadEmail)
    {

        // Fire event only if e-mail is default
        if (!empty($leadEmail->lead) && $leadEmail->is_default) { // if this is new default email

            $leadEmail->lead->leadEmails()->where('id', '<>', $leadEmail->id)->where('is_default','=',true)
                ->update([
                    'is_default' => false
                ]);

            //Check if last primary email is in secondary email if not create
            $lastPrimaryEmail = $leadEmail->lead->email;

            $lastPrimaryInMultiEmail = $leadEmail->lead->leadEmails()->where('email', '=', $lastPrimaryEmail)->first();

            if (empty($lastPrimaryInMultiEmail)) {

                // Add last primary email to history
                $emailPrimary = $leadEmail->lead->leadEmails()->create([
                    'email' => $lastPrimaryEmail,
                    'lead_id' => $leadEmail->lead->id,
                    'company_id' => $leadEmail->lead->company_id,
                    'is_active' => true,
                    'is_default' => false
                ]);
            }

            $leadEmail->lead()->update([
                'email' => $leadEmail->email
            ]);


        }

    }

}
