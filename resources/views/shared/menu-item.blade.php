@if ($item['submenu'])

    <li>
        <a>
            @if($item['icon'])<i class="icon-menu fa {{ $item['icon'] }}"></i>@endif{{$item['name']}} <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li>
                        <a href="{{ route($submenu['url'].'.index') }}">
                            @if($submenu['icon'])<i class="icon-menu fa {{ $submenu['icon'] }}"></i>@endif
                            @choice('words.'.$submenu['name'], 2)
                        </a>
                    </li>
                @else
                    @include('shared.menu-item', [ 'item' => $submenu ])
                @endif
            @endforeach
        </ul>
    </li>

@endif