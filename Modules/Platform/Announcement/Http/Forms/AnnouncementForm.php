<?php

namespace Modules\Platform\Announcement\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class AnnouncementForm
 * @package Modules\Platform\Announcement\Http\Forms
 */
class AnnouncementForm extends Form
{
    public function buildForm()
    {
        $this->add('display_class', 'select', [
            'choices' => [
                'modal-col-red'=>'RED',
                'modal-col-pink' =>'PINK',
                'modal-col-orange'=>'ORANGE',
                'modal-col-deep-orange'=>'DEEP ORANGE',
                'modal-col-deep-purple'=>'DEEP PURPLE',
            ],
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('announcement::announcement.display_class'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('message', 'textarea', [
            'label' => trans('announcement::announcement.message'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('announcement::announcement.update_settings'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
