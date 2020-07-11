@if(isset($passwordForm))
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">@lang('user::users.change_password')</h4>
                </div>
                <div class="modal-body">
                    {!! form($passwordForm) !!}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {!! JsValidator::formRequest(\Modules\Platform\User\Http\Requests\UserChangePasswordRequest::class, '#user_change_password') !!}
    @endpush
@endif