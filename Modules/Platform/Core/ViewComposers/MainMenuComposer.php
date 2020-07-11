<?php

namespace Modules\Platform\Core\ViewComposers;

use Illuminate\View\View;
use Modules\Platform\MenuManager\Repositories\MenuRepository;

/**
 * Class MainMenuComposer
 * @package Modules\Platform\Core\ViewComposers
 */
class MainMenuComposer
{
    private $repo;

    public function __construct(MenuRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Compose Settings Menu
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('mainMenu', $this->repo->renderMainMenu());
    }
}
