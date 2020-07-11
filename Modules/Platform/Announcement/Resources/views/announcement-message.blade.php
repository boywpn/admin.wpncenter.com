<?php
use Modules\Platform\Core\Helper\SettingsHelper as SettingsHelper;
use Krucas\Settings\Facades\Settings as Settings;

?>

@if(Settings::get(SettingsHelper::S_ANNOUNCEMENT_MESSAGE) != '' && Settings::get(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS) != '' )

    <div class="modal fade" id="announcement-message" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content {{ Settings::get(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS)}}">
                <div class="modal-header">
                    <h4 class="modal-title" id="largeModalLabel">
                        @lang('core::core.ANNOUNCEMENT')
                    </h4>
                </div>
                <div class="modal-body">



                    {{Settings::get(SettingsHelper::S_ANNOUNCEMENT_MESSAGE) }}

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">@lang('core::core.CLOSE')</button>
                </div>
            </div>
        </div>
    </div>

@endif