<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- You can change the theme colors from here -->
    <link rel="stylesheet" href="{{ asset('css/colors/default-dark.css') }}">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Popup CSS -->
    <link href="{{ asset('plugins/Magnific-Popup-master/dist/magnific-popup.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('plugins/popper/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <!-- Notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Magnific popup JavaScript -->
    <script src="{{ asset('plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>
</head>

<body class="fix-header fix-sidebar card-no-border mini-sidebar">

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('images/logo.png') }}" alt="Inicio" style="width: 200px;">
                        </b>
                        <!--End Logo icon -->
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu ti-close"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notificaciones</div>
                                    </li>
                                    <li style="overflow: visible;">
                                        <div class="slimScrollDiv" style="position: relative; overflow: visible hidden; width: auto; height: 250px;">
                                            <div class="message-center" style="overflow: hidden; width: auto; height: 250px;">
                                                <!-- Message -->
                                                <a href="#">
                                                    <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                    <div class="mail-contnet">
                                                        <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 192.901px;"></div>
                                            <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li style="overflow: visible;">
                                        <div class="slimScrollDiv" style="position: relative; overflow: visible hidden; width: auto; height: 250px;">
                                            <div class="message-center" style="overflow: hidden; width: auto; height: 250px;">
                                                <!-- Message -->
                                                <a href="#">
                                                    <div class="user-img"> <img src="" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                    <div class="mail-contnet">
                                                        <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                                    </div>
                                                </a>
                                                <!-- Message -->
                                                <a href="#">
                                                    <div class="user-img"> <img src="" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                    <div class="mail-contnet">
                                                        <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                                    </div>
                                                </a>
                                                <!-- Message -->
                                                <a href="#">
                                                    <div class="user-img"> <img src="" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                    <div class="mail-contnet">
                                                        <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                                    </div>
                                                </a>
                                                <!-- Message -->
                                                <a href="#">
                                                    <div class="user-img"> <img src="" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                    <div class="mail-contnet">
                                                        <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 183.824px;"></div>
                                            <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/profile.png') }}" alt="user" class="profile-pic"></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    @guest
                                        @if (Route::has('login'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                                            </li>
                                        @endif

                                        @if (Route::has('register'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                            </li>
                                        @endif
                                    @else
                                        <li>
                                            <div class="dw-user-box">
                                                <div class="u-img"><img src="{{ asset('images/profile.png') }}" alt="user"></div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="#" class="btn btn-rounded btn-danger btn-sm">Ver perfil</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#"><i class="ti-user"></i> Mi perfil</a></li>
                                        <li><a href="#"><i class="ti-wallet"></i> Mi balance</a></li>
                                        <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#"><i class="ti-settings"></i> Preferencias</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li>
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fa fa-power-off"></i>
                                                Cerrar sesión
                                            </a>
                                        </li>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endguest
                                    
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Language -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="flag-icon flag-icon-us"></i></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up"> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-in"></i> India</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-cn"></i> China</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> Dutch</a> </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        @auth
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="slimScrollDiv" style="position: relative; overflow: visible; width: auto; height: 100%;">
                <div class="scroll-sidebar" style="overflow: visible hidden; width: auto; height: 100%;">
                    <!-- User profile -->
                    <div class="user-profile" no-repeat;">
                        <!-- User profile image -->
                        <div class="profile-img"> <img src="{{ asset('images/profile.png') }}" alt="user"> </div>
                        <!-- User profile text-->
                        <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Markarn Doe</a>
                            <div class="dropdown-menu animated flipInY"> <a href="#" class="dropdown-item"><i class="ti-user"></i> My Profile</a> <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a> <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                                <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                                <div class="dropdown-divider"></div> <a href="login.html" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                    <!-- End User profile text-->
                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li class="nav-small-cap">PERSONAL</li>
                            <li class=""> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>
                                <ul aria-expanded="false" class="collapse" style="height: 10px;">
                                    <li><a href="index.html">Dashboard 1</a></li>
                                    <li><a href="index2.html">Dashboard 2</a></li>
                                    <li><a href="index3.html">Dashboard 3</a></li>
                                    <li><a href="index4.html">Dashboard 4</a></li>
                                    <li><a href="index5.html">Dashboard 5</a></li>
                                    <li><a href="index6.html">Dashboard 6</a></li>
                                </ul>
                            </li>
                            <li class=""> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-laptop-windows"></i><span class="hide-menu">Template Demos</span></a>
                                <ul aria-expanded="false" class="collapse" style="height: 10px;">
                                    <li><a href="index.html">Minisidebar</a></li>
                                    <li><a href="https://www.wrappixel.com/demos/admin-templates/material-pro/horizontal/index2.html">Horizontal</a></li>
                                    <li><a href="https://www.wrappixel.com/demos/admin-templates/material-pro/material/index3.html">Material Version</a></li>
                                    <li><a href="https://www.wrappixel.com/demos/admin-templates/material-pro/material-rtl/index4.html">RTL Version</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu">Apps</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="app-calendar.html">Calendar</a></li>
                                    <li><a href="app-chat.html">Chat app</a></li>
                                    <li><a href="app-ticket.html">Support Ticket</a></li>
                                    <li><a href="app-contact.html">Contact / Employee</a></li>
                                    <li><a href="app-contact2.html">Contact Grid</a></li>
                                    <li><a href="app-contact-detail.html">Contact Detail</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-email"></i><span class="hide-menu">Inbox</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="app-email.html">Mailbox</a></li>
                                    <li><a href="app-email-detail.html">Mailbox Detail</a></li>
                                    <li><a href="app-compose.html">Compose Mail</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu">Ui Elements</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="ui-cards.html">Cards</a></li>
                                    <li><a href="ui-user-card.html">User Cards</a></li>
                                    <li><a href="ui-buttons.html">Buttons</a></li>
                                    <li><a href="ui-modals.html">Modals</a></li>
                                    <li><a href="ui-tab.html">Tab</a></li>
                                    <li><a href="ui-tooltip-popover.html">Tooltip &amp; Popover</a></li>
                                    <li><a href="ui-tooltip-stylish.html">Tooltip stylish</a></li>
                                    <li><a href="ui-sweetalert.html">Sweet Alert</a></li>
                                    <li><a href="ui-notification.html">Notification</a></li>
                                    <li><a href="ui-progressbar.html">Progressbar</a></li>
                                    <li><a href="ui-nestable.html">Nestable</a></li>
                                    <li><a href="ui-range-slider.html">Range slider</a></li>
                                    <li><a href="ui-timeline.html">Timeline</a></li>
                                    <li><a href="ui-typography.html">Typography</a></li>
                                    <li><a href="ui-horizontal-timeline.html">Horizontal Timeline</a></li>
                                    <li><a href="ui-session-timeout.html">Session Timeout</a></li>
                                    <li><a href="ui-session-ideal-timeout.html">Session Ideal Timeout</a></li>
                                    <li><a href="ui-bootstrap.html">Bootstrap Ui</a></li>
                                    <li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
                                    <li><a href="ui-bootstrap-switch.html">Bootstrap Switch</a></li>
                                    <li><a href="ui-list-media.html">List Media</a></li>
                                    <li><a href="ui-ribbons.html">Ribbons</a></li>
                                    <li><a href="ui-grid.html">Grid</a></li>
                                    <li><a href="ui-carousel.html">Carousel</a></li>
                                    <li><a href="ui-date-paginator.html">Date-paginator</a></li>
                                    <li><a href="ui-dragable-portlet.html">Dragable Portlet</a></li>
                                    <li><a href="ui-scrollspy.html">Scrollspy</a></li>
                                    <li><a href="ui-toasts.html">Toasts</a></li>
                                    <li><a href="ui-spinner.html">Spinner</a></li>
                                </ul>
                            </li>
                            <li class="nav-devider"></li>
                            <li class="nav-small-cap">FORMS, TABLE &amp; WIDGETS</li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file"></i><span class="hide-menu">Forms</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="form-basic.html">Basic Forms</a></li>
                                    <li><a href="form-layout.html">Form Layouts</a></li>
                                    <li><a href="form-addons.html">Form Addons</a></li>
                                    <li><a href="form-material.html">Form Material</a></li>
                                    <li><a href="form-float-input.html">Floating Lable</a></li>
                                    <li><a href="form-pickers.html">Form Pickers</a></li>
                                    <li><a href="form-upload.html">File Upload</a></li>
                                    <li><a href="form-mask.html">Form Mask</a></li>
                                    <li><a href="form-validation.html">Form Validation</a></li>
                                    <li><a href="form-bootstrap-validation.html">Form Bootstrap Validation</a></li>
                                    <li><a href="form-dropzone.html">File Dropzone</a></li>
                                    <li><a href="form-icheck.html">Icheck control</a></li>
                                    <li><a href="form-img-cropper.html">Image Cropper</a></li>
                                    <li><a href="form-bootstrapwysihtml5.html">HTML5 Editor</a></li>
                                    <li><a href="form-typehead.html">Form Typehead</a></li>
                                    <li><a href="form-wizard.html">Form Wizard</a></li>
                                    <li><a href="form-xeditable.html">Xeditable Editor</a></li>
                                    <li><a href="form-summernote.html">Summernote Editor</a></li>
                                    <li><a href="form-tinymce.html">Tinymce Editor</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Tables</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="table-basic.html">Basic Tables</a></li>
                                    <li><a href="table-layout.html">Table Layouts</a></li>
                                    <li><a href="table-data-table.html">Data Tables</a></li>
                                    <li><a href="table-footable.html">Footable</a></li>
                                    <li><a href="table-jsgrid.html">Js Grid Table</a></li>
                                    <li><a href="table-responsive.html">Responsive Table</a></li>
                                    <li><a href="table-bootstrap.html">Bootstrap Tables</a></li>
                                    <li><a href="table-editable-table.html">Editable Table</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-widgets"></i><span class="hide-menu">Widgets</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="widget-apps.html">Widget Apps</a></li>
                                    <li><a href="widget-data.html">Widget Data</a></li>
                                    <li><a href="widget-charts.html">Widget Charts</a></li>
                                </ul>
                            </li>
                            <li class="nav-devider"></li>
                            <li class="nav-small-cap">EXTRA COMPONENTS</li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Page Layout</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="layout-single-column.html">1 Column</a></li>
                                    <li><a href="layout-fix-header.html">Fix header</a></li>
                                    <li><a href="layout-fix-sidebar.html">Fix sidebar</a></li>
                                    <li><a href="layout-fix-header-sidebar.html">Fixe header &amp; Sidebar</a></li>
                                    <li><a href="layout-boxed.html">Boxed Layout</a></li>
                                    <li><a href="layout-logo-center.html">Logo in Center</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-open-variant"></i><span class="hide-menu">Sample Pages</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="starter-kit.html">Starter Kit</a></li>
                                    <li><a href="pages-blank.html">Blank page</a></li>
                                    <li><a href="#" class="has-arrow">Authentication <span class="label label-rounded label-success">6</span></a>
                                        <ul aria-expanded="false" class="collapse">
                                            <li><a href="pages-login.html">Login 1</a></li>
                                            <li><a href="pages-login-2.html">Login 2</a></li>
                                            <li><a href="pages-register.html">Register</a></li>
                                            <li><a href="pages-register2.html">Register 2</a></li>
                                            <li><a href="pages-lockscreen.html">Lockscreen</a></li>
                                            <li><a href="pages-recover-password.html">Recover password</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="pages-profile.html">Profile page</a></li>
                                    <li><a href="pages-animation.html">Animation</a></li>
                                    <li><a href="pages-fix-innersidebar.html">Sticky Left sidebar</a></li>
                                    <li><a href="pages-fix-inner-right-sidebar.html">Sticky Right sidebar</a></li>
                                    <li><a href="pages-invoice.html">Invoice</a></li>
                                    <li><a href="pages-treeview.html">Treeview</a></li>
                                    <li><a href="pages-utility-classes.html">Helper Classes</a></li>
                                    <li><a href="pages-search-result.html">Search result</a></li>
                                    <li><a href="pages-scroll.html">Scrollbar</a></li>
                                    <li><a href="pages-pricing.html">Pricing</a></li>
                                    <li><a href="pages-lightbox-popup.html">Lighbox popup</a></li>
                                    <li><a href="pages-gallery.html">Gallery</a></li>
                                    <li><a href="pages-faq.html">Faqs</a></li>
                                    <li><a href="#" class="has-arrow">Error Pages</a>
                                        <ul aria-expanded="false" class="collapse">
                                            <li><a href="pages-error-400.html">400</a></li>
                                            <li><a href="pages-error-403.html">403</a></li>
                                            <li><a href="pages-error-404.html">404</a></li>
                                            <li><a href="pages-error-500.html">500</a></li>
                                            <li><a href="pages-error-503.html">503</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file-chart"></i><span class="hide-menu">Charts</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="chart-morris.html">Morris Chart</a></li>
                                    <li><a href="chart-chartist.html">Chartis Chart</a></li>
                                    <li><a href="chart-echart.html">Echarts</a></li>
                                    <li><a href="chart-flot.html">Flot Chart</a></li>
                                    <li><a href="chart-knob.html">Knob Chart</a></li>
                                    <li><a href="chart-chart-js.html">Chartjs</a></li>
                                    <li><a href="chart-sparkline.html">Sparkline Chart</a></li>
                                    <li><a href="chart-extra-chart.html">Extra chart</a></li>
                                    <li><a href="chart-peity.html">Peity Charts</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-brush"></i><span class="hide-menu">Icons</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="icon-material.html">Material Icons</a></li>
                                    <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                                    <li><a href="icon-themify.html">Themify Icons</a></li>
                                    <li><a href="icon-linea.html">Linea Icons</a></li>
                                    <li><a href="icon-weather.html">Weather Icons</a></li>
                                    <li><a href="icon-simple-lineicon.html">Simple Lineicons</a></li>
                                    <li><a href="icon-flag.html">Flag Icons</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-map-marker"></i><span class="hide-menu">Maps</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="map-google.html">Google Maps</a></li>
                                    <li><a href="map-vector.html">Vector Maps</a></li>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-arrange-send-backward"></i><span class="hide-menu">Multi level dd</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">item 1.1</a></li>
                                    <li><a href="#">item 1.2</a></li>
                                    <li> <a class="has-arrow" href="#" aria-expanded="false">Menu 1.3</a>
                                        <ul aria-expanded="false" class="collapse">
                                            <li><a href="#">item 1.3.1</a></li>
                                            <li><a href="#">item 1.3.2</a></li>
                                            <li><a href="#">item 1.3.3</a></li>
                                            <li><a href="#">item 1.3.4</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">item 1.4</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <!-- End Sidebar navigation -->
                </div>
                <div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 746.088px;"></div>
                <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; left: 1px;"></div>
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Settings"><i class="ti-settings"></i></a>
                <!-- item--><a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i class="mdi mdi-gmail"></i></a>
                <!-- item--><a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
        </aside>
        @endauth

        <main class="@auth page-wrapper @endauth">
            @yield('content')
        </main>

        <footer class="footer">
            © 2023 Aguas Nico
        </footer>
    </div>
</body>

</html>