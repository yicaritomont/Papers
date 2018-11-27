<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
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

    public static function getChildren($data, $line)
    {
        $children = [];
        foreach ($data as $line1)
        {

            
            if ($line['id'] == $line1['parent'])
            {
                $childrenTemp = self::getChildren($data, $line1);
                
                if($line1['url'])
                {
                    if(app(Gate::class)->check('view_'.$line1['url']))
                    {
                        $children = array_merge($children, [ array_merge($line1, ['submenu' => $childrenTemp ]) ]);
                    }
                }
                elseif(count($childrenTemp) > 0)
                {
                    $children = array_merge($children, [ array_merge($line1, ['submenu' => $childrenTemp ]) ]);
                }
                
            }
        }
        return $children;
    }

    public static function optionsMenu()
    {
        $menu = Menu::where('status', 1)
            // ->where('parent', 0)
            ->orderby('parent')
            ->orderby('order')
            ->orderby('name')
            ->get()
            ->toArray();

        // dd($menu);
        return $menu;
    }

    public static function prueba()
    {
        return 'XD';
    }

    public static function menus()
    {
        // dd(self::prueba());
        // $menus = self;
        $data = self::optionsMenu();
        $menuAll = [];
        foreach ($data as $line)
        {
            $item = [ array_merge($line, ['submenu' => self::getChildren($data, $line) ]) ];
            $menuAll = array_merge($menuAll, $item);
            // dd(self::getChildren($data, $line));
            //dd(count(self::getChildren($data, $line)));
            // var_dump(self::getChildren($data, $line));
        }
        //dd($menuAll);
        return $menuAll;
    }
}
