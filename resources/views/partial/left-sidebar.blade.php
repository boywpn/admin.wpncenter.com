<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->

    <div class="user-info" style="{{ \Modules\Platform\Core\Helper\SettingsHelper::siebarBackground() }}">
        <div class="image">
           {!!  \Modules\Platform\Core\Helper\UserHelper::profileImage() !!}
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}
            </div>
            <div class="email">
                {{ Auth::user()->email }}
            </div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{ route('account.index') }}"><i class="material-icons">person</i>@lang('core::core.menu.account')</a></li>
                    <li role="seperator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="material-icons">input</i>@lang('core::core.menu.sign_out')</a></li>
                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">

        <a href="javascript:void(0);" class="bars"></a>
        {!! $mainMenu->render() !!}

    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <i title="@lang('core::core.minify_sidebar')" id="minify-sidebar" class="material-icons">keyboard_arrow_left</i>
        <div class="version">
            <b>@lang('bap.version'): {{ config('bap.version') }}</b>
        </div>
    </div>
    <!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->