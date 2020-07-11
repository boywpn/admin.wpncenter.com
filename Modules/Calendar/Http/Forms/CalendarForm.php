<?php

namespace Modules\Calendar\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Calendar\Entities\EventPriority;
use Modules\Calendar\Entities\EventStatus;
use Modules\Platform\Core\Helper\FormHelper;

class CalendarForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('calendar::calendar.form.name'),
        ]);
        $this->add('is_public', 'checkbox', [
            'label' => trans('calendar::calendar.form.is_public'),
        ]);

        $this->add('default_view', 'select', [
            'choices' => FormHelper::calendarDefaultView(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calendar::calendar.form.default_view'),
        ]);

        $this->add('first_day', 'select', [
            'choices' => FormHelper::calendarFirstDay(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calendar::calendar.form.first_day'),
        ]);

        $this->add('day_start_at', 'select', [
            'choices' => FormHelper::calendarDayStartsAt(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calendar::calendar.form.day_start_at'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
