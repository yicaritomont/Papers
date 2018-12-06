<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="images/favicon.ico" type="image/ico" />

        <title>@yield('title') {{ config('app.name') }}</title>

        <!--  -->
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('css/all.css') }}">

        <!-- FullCalendar -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/fullCalendar/fullcalendar.min.css')}}">
        <!-- Bootstrap -->
        <link href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
        <!-- NProgress -->
        <link href="{{asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
        <!-- iCheck -->
        <link href="{{asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">

        <!-- bootstrap-progressbar -->
        <link href="{{asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
        <!-- JQVMap -->
        <link href="{{asset('vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
        <!-- bootstrap-daterangepicker -->
        <link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">


        <!-- DatePicker -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/datePicker/bootstrap-datepicker.min.css')}}">

        <!-- ClockPicker -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/clockPicker/bootstrap-clockpicker.css')}}">

        <!-- Datatable -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/dataTable/dataTables.bootstrap.min.css')}}">
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('css/lib/dataTable/fixedHeader.bootstrap.min.css')}}"> --}}
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/dataTable/responsive.bootstrap.min.css')}}">
        {{-- <link href="{{asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet"> --}}

        <!-- File Input -->
        <link href="{{ asset('file-input/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />

        <!-- SweetAlert -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/sweetAlert/sweetalert2.min.css')}}">

        <!-- Chosen -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/chosen/chosenStyle.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/lib/chosen/chosen.min.css')}}">
        
        <!-- Custom Theme Style -->
        <link href="{{asset('build/css/custom.min.css')}}" rel="stylesheet">

        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">



        @yield('styles')

        <script>
            window.Laravel = {!! json_encode([
                'csrfToken'     => csrf_token(),
                'language'      => app()->getLocale(),
                'url'           => URL::to('/'),
            ]) !!};
        </script>
    </head>

    <body class="{{ Request::path() == 'login' || Request::path() == 'password/reset' ? 'body-content' : '' }} nav-md pr-0" style="">
        <div class="container body">
        @if (Auth::check() && Request::path() != 'elegirCompania')
            <div class="main_container">
                <div class="col-md-3 left_col">

                    <div class="left_col scroll-view">

                        <div class="navbar nav_title" style="border: 0;">
                            <a href="{{ route('home') }}" class="site_title"><i class="fa fa-home"></i> </a>

                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="{{asset(Auth::user()->picture)}}" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>@lang('header.Welcome'),</span>
                                <h2>{{ Auth::user()->name }}</h2>
                                {{-- Compañia en session --}}
                                @if(Auth::user()->roles->pluck('id')[0] != 1)
                                    @if(session()->get('Session_Company') != "")
                                        <b>{{ App\User::find(App\Company::find(session()->get('Session_Company'))->user_id)->name }}</b>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <h3>General</h3>
                                    <ul class="nav side-menu">
                                        {{-- <li><a><i class="fa fa-cogs"></i> @lang('words.ManagementTools') <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                @if (Auth::check())
                                                    @can('view_users')
                                                        <li class="{{ Request::is('users*') ? 'active' : '' }}">
                                                            <a href="{{ route('users.index') }}">
                                                                <span class="text-info glyphicon glyphicon-user"></span> @lang('words.ManageUsers')
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('view_permissions')
                                                        <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
                                                            <a href="{{ route('permissions.index') }}">
                                                                <span class="text-danger glyphicon glyphicon-wrench"></span> @lang('words.ManagePermission')
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('view_roles')
                                                        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                                                            <a href="{{ route('roles.index') }}">
                                                                <span class="text-danger glyphicon glyphicon-lock"></span> @lang('words.ManageRoles')
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('view_modulos')
                                                        <li class="{{ Request::is('modulos*') ? 'active' : '' }}">
                                                            <a href="{{ route('modulos.index') }}">
                                                                <span class="text-warning glyphicon glyphicon-tasks"></span> @lang('words.ManageModulo')
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('view_menus')
                                                        <li class="{{ Request::is('menus*') ? 'active' : '' }}">
                                                            <a href="{{ route('menus.index') }}">
                                                                <span class="text-success glyphicon glyphicon-th-list"></span> @lang('words.ManageMenu')
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('view_preformatos')
                                                        <li class="{{ Request::is('preformatos*') ? 'active' : '' }}">
                                                            <a href="{{ route('preformatos.index') }}">
                                                                <span class="text-info glyphicon glyphicon-pushpin"></span> {{trans_choice('words.Preformato',2)}}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @endif
                                            </ul>
                                        </li> --}}
                                        <!--<li><a><i class="fa fa-suitcase"></i> App <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                @can('view_posts')
                                                    <li class="{{ Request::is('posts*') ? 'active' : '' }}">
                                                        <a href="{{ route('posts.index') }}">
                                                            <span class="text-success glyphicon glyphicon-text-background"></span> Posts
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>-->

                                        <!-- Made Menu, with modules -->
                                        {{-- {{ dd(MadeMenu::menus()) }} --}}
                                        @foreach (MadeMenu::menus() as $key => $item)
                                            @include('shared.menu-item', ['item' => $item])
                                        @endforeach


                                    </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <div class="navbar-header">
                                <!-- Branding Image -->
                                <a class="navbar-brand" href="{{ url('/') }}">
                                    <span>@yield('title') {{ config('app.name') }}</span>
                                </a>
                            </div>


                            <ul class="nav navbar-nav navbar-right">
                                @if (Auth::check())
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="{{asset(Auth::user()->picture)}}" alt="">{{ Auth::user()->name }}
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">

                                        <li><a href="{{route('perfiles.index')}}">@lang('header.Profile')</a></li>
                                        {{-- Compañia en session --}}
                                        @if(Auth::user()->roles->pluck('id')[0] != 1)
                                            @if(session()->get('Session_Company') != "")
                                                <li><a href="{{route('elegirCompania')}}">@lang('header.ChangeCompany')</a></li>
                                            @endif
                                        @endif

                                        <li><a href="javascript:;">@lang('header.Help')</a></li>
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                <i class="glyphicon glyphicon-log-out"></i> @lang('header.Logout')
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                                <li role="presentation" class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-envelope-o"></i>
                                        <span class="badge bg-green">1</span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <li>
                                            <a>
                                                <span class="image"><img src="images/user.png" alt="Profile Image" /></span>
                                                <span>
                                                    <span>{{ Auth::user()->name }}</span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="text-center">
                                                <a>
                                                    <strong>See All Alerts</strong>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                @endif
                                <li><a href="{{ route('change_lang', ['lang' => 'es']) }}"><span class="badge badge-primary">ES</span></a></li>
                                <li><a href="{{ route('change_lang', ['lang' => 'en']) }}"><span class="badge badge-primary">EN</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- /top navigation -->
                <div class="right_col" role="main" style="min-height: 100vh;">

                    {{-- <div class="content-page"> --}}
                        {{-- <div id="flash-msg">
                            @include('flash::message')
                        </div> --}}
                        @yield('content')
                    {{-- </div> --}}
                </div>
            </div>
        </div>


    <!-- jQuery -->
    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{asset('vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script src="{{asset('vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{asset('vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{asset('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{asset('vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{asset('vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{asset('vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{asset('vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{asset('vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{asset('vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{asset('vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- DatePicker -->
    <script src="{{asset('js/lib/datePicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/lib/datePicker/bootstrap-datepicker.es.min.js')}}"></script>

    <!-- ClockPicker -->
    <script src="{{asset('js/lib/clockPicker/bootstrap-clockpicker.js')}}"></script>

    <!-- FullCalendar -->
    <script src="{{asset('js/lib/fullCalendar/moment.min.js')}}"></script>
    <script src="{{asset('js/lib/fullCalendar/fullcalendar.min.js')}}"></script>

    <!-- Cambiar el idioma del calendario -->
    @if(app()->getLocale()=='es')
        <script src="{{ asset('js/lib/fullCalendar/es.js') }}"></script>
    @endif

    <!-- Datatable -->
    <script src="{{ asset('js/lib/dataTable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/lib/dataTable/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/lib/dataTable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/lib/dataTable/responsive.bootstrap.min.js') }}"></script>
    
    <!-- SweetAlert -->
    <script src="{{ asset('js/lib/sweetAlert/sweetalert2.min.js') }}"></script>

    <!-- text editor -->
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>

    <!-- Moment timezone -->
    <script src="{{ asset('js/lib/momentTz/moment-timezone-with-data-2012-2022.min.js') }}"></script>

    <!-- Chosen -->
    <script src="{{asset('js/lib/chosen/chosen.jquery.min.js')}}"></script>
    <script src="{{asset('js/lib/chosen/chosen.proto.min.js')}}"></script>
    
    <!-- Vue JS -->
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <!-- File Input -->
    <script src="{{ asset('file-input/js/plugins/piexif.js') }}" type="text/javascript"></script>
    <script src="{{ asset('file-input/js/plugins/sortable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('file-input/js/plugins/purify.js') }}" type="text/javascript"></script>
    <script src="{{ asset('file-input/js/fileinput.min.js') }}"></script>

    @if( file_exists( "file-input/js/locales/".Session::get('lang').".js" ) )
        <input type="hidden" name="lang" id="lang" value="{{ Session::get('lang') }}">
        <script src="{{ asset('file-input/js/locales/'.Session::get('lang').'.js') }}"></script>
    @else
        <input type="hidden" name="lang" id="lang" value="es">
        <script src="{{ asset('file-input/js/locales/es.js') }}"></script>
    @endif

    <!-- Js to application -->
    <script src="{{asset('js/applicationEvents.js')}}"></script>

	   @yield('scripts')

    <!-- Custom Theme Scripts -->
    <script src="{{asset('build/js/custom.js')}}"></script>

    @if(session('alert'))
        <script>
            toast({
                type: '@php echo session('alert')[0] @endphp',
                title: '@php echo session('alert')[1] @endphp',
                //timer: 10000
            });
            changeTopToast();
        </script>
    @endif
  </body>
</html>
