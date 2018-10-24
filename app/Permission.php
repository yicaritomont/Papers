<?php

namespace App;
use App\Permission;
use Illuminate\Support\Facades\Route;
class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {
        

       /* $routes = app('router')->getRoutes();
        return  $arrays=(array) $routes;*/
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

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
        ];
    }

    public static function storedPermissions()
    {
        $permissions = Permission::pluck('name', 'id');

        return $permissions;
    }
}
