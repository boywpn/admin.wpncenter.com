<?php

namespace Modules\Core\Banks\Http\Forms;

use App\Models\Banks;
use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class BanksForm extends Form
{
    public function buildForm()
    {
        $this->add('username', 'text', [
            'label' => trans('core/banks::banks.form.username'),
        ]);
        $this->add('password', 'password', [
            'label' => trans('core/banks::banks.form.password'),
            'default_value' => null
        ]);
        $this->add('account', 'text', [
            'label' => trans('core/banks::banks.form.account'),
        ]);
        $this->add('number', 'text', [
            'label' => trans('core/banks::banks.form.number'),
        ]);
        $this->add('phone', 'text', [
            'label' => trans('core/banks::banks.form.phone'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('core/banks::banks.form.notes'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/banks::banks.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('bank_id', 'select', [
            'choices' => Banks::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/banks::banks.form.bank_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

        $method = FormHelper::getActionMethod();
        if($method == 'create'){
            foreach (\Modules\Core\Banks\Entities\Banks::FORM_REMOVE_CREATE as $remove) {
                $this->remove($remove);
            }
        }elseif($method == 'edit'){
            foreach (\Modules\Core\Banks\Entities\Banks::FORM_REMOVE_EDIT as $remove) {
                $this->remove($remove);
            }
        }
    }
}
