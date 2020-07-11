<?php

namespace Modules\Platform\Settings\ViewComposers;

use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;
use Spatie\Menu\Html;
use Spatie\Menu\Link;
use Spatie\Menu\Menu;

/**
 * Class SettingsMenuComposer
 * @package Modules\Platform\Settings\ViewComposers
 */
class SettingsMenuComposer
{


    /**
     * Compose Settings Menu
     * @param View $view
     */
    public function compose(View $view)
    {
        $user = \Auth::user();

        $settingsMenu = Menu::new();
        $settingsMenu->addClass('list-group list-menu card');
        $settingsMenu->setActiveFromUrl(url()->current());

        // General Settings
        $settingsMenu->add(Html::raw('<h5>'.Lang::get('settings::settings.menu.general').'</h5>')->addParentClass('header'));

        $settingsMenu->add(Link::to(route('settings.display'), Lang::get('settings::settings.menu.display')));

        if($user->hasPermissionTo('settings.access')) {
            $settingsMenu->add(Link::to(route('settings.language.index'), trans('settings::settings.menu.language')));
            $settingsMenu->add(Link::to(route('settings.timezone.index'), trans('settings::settings.menu.timezone')));
            $settingsMenu->add(Link::to(route('settings.dateformat.index'), trans('settings::settings.menu.dateformat')));
            $settingsMenu->add(Link::to(route('settings.timeformat.index'), trans('settings::settings.menu.timeformat')));
            $settingsMenu->add(Link::to(route('settings.currency.index'), trans('settings::settings.menu.currency')));
            $settingsMenu->add(Link::to(route('settings.tax.index'), trans('settings::settings.menu.tax')));
            $settingsMenu->add(Link::to(route('settings.menu_manager.index'), trans('settings::settings.menu.menu_manager')));
        }

        $settingsMenu->add(Html::raw('<h6>'.trans('settings::settings.menu.user_and_access').'</h6>')->addParentClass('header'));

        if($user->hasPermissionTo('settings.access')) {
            $settingsMenu->add(Link::to(route('settings.companies.index'), trans('settings::settings.menu.companies')));
            $settingsMenu->add(Link::to(route('settings.roles.index'), trans('settings::settings.menu.roles')));
        }

        $settingsMenu->add(Link::to(route('settings.users.index'), trans('settings::settings.menu.users')));
        $settingsMenu->add(Link::to(route('settings.groups.index'), trans('settings::settings.menu.groups')));

        $settingsMenu->add(Html::raw('<h5>'.trans('settings::settings.menu.others').'</h5>')->addParentClass('header'));

        $settingsMenu->add(Link::to(route('settings.company_settings'), trans('settings::settings.menu.company')));
        if($user->hasPermissionTo('settings.access')) {
            $settingsMenu->add(Link::to(route('settings.announcement'), trans('settings::settings.menu.announcement')));
            $settingsMenu->add(Link::to(route('settings.outgoing_server'), trans('settings::settings.menu.outgoing_server')));
        }
        $settingsMenu->add(Link::to(route('settings.clear_cache'), trans('settings::settings.menu.clear_cache')));
        $settingsMenu->add(Link::to(route('settings.optimize'), trans('settings::settings.menu.optimize')));


        $settingsMenu->each(function (Link $link) {
            $link->addClass('list-group-item');
        });



        $view->with('settingsMenu', $settingsMenu);
    }
}
