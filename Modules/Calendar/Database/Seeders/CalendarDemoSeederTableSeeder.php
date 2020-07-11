<?php

namespace Modules\Calendar\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Calendar\Entities\Calendar;
use Modules\Calendar\Entities\Event;
use Modules\Platform\Core\Helper\SeederHelper;

class CalendarDemoSeederTableSeeder extends SeederHelper
{
    private function generatePrivateEvents()
    {
        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->company;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(8, 10), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(11, 14), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 1;
            $event->company_id = $this->firstCompany();
            $event->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->company;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(8, 10), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(11, 16), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 3;
            $event->company_id = $this->secondCompany();
            $event->save();
        }
    }

    private function generatePublicEvents()
    {
        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->name;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(8, 10), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(11, 14), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 2;
            $event->created_by = \Auth::user()->id;
            $event->company_id = $this->firstCompany();
            $event->save();
        }

        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->name;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(14, 16), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(17, 18), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 2;
            $event->created_by = \Auth::user()->id;
            $event->company_id = $this->firstCompany();
            $event->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->name;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(8, 10), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(11, 14), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 4;
            $event->created_by = \Auth::user()->id;
            $event->company_id = $this->secondCompany();
            $event->save();
        }

        for ($i = 1; $i <= 4;$i++) {
            $faker = Factory::create();

            $event = new Event();
            $event->name = 'Meeting #'.$faker->name;
            $event->changeOwnerTo(\Auth::user());
            $event->start_date = Carbon::now()->subDay($i)->setTime(rand(14, 16), 0, 0);
            $event->end_date = Carbon::now()->subDay($i)->setTime(rand(17, 18), 0, 0);
            $event->event_priority_id = rand(1, 4);
            $event->event_status_id = rand(1, 4);
            $event->event_color = $faker->hexColor;
            $event->full_day = false;
            $event->calendar_id = 4;
            $event->created_by = \Auth::user()->id;
            $event->company_id = $this->secondCompany();
            $event->save();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Calendar::truncate();
        Event::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        $myCalendar = new Calendar();
        $myCalendar->changeOwnerTo(\Auth::user());
        $myCalendar->id = 1;
        $myCalendar->name = 'My Calendar';
        $myCalendar->is_public = 0;
        $myCalendar->default_view = 'agendaWeek';
        $myCalendar->first_day = 1;
        $myCalendar->day_start_at = '07:00:00';
        $myCalendar->created_by = \Auth::user()->id;
        $myCalendar->company_id = $this->firstCompany();
        $myCalendar->save();

        $publicCalendar = new Calendar();
        $publicCalendar->id = 2;

        $publicCalendar->name = 'Meeting Room #1';
        $publicCalendar->is_public = 1;
        $publicCalendar->default_view = 'agendaWeek';
        $publicCalendar->first_day = 1;
        $publicCalendar->day_start_at = '07:00:00';
        $publicCalendar->created_by = \Auth::user()->id;
        $publicCalendar->company_id = $this->firstCompany();
        $publicCalendar->save();

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        $myCalendarSecond = new Calendar();
        $myCalendarSecond->changeOwnerTo(\Auth::user());
        $myCalendarSecond->id = 3;
        $myCalendarSecond->name = 'My Calendar';
        $myCalendarSecond->is_public = 0;
        $myCalendarSecond->default_view = 'agendaWeek';
        $myCalendarSecond->first_day = 1;
        $myCalendarSecond->day_start_at = '07:00:00';
        $myCalendarSecond->created_by = \Auth::user()->id;
        $myCalendarSecond->company_id = $this->secondCompany();
        $myCalendarSecond->save();

        $publicCalendarSecond = new Calendar();
        $publicCalendarSecond->id = 4;
        $publicCalendarSecond->name = 'Meeting Room #2';
        $publicCalendarSecond->is_public = 1;
        $publicCalendarSecond->default_view = 'agendaWeek';
        $publicCalendarSecond->first_day = 1;
        $publicCalendarSecond->day_start_at = '07:00:00';
        $publicCalendarSecond->created_by = \Auth::user()->id;
        $publicCalendarSecond->company_id = $this->secondCompany();
        $publicCalendarSecond->save();

        $this->generatePrivateEvents();
        $this->generatePublicEvents();
    }
}
