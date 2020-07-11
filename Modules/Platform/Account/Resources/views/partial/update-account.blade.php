{!! form_start($accountForm) !!}

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('account::account.panel.name')</h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->first_name) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->last_name) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('account::account.panel.address')</h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->address_country) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->address_state) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->address_city) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->address_postal_code) !!}
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->address_street) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('account::account.panel.more')</h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->title) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->department) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->office_phone) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->mobile_phone) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->home_phone) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->signature) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->fax) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->secondary_email) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('account::account.panel.settings')</h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->theme) !!}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->language_id) !!}
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->date_format_id) !!}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->time_format_id) !!}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->time_zone) !!}
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('account::account.panel.profile')</h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left">
        {!! form_row($accountForm->profile_pic_conf) !!}
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        {!! form_row($accountForm->profile_picture) !!}
    </div>
</div>

{!! form_end($accountForm, $renderRest = true) !!}

@push('scripts')
    <script src="{!! Module::asset('account:js/Account.js') !!}"></script>
@endpush

@push('scripts')
{!! JsValidator::formRequest(\Modules\Platform\Account\Http\Requests\AccountUpdateRequest::class, '#account_update_form') !!}
@endpush
