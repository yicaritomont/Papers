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
        ];
    }

    public static function storedPermissions()
    {
        $permissions = Permission::pluck('name');

        return $permissions;
    }
}
