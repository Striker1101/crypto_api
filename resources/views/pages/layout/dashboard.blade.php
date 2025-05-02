<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ $site_title }}" />
    <meta name="author" content="" />

    <link rel="icon" href="{{ asset('assets/images') }}/{{ $general->favicon }}">

    <title>{{ $site_title }} | {{ $page_title }}</title>

    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/font-icons/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/neon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/neon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/css/sweetalert.css') }}">

    <script src="{{ asset('assets/dashboard/js/jquery-1.11.3.min.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- google translator --}}
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                    pageLanguage: "en"
                },
                "google_translate_element"
            );
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
</head>

<body class="page-body light-theme  page-fade">
    <div class="page-container">
        <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

        <div class="sidebar-menu">

            <div class="sidebar-menu-inner">

                <header class="logo-env">

                    <!-- logo -->
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/images/logo.png') }}" width="120" alt="" />
                        </a>
                    </div>

                    <!-- logo collapse icon -->
                    <div class="sidebar-collapse">
                        <a href="#"
                            class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>

                    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                    <div class="sidebar-mobile-menu visible-xs">
                        <a href="#"
                            class="with-animation"><!-- add class "with-animation" to support animation -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>

                </header>

                <ul id="main-menu" class="main-menu">
                    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->

                    <li class="{{ Request::is('user-dashboard') ? ' opened active' : '' }}">
                        <a href="{{ route('user-dashboard') }}">
                            <i class="entypo-air"></i>
                            <span class="title">Dashboard</span>
                        </a>

                    </li>

                    <li class="{{ Request::is('user-buy-and-trade') ? ' opened active' : '' }}">
                        <a href="{{ route('user-buy-and-trade') }}">
                            <i class="fa fa-credit-card"></i>
                            <span class="title">Buy And Trade</span>
                        </a>
                    </li>


                    <li class="{{ Request::is('user-statement') ? ' opened active' : '' }}">
                        <a href="{{ route('user-statement') }}">
                            <i class="entypo-gauge"></i>
                            <span class="title">Statement</span>
                        </a>

                    </li>

                    <li class="{{ Request::is('user-task') ? ' opened active' : '' }}">
                        <a href="{{ route('user-task') }}">
                            <i class="fa fa-tasks"></i>
                            <span class="title">Tasks</span>
                        </a>

                    </li>

                    <li class="{{ Request::is('user-calender') ? ' opened active' : '' }}">
                        <a href="{{ route('user-calender') }}">
                            <i class="fa fa-calendar-o"></i>
                            <span class="title">Calender</span>
                        </a>

                    </li>

                    <li class="has-sub">
                        <a href="#">
                            <span class="title"><i class="fa fa-money"></i> Deposits</span>
                        </a>
                        <ul>

                            <li>
                                <a href="{{ route('manual-fund-add') }}">
                                    <i class="fa fa-bank"></i>
                                    <span class="title">Add Deposit</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('manual-fund-history') }}">
                                    <span class="title"><i class="fa fa-history"></i> Deposit History</span>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="has-sub">
                        <a href="#">
                            <span class="title"><i class="fa fa-reply-all"></i> Withdrawals</span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('withdraw-new') }}">
                                    <i class="fa fa-plus"></i>
                                    <span class="title">New Withdrawal</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('withdraw-history') }}">
                                    <span class="title"><i class="fa fa-history"></i> Withdrawal History</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @php

                        use App\Models\Notification;
                        use App\Models\Task;
                        use Carbon\Carbon;

                        $messages = Notification::where('user_id', Auth::user()->id)
                            ->where('gene', 'support')
                            ->get();
                        $message_count = Notification::where('user_id', Auth::user()->id)
                            ->where('gene', 'support')
                            ->count();

                        //notification
                        $notifications = Notification::where('user_id', Auth::user()->id)
                            ->where('gene', 'message')
                            ->get();
                        $notification_count = Notification::where('user_id', Auth::user()->id)
                            ->where('gene', 'message')
                            ->count();

                        $mailbox_count = Notification::where('user_id', Auth::user()->id)
                            ->where('status', 0)
                            ->count();

                        //task
                        $tasks = Task::where('user_id', Auth::user()->id)->get();
                        $task_count = Task::where('user_id', Auth::user()->id)->count();

                        //select random color
                        $statuses = ['success', 'warning', 'important', 'info', 'danger'];
                        $randomStatus = $statuses[array_rand($statuses)];
                    @endphp
                    <li class="has-sub root-level">
                        <a href="#">
                            <i class="entypo-mail"></i>
                            <span class="title">Mailbox</span><span
                                class="badge badge-secondary">{{ $mailbox_count }}</span></a>
                        <ul class="visible" style="">
                            <li> <a class="{{ Request::is('user-notification') ? ' opened active' : '' }}"
                                    href="{{ route('user-notification') }}"><i class="entypo-inbox"></i><span
                                        class="title">Inbox</span></a> </li>
                            <li> <a class="{{ Request::is('user-notification-compose') ? ' opened active' : '' }}"
                                    href="{{ route('user-notification-compose') }}"><i
                                        class="entypo-pencil"></i><span class="title">Compose Message</span></a>
                            </li>
                            <li> <a class="{{ Request::is('user-notification') ? ' opened active' : '' }}"
                                    href="{{ route('user-notification', 0) }}"><i class="entypo-attach"></i><span
                                        class="title">View Message</span></a> </li>
                        </ul>
                    </li>

                    <li class="{{ Request::is('user-activity') ? ' opened active' : '' }}">
                        <a href="{{ route('user-activity') }}">
                            <i class="fa fa-indent"></i>
                            <span class="title">User Activity Log</span>
                        </a>
                    </li>


                    @foreach ($withdrawalcnt as $c)
                        @if ($member->name == $c->name)
                            @continue;
                        @endif
                        <li class="">
                            <a href="../user/switch/start/{{ $c->id }}">
                                <i class="fa fa-address-book"></i>{{ $c->name }}'s Account<br>
                            </a>
                        </li>
                    @endforeach




                </ul>

            </div>

        </div>

        {{-- main-content was the class here --}}
        <div class="main-content">
            <div class="row">
                <div class="col-md-6 col-sm-8 clearfix">
                    <ul class="user-info pull-left pull-none-xsm">
                        <li class="profile-info dropdown"> <a href="#" class="dropdown-toggle"
                                data-toggle="dropdown">
                                <img src="{{ asset('assets/images') }}/{{ Auth::user()->image }}" alt
                                    class="img-circle" width="44" />
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li class="caret"></li>
                                <li> <a href="{{ route('user-edit') }}"> <i class="entypo-user"></i>
                                        Edit Profile
                                    </a> </li>
                                <li> <a href="{{ route('user-notification') }}"> <i class="entypo-mail"></i>
                                        Inbox
                                    </a> </li>
                                <li> <a href="{{ route('user-calender') }}"> <i class="entypo-calendar"></i>
                                        Calendar
                                    </a> </li>
                                <li> <a href="{{ route('user-task') }}"> <i class="entypo-clipboard"></i>
                                        Tasks
                                    </a> </li>
                                <li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Log Out <i class="entypo-logout right"></i>
                                    </a>
                                </li>
                        </li>
                    </ul>
                    </li>
                    </ul>
                    <style>
                        @media (max-width: 768px) {
                            .dropdown-menu-inline {
                                width: 50% !important;
                            }
                        }
                    </style>
                    <ul class="user-info pull-left pull-right-xs pull-none-xsm">
                        <li class="notifications dropdown"> <a href="#" class="dropdown-toggle"
                                data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="entypo-attention"></i> <span
                                    class="badge badge-info">{{ $notification_count }}</span> </a>

                            <ul class="dropdown-menu scroller dropdown-menu-inline" style="float:right; width: 100%;">
                                <li class="top">
                                    <p class="small"> <a href="#" class="pull-right">Mark all Read</a>
                                        You have <strong>{{ $notification_count }}</strong> new notifications.
                                    </p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller ">

                                        @foreach ($notifications as $item)
                                            <li class="{{ $item->status == 1 ? 'unread' : '' }} notification-success">
                                                <a id="notify_container"
                                                    href="{{ route('user-notification-details', $item->id) }}">
                                                    <i class="{{ $item->icon }} pull-right"></i>
                                                    <div>
                                                        <span class="line">
                                                            <strong>{{ $item->title }}</strong>
                                                        </span>
                                                        <span class="line small">
                                                            {{ Carbon::parse($item->created_at)->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="{{ route('user-notification', ['type' => 'warning']) }}">View all
                                        notifications</a>
                                </li>
                            </ul>
                        </li>
                        <li class="notifications dropdown"> <a href="#" class="dropdown-toggle"
                                data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <i
                                    class="entypo-mail"></i> <span
                                    class="badge badge-secondary">{{ $message_count }}</span> </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form class="top-dropdown-search">
                                        <div class="form-group"> <input type="text" class="form-control"
                                                placeholder="Search anything..." name="searchMessage" /> </div>
                                    </form>
                                    <ul class="dropdown-menu-list scroller  dropdown-menu-inline"
                                        style="float:right; width: 100%;">
                                        @foreach ($messages as $message)
                                            <li class="{{ $message->status ? 'active' : '' }}">
                                                <a href="{{ route('user-notification-details', $message->id) }}">
                                                    <span class="image pull-right">
                                                        <img src="{{ $message->icon }}" width="44" alt
                                                            class="img-circle" />
                                                    </span>
                                                    <span class="line">
                                                        <strong>{{ $message->title }}</strong>
                                                        - {{ Carbon::parse($message->created_at)->diffForHumans() }}
                                                    </span>
                                                    <span class="line desc small">

                                                    </span> </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="{{ route('user-notification', ['type' => 'message']) }}">View all
                                        Messages</a>
                                </li>
                            </ul>
                        </li>
                        <li class="notifications dropdown"> <a href="#" class="dropdown-toggle"
                                data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <i
                                    class="entypo-list"></i> <span class="badge badge-warning">{{ $task_count }}
                                </span> </a>
                            <ul class="dropdown-menu">
                                <li class="top">
                                    <p>You have {{ $task_count }} pending tasks</p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller">
                                        @foreach ($tasks as $task)
                                            <li>
                                                <a href="#">
                                                    <span class="task">
                                                        <span class="desc">{{ $task->title }}</span>
                                                        <span class="percent">{{ $task->percent }}%</span> </span>
                                                    <span class="progress">
                                                        <span style="width: {{ $task->percent }}%;"
                                                            class="progress-bar progress-bar-{{ $randomStatus }}">
                                                            <span class="sr-only">{{ $task->percent }}%
                                                                Complete</span>
                                                        </span> </span>
                                                </a>
                                            </li>
                                        @endforeach
                                </li>
                            </ul>
                        </li>
                        <li class="external"> <a href="{{ route('user-task') }}">See all
                                tasks</a> </li>
                    </ul>
                    </li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                    <ul class="list-inline links-list pull-right">
                        <li class="dropdown language-selector">
                            <div id="google_translate_element"></div>

                            <style>
                                #google_translate_element>div>span {
                                    display: none;
                                }

                                #google_translate_element>div::after {
                                    content: "EveryDrop";
                                }
                            </style>
                        </li>
                        <li class="sep"></li>
                        <li class="sep"></li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log Out <i class="entypo-logout right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <!--  ==================================VALIDATION ERRORS==================================  -->
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {!! $error !!}
                            </div>
                        @endforeach
                    @endif
                    <!--  ==================================SESSION MESSAGES==================================  -->
                </div>
            </div>

            @yield('content')


            <!-- Footer -->
            <footer class="main">

                &copy; @php echo date('Y'); @endphp <strong>All Copyright Reserved.</strong>

            </footer>
        </div>


    </div>


    <!-- Bottom scripts (common) -->
    <script src="{{ asset('assets/dashboard/js/TweenMax.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/joinable.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/resizeable.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/neon-api.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/jquery-jvectormap-europe-merc-en.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/toastr.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/rickshaw/rickshaw.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/rickshaw/vendor/d3.v3.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/morris.min.js') }}"></script>

    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-28991003-7']);
        _gaq.push(['_setDomainName', 'demo.neontheme.com']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') +
                '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <script>
        @if (session()->has('message'))
            swal({
                title: "{!! session()->get('title') !!}",
                text: "{!! session()->get('message') !!}",
                type: "{!! session()->get('type') !!}",
                confirmButtonText: "OK"
            });
        @endif
    </script>


    @yield('scripts')

    <!-- JavaScripts initializations and stuff -->
    <script src="{{ asset('assets/dashboard/js/neon-custom.js') }}"></script>
</body>

</html>
