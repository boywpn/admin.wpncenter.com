<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 23.11.18
 * Time: 11:12
 */

namespace Modules\ContactEmails\Service;


use Modules\ContactEmails\Entities\ContactEmail;
use Modules\Contacts\Entities\Contact;

class ContactEmailService
{

    /**
     * @param Contact $contact
     */
    public function manageContactEmail(Contact $contact)
    {

        $emailPrimary = $contact->contactEmails()->where('email', '=', $contact->email)->first();

        if (empty($emailPrimary)) {
            $emailPrimary = $contact->contactEmails()->create([
                'email' => $contact->email,
                'contact_id' => $contact->id,
                'company_id' => $contact->company_id,
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

        $contact->contactEmails()->where('id', '<>', $emailPrimary->id)
            ->where('is_default','=',true)
            ->update([
            'is_default' => false
        ]);

    }

    /**
     * @param ContactEmail $contactEmail
     */
    public function manageContactMultiEmail(ContactEmail $contactEmail)
    {

        // Fire event only if e-mail is default
        if (!empty($contactEmail->contact) && $contactEmail->is_default) { // if this is new default email

            $contactEmail->contact->contactEmails()->where('id', '<>', $contactEmail->id)->where('is_default','=',true)
                ->update([
                'is_default' => false
            ]);

            //Check if last primary email is in secondary email if not create
            $lastPrimaryEmail = $contactEmail->contact->email;

            $lastPrimaryInMultiEmail = $contactEmail->contact->contactEmails()->where('email', '=', $lastPrimaryEmail)->first();

            if (empty($lastPrimaryInMultiEmail)) {

                // Add last primary email to history
                $emailPrimary = $contactEmail->contact->contactEmails()->create([
                    'email' => $lastPrimaryEmail,
                    'contact_id' => $contactEmail->contact->id,
                    'company_id' => $contactEmail->contact->company_id,
                    'is_active' => true,
                    'is_default' => false
                ]);
            }

            $contactEmail->contact()->update([
                'email' => $contactEmail->email
            ]);


        }

    }

}
