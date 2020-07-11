<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Settings\Datatables\LanguageDatatable;
use Modules\Platform\Settings\Entities\Language;
use Modules\Platform\Settings\Http\Forms\LanguageForm;
use Modules\Platform\Settings\Http\Requests\LanguageSettingsRequest;
use Modules\Platform\Settings\Repositories\LanguageRepository;

class LanguageController extends ModuleCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $settingsMode = true;

    protected $disableTabs = true;

    protected $moduleName = 'settings';

    protected $permissions = [
        'browse' => 'settings.access',
        'create' => 'settings.access',
        'update' => 'settings.access',
        'destroy' => 'settings.access'
    ];

    protected $entityClass = Language::class;

    protected $datatable = LanguageDatatable::class;

    protected $formClass = LanguageForm::class;

    protected $storeRequest = LanguageSettingsRequest::class;

    protected $updateRequest = LanguageSettingsRequest::class;

    protected $repository = LanguageRepository::class;

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'language_key' => ['type' => 'text'],
            'is_active' => ['type' => 'boolean'],
        ]
    ];

    protected $languageFile = 'settings::language';

    protected $routes = [
        'index' => 'settings.language.index',
        'create' => 'settings.language.create',
        'show' => 'settings.language.show',
        'edit' => 'settings.language.edit',
        'store' => 'settings.language.store',
        'destroy' => 'settings.language.destroy',
        'update' => 'settings.language.update'
    ];
}
