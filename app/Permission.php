<?php

namespace App;
use App\Permission;
use Illuminate\Support\Facades\Route;
class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {

        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'view_permissions',
            'add_permissions',
            'delete_permissions',

            'view_modulos',
            'add_modulos',
            'edit_modulos',
            'delete_modulos',

            'view_menus',
            'add_menus',
            'edit_menus',
            'delete_menus',

            'view_posts',
            'add_posts',
            'edit_posts',
            'delete_posts',

            'view_inspectors',
            'add_inspectors',
            'edit_inspectors',
            'delete_inspectors',

            'view_inspectortypes',
            'add_inspectortypes',
            'edit_inspectortypes',
            'delete_inspectortypes',

            'view_professions',
            'add_professions',
            'edit_professions',
            'delete_professions',

            'view_inspectiontypes',
            'add_inspectiontypes',
            'edit_inspectiontypes',
            'delete_inspectiontypes',

            'view_inspectionsubtypes',
            'add_inspectionsubtypes',
            'edit_inspectionsubtypes',
            'delete_inspectionsubtypes',

            'view_companies',
            'add_companies',
            'edit_companies',
            'delete_companies',

            'view_clients',
            'add_clients',
            'edit_clients',
            'delete_clients',

            'view_headquarters',
            'add_headquarters',
            'edit_headquarters',
            'delete_headquarters',

            'view_inspectionappointments',
            'add_inspectionappointments',
            'edit_inspectionappointments',
            'delete_inspectionappointments',

            'view_inspectoragendas',
            'add_inspectoragendas',
            'edit_inspectoragendas',
            'delete_inspectoragendas',

            'view_preformatos',
            'add_preformatos',
            'edit_preformatos',
            'delete_preformatos',

            'view_formats',
            'add_formats',
            'edit_formats',
            'delete_formats',

            'view_contracts',
            'add_contracts',
            'edit_contracts',
            'delete_contracts',
        ];
    }

    public static function storedPermissions()
    {
        $permissions = Permission::pluck('name');

        return $permissions;
    }
}
