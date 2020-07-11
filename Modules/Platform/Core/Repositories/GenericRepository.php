<?php

namespace Modules\Platform\Core\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Support\Collection;

/**
 * Generic repository with model setup
 * Class GenericRepository
 * @package Modules\Platform\Core\Repositories
 */
class GenericRepository extends PlatformRepository
{
    private $entityModel;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set New Model
     * @param $model
     */
    public function setupModel($model)
    {
        $this->entityModel = $model;

        $this->criteria = new Collection();
        $this->makeModel();
        $this->makePresenter();
        $this->makeValidator();
        $this->boot();
    }

    public function model()
    {
        return $this->entityModel;
    }
}
