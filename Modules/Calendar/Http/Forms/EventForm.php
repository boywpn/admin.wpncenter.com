<?php

namespace Modules\Calendar\Http\Forms;

use Carbon\Carbon;
use Kris\LaravelFormBuilder\Form;
use Modules\Calendar\Entities\EventPriority;
use Modules\Calendar\Entities\EventStatus;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class EventForm
 * @package Modules\Calendar\Http\Forms
 */
class EventForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('calendar::events.form.name'),
        ]);

        $this->add('start_date', 'dateTimeType', [
            'label' => trans('calendar::events.form.start_date'),
            'default_value' => UserHelper::formatUserDateTime(Carbon::now())
        ]);

        $this->add('end_date', 'dateTimeType', [
            'label' => trans('calendar::events.form.end_date'),
            'default_value' => UserHelper::formatUserDateTime(Carbon::now()->addHour(2))

        ]);

        $this->add('sharedWith', 'choice', [
            'label' => trans('calendar::events.form.sharedWith'),
            'choices' => FormHelper::shareWithChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'selected' => $this->model != null && !is_array($this->model)  ? $this->model->sharedWith()->pluck('id')->toArray() : null ,
            'expanded' => false,
            'multiple' => true,


        ]);

        $this->add('full_day', 'checkbox', [
            'label' => trans('calendar::events.form.full_day'),
        ]);

        $this->add('event_color', 'simpleColorPicker', [
            'label' => trans('calendar::events.form.event_color'),
            'default_value' => '#9E9E9E'
        ]);

        $this->add('calendar_id', 'hidden');

        $this->add('event_priority_id', 'select', [
            'choices' => EventPriority::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calendar::events.form.event_priority_id'),
            'empty_value' => trans('core::core.empty_select'),
            'default_value' => EventPriority::NORMAL
        ]);


        $this->add('event_status_id', 'select', [
            'choices' => EventStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calendar::events.form.event_status_id'),
            'empty_value' => trans('core::core.empty_select'),
            'default_value' => EventStatus::NEW
        ]);

        $this->add('description', 'textarea', [
            'label' => trans('calendar::events.form.description'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
