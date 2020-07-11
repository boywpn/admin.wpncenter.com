<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active"><a href="#settings"
                                                  data-toggle="tab">@lang('core::core.right_menu.settings')</a></li>
        <li role="presentation"><a href="#skins" data-toggle="tab">@lang('core::core.right_menu.skins')</a></li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade " id="skins">


            <ul class="demo-choose-skin">

                @foreach(\Modules\Platform\Core\Helper\ThemeHelper::SUPPORTED_THEMES as $key => $name)
                    <li data-theme="{{ $key }}" class="{{ \Modules\Platform\Core\Helper\ThemeHelper::isActive($key) }}">
                        <div class="{{ strtolower($name)  }}"></div>
                        <span>{{ trans('core::core.right_menu.theme.'.$name) }}</span>
                    </li>
                @endforeach

            </ul>
        </div>
        <div role="tabpanel" class="tab-pane fade in active" id="settings">
            <div class="right-menu-settings">

                <ul class="setting-list">
                    @if(Session::has('original_user'))
                        <li>
                            <a href="{{ route('account.ghost-logout') }}">
                                <i class="material-icons">people_outline</i>
                                <span>@lang('core::core.right_menu.ghost_sign_out')</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('account.index') }}">
                            <i class="material-icons">person</i>
                            <span>@lang('core::core.menu.account')</span>
                        </a>
                    </li>
                    @can('settings.access')
                        <li>
                            <a href="{{ url('/settings') }}">
                                <i class="material-icons">settings</i>
                                <span>@lang('core::core.menu.settings')</span>
                            </a>
                        </li>
                    @endcan


                    <li>
                        <a href="{{ route('notifications.index') }}">
                            <i class="material-icons">notifications</i>
                            <span>@lang('core::core.menu.notifications')</span>
                        </a>
                    </li>


                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="material-icons">input</i>
                            <span>@lang('core::core.menu.sign_out')</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</aside>
<!-- #END# Right Sidebar -->