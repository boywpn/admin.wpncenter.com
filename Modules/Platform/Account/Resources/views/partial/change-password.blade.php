{!! form($passwordForm) !!}

@push('scripts')
{!! JsValidator::formRequest(\Modules\Platform\Account\Http\Requests\AccountChangePasswordRequest::class, '#account_password_form') !!}
@endpush