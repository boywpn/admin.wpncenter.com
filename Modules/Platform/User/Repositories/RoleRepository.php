<?php

namespace Modules\Platform\User\Repositories;

use Modules\Platform\Core\Helper\FileHelper;
use Modules\Platform\Core\Repositories\PlatformRepository;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Functions;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class RoleRepository
 * @package Modules\Platform\User\Repositories
 */
class RoleRepository extends PlatformRepository
{

    /**
     * @var array
     */
    private $permissionCreated = [

    ];

    /**
     * @var array
     */
    private $permissionDeleted = [

    ];

    public function model()
    {
        return Role::class;
    }

    /**
     * Return Grouped Permissions by perm_group
     * @return array
     */
    public function getGroupedAllPermissions()
    {
        $grouped =  Arrays::groupBy($this->getAllPermissionsAsArray(), Functions::extractField('perm_group'));

        ksort($grouped);

        return $grouped;
    }

    /**
     * Return All Permissions as Array
     * @return array
     */
    public function getAllPermissionsAsArray()
    {
        return Permission::all()->toArray();
    }

    /**
     * @param $deleteUnused - Delete Unused permissions
     * @return array
     */
    public function synchModulePermissions($deleteUnused = false)
    {
        if ($deleteUnused) {
            $this->deletePermissions();
        }

        $modules = \Module::toCollection();

        foreach ($modules as $module) {
            $config = include $module->getPath().'/Config/config.php';

            if (isset($config['permissions'])) {
                $permissions = $config['permissions'];

                if ($permissions != null) {
                    foreach ($permissions as $permissionName) {
                        try {
                            $permission = Permission::findByName($permissionName);
                        } catch (PermissionDoesNotExist $e) {
                            // Permission not exist create one

                            $permission = Permission::create([
                                'name' => $permissionName,
                                'guard_name' => 'web',
                                'perm_group' => $module->name
                            ]);

                            $this->permissionCreated[] = $permission;
                        }
                    }
                }
            }
        }

        return [
            'created' => $this->permissionCreated,
            'deleted' => $this->permissionDeleted
        ];
    }

    /**
     * Check permissions in database and delete unused
     */
    private function deletePermissions()
    {
        $databasePermissions = Permission::all()->pluck('name');

        $modules = \Module::toCollection();

        $permissionCollection = [];

//        foreach ($modules as $module) {
//            $permissions = config($module->getLowerName() . '.permissions');
//            if ($permissions != null) {
//                foreach ($permissions as $permissionName) {
//                    $permissionCollection[] = $permissionName;
//                }
//            }
//        }

        foreach ($modules as $module) {
            $config = include $module->getPath().'/Config/config.php';

            if (isset($config['permissions'])) {
                $permissions = $config['permissions'];

                if ($permissions != null) {
                    foreach ($permissions as $permissionName) {
                        $permissionCollection[] = $permissionName;
                    }
                }
            }
        }

        $dif = array_diff($databasePermissions->toArray(), $permissionCollection);

        foreach ($dif as $perm) {
            $perm = Permission::findByName($perm);
            $this->permissionDeleted[] = $perm;
            $perm->delete();
        }
    }
}
