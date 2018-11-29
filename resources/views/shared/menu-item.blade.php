@if ($item['submenu'] == [])
    {{-- <li>
        <a href="{{ url($item['name']) }}">{{ $item['name'] }} </a>
    </li> --}}
    {{-- {{ dd($item) }} --}}
    @if($item['url'])
        <li>
            <a href="{{ route($item['url'].'.index') }}"><span></span>
                @if($submenu['icon'])<i class="icon-menu fa {{ $item['icon'] }}"></i>@endif
                @choice('words.'.$item['name'], 2)
            </a>
        </li>
    @endif
    
@else

    {{-- {{ dd($item) }} --}}

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

    {{-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['name'] }} <span class="caret"></span></a>
        <ul class="dropdown-menu sub-menu">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li><a href="{{ url('menu',['id' => $submenu['id'], 'slug' => $submenu['slug']]) }}">{{ $submenu['name'] }} </a></li>
                @else
                    @include('partials.menu-item', [ 'item' => $submenu ])
                @endif
            @endforeach
        </ul>
    </li> --}}
@endif