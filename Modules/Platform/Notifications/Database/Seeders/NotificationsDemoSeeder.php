<?php

namespace Modules\Platform\Notifications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\User\Entities\User;

class NotificationsDemoSeeder extends Seeder
{

    private function  generateForUser($userId,$campaignId){
        Model::unguard();

        $user = User::find($userId);

        $user->notifications()->delete();

        $placeholder = new NotificationPlaceholder();

        $placeholder->setRecipient($user);
        $placeholder->setAuthorUser(\Auth::user());
        $placeholder->setAuthor($user->name);
        $placeholder->setColor('bg-green');
        $placeholder->setIcon('assignment');
        $placeholder->setContent(trans('notifications::notifications.new_record', ['user' => $user->name]));

        $placeholder->setUrl(route('campaigns.campaigns.show', $campaignId));

        $user->notify(new GenericNotification($placeholder));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->generateForUser(1,1);
        $this->generateForUser(2,rand(1,20));
        $this->generateForUser(3,rand(1,20));
        $this->generateForUser(4,rand(21,40));
        $this->generateForUser(5,rand(21,40));
    }

}