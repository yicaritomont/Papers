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
        ];
    }

    public static function storedPermissions()
    {
        $permissions = Permission::pluck('name', 'id');

        return $permissions;
    }
}
