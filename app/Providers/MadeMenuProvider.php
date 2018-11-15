<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modulo;
use App\Menu;


class MadeMenuProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function get_modules()
    {	
        $modules = Modulo::where('status',1)->whereIn('id',Menu::select('modulo_id')->get())->get();         
        return $modules;
    }

    public static function get_item_modules($modules)
    {
        $items = Menu::where('modulo_id',$modules)->where('url','<>','.')->where('status',1)->whereRaw('id = menu_id')->get();
        return $items;
    }

    public static function item_has_child($menu)
    {
        $verify_item = Menu::where('menu_id',$menu)->get();

        return count($verify_item);
    }

    public static function get_child_items($item)
    {
        $child = Menu::where('menu_id',$item)->where('status',1)->get();
        return $child;
    }
}
