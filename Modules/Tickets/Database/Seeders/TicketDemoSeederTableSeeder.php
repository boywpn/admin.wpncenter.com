<?php

namespace Modules\Tickets\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Tickets\Entities\Ticket;

class TicketDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Ticket::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        for ($i = 1; $i <= 50; $i++) {
            $faker = Factory::create();

            $ticket = new Ticket();
            $ticket->id = $i;
            $ticket->changeOwnerTo(\Auth::user());

            $ticket->name = 'Ticket #' . rand(1000, 56780);

            $ticket->due_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');

            $ticket->description = $faker->sentence();
            $ticket->resolution = $faker->sentence();

            $ticket->ticket_priority_id = rand(1, 4);
            $ticket->ticket_status_id = rand(1, 4);
            $ticket->ticket_severity_id = rand(1, 4);
            $ticket->ticket_category_id = rand(1, 3);

            $ticket->account_id = rand(1, 20);
            $ticket->company_id = $this->firstCompany();

            $ticket->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        for ($i = 51; $i <= 100; $i++) {
            $faker = Factory::create();

            $ticket = new Ticket();
            $ticket->id = $i;
            $ticket->changeOwnerTo(\Auth::user());

            $ticket->name = 'Ticket #' . rand(1000, 56780);

            $ticket->due_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');

            $ticket->description = $faker->sentence();
            $ticket->resolution = $faker->sentence();

            $ticket->ticket_priority_id = rand(1, 4);
            $ticket->ticket_status_id = rand(1, 4);
            $ticket->ticket_severity_id = rand(1, 4);
            $ticket->ticket_category_id = rand(1, 3);

            $ticket->account_id = rand(21, 40);
            $ticket->company_id = $this->secondCompany();

            $ticket->save();
        }
    }
}
