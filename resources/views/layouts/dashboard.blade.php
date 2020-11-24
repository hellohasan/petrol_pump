<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="{{ $basic->meta_tag }}">
    <title>{{ $site_title }} | {{ $page_title }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/backend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">

    @yield('style')
</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar">
<!-- - var navbarShadow = true-->
<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-light bg-gradient-x-grey-blue">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="navbar-brand">
                        <img alt="logo" src="{{ asset('storage/images/logo.png') }}" style="height: 36px" class="brand-logo">
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="fa fa-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div id="navbar-mobile" class="collapse navbar-collapse">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link nav-link-expand"><i class="ficon ft-maximize"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-right">

                    @hasanyrole('Super Admin|Manager')
                    <li class="dropdown dropdown-notification nav-item">
                        <a href="{{ route('upcoming-due-repayment') }}" class="nav-link nav-link-label" title="Due Repayment"><i class="ficon ft-sliders"></i>
                            <span class="badge badge-pill badge-default badge-danger badge-default badge-up" >{{ $dueCount }}</span>
                        </a>
                    </li>
                    @endhasanyrole

                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="avatar avatar-online">
                                <img src="{{ asset('storage/images') }}/{{ Auth::user()->image }}" alt="avatar"><i></i>
                            </span>
                            @hasanyrole('Super Admin')
                                <span class="user-name">{{ Auth::user()->name }} - {{ $basic->symbol }}{{ $basic->balance }}</span>
                            @else
                                <span class="user-name">{{ Auth::user()->name }} - {{ $basic->symbol }} {{ Auth::user()->balance }}</span>
                            @endhasanyrole
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"><a href="{{ route('edit-profile') }}" class="dropdown-item"><i class="ft-edit-3"></i> Edit Profile</a>
                            <a href="{{ route('admin-change-password') }}" class="dropdown-item"><i class="ft-check-square"></i> Change Password</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item"><i class="ft-power"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div data-scroll-to-active="true" class="main-menu menu-fixed menu-light menu-accordion menu-shadow">
    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class=" navigation-header">
                <span>General</span><i data-toggle="tooltip" data-placement="right" data-original-title="General" class=" ft-minus"></i>
            </li>
            <li class="{{ Request::is('admin-dashboard') ? 'active' : '' }} nav-item">
                <a href="{{ route('dashboard') }}"><i class="ft-home"></i><span data-i18n="" class="menu-title">Dashboard</span></a>
            </li>

            <li class="{{ Request::is('admin/fuel-sell') ? 'active' : '' }} nav-item">
                <a href="{{ route('fuel.sell') }}"><i class="ft-filter"></i><span data-i18n="" class="menu-title">Sell Fuel</span></a>
            </li>

            <li class="{{ Request::is('admin/fuel-sell-history') ? 'active' : '' }} nav-item">
                <a href="{{ route('fuel.sell.history') }}"><i class="ft-list"></i><span data-i18n="" class="menu-title">Sell History </span></a>
            </li>

            @role('Seller')
            <li class="nav-item"><a href="#"><i class="ft-activity"></i><span data-i18n="" class="menu-title">Sell Statistic</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/daily-statistic') ? 'active' : '' }}"><a href="{{ route('daily-statistic') }}" class="menu-item">Daily Statistic</a></li>
                    <li class="{{ Request::is('admin/monthly-statistic') ? 'active' : '' }}"><a href="{{ route('monthly-statistic') }}" class="menu-item">Monthly Statistic</a></li>
                    <li class="{{ Request::is('admin/yearly-statistic') ? 'active' : '' }}"><a href="{{ route('yearly-statistic') }}" class="menu-item">Yearly Statistic</a></li>
                </ul>
            </li>
            @endrole

            @hasanyrole('Super Admin|Manager')

            <li class=" nav-item"><a href="#"><i class="ft-shopping-cart"></i><span data-i18n="" class="menu-title">Sell Product</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/sell-new') ? 'active' : '' }}"><a href="{{ route('sell-new') }}" class="menu-item">New Sell</a></li>
                    <li class="{{ Request::is('admin/sell-history') ? 'active' : '' }}"><a href="{{ route('sell-history') }}" class="menu-item">Sell History</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="#"><i class="ft-activity"></i><span data-i18n="" class="menu-title">Sell Statistic</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/daily-statistic') ? 'active' : '' }}"><a href="{{ route('daily-statistic') }}" class="menu-item">Daily Statistic</a></li>
                    <li class="{{ Request::is('admin/monthly-statistic') ? 'active' : '' }}"><a href="{{ route('monthly-statistic') }}" class="menu-item">Monthly Statistic</a></li>
                    <li class="{{ Request::is('admin/yearly-statistic') ? 'active' : '' }}"><a href="{{ route('yearly-statistic') }}" class="menu-item">Yearly Statistic</a></li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="ft-box"></i><span data-i18n="" class="menu-title">Manage Machine</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/machine') ? 'active' : '' }}"><a href="{{ route('machine.index') }}" class="menu-item">Machine List</a></li>
                    <li class="{{ Request::is('admin/machine-reading') ? 'active' : '' }}"><a href="{{ route('machine.reading') }}" class="menu-item">Machine Reading</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="#"><i class="ft-credit-card"></i><span data-i18n="" class="menu-title">Manage Account</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/mange-account') ? 'active' : '' }}"><a href="{{ route('mange-account') }}" class="menu-item">New Action</a></li>
                    <li class="{{ Request::is('admin/account-history') ? 'active' : '' }}"><a href="{{ route('account-history') }}" class="menu-item">Account History</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="#"><i class="ft-credit-card"></i><span data-i18n="" class="menu-title">Manage Cash</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/receive-new') ? 'active' : '' }}"><a href="{{ route('receive.new') }}" class="menu-item">Cash Receive</a></li>
                    <li class="{{ Request::is('admin/receive-history') ? 'active' : '' }}"><a href="{{ route('receive.history') }}" class="menu-item">Receive History</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="#"><i class="ft-fast-forward"></i><span data-i18n="" class="menu-title">Due Repayment</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/repayment-new') ? 'active' : '' }}"><a href="{{ route('repayment-new') }}" class="menu-item">New Repayment</a></li>
                    <li class="{{ Request::is('admin/repayment-history') ? 'active' : '' }}"><a href="{{ route('repayment-history') }}" class="menu-item">Repayment History</a></li>
                    <li class="{{ Request::is('admin/due-order-history') ? 'active' : '' }}"><a href="{{ route('due-order-history') }}" class="menu-item">Due Sell List</a></li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="ft-users"></i><span data-i18n="" class="menu-title">Manage Customer</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/customer-new') ? 'active' : '' }}"><a href="{{ route('customer-new') }}" class="menu-item">New Customer</a></li>
                    <li class="{{ Request::is('admin/customer-history') ? 'active' : '' }}"><a href="{{ route('customer-history') }}" class="menu-item">Customer History</a></li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="ft-layers"></i><span data-i18n="" class="menu-title">Manage Store</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/current-store') ? 'active' : '' }}"><a href="{{ route('current-store') }}" class="menu-item">Current Store</a></li>
                    <li class="{{ Request::is('admin/store-new') ? 'active' : '' }}"><a href="{{ route('store-new') }}" class="menu-item">New Store</a></li>
                    <li class="{{ Request::is('admin/store-history') ? 'active' : '' }}"><a href="{{ route('store-history') }}" class="menu-item">Store History</a></li>
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="ft-box"></i><span data-i18n="" class="menu-title">Manage Product</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/product-new') ? 'active' : '' }}"><a href="{{ route('product-new') }}" class="menu-item">New Product</a></li>
                    <li class="{{ Request::is('admin/product-history') ? 'active' : '' }}"><a href="{{ route('product-history') }}" class="menu-item">Product History</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="#"><i class="ft-credit-card"></i><span data-i18n="" class="menu-title">Company Payment</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/company-send') ? 'active' : '' }}"><a href="{{ route('company-send') }}" class="menu-item">Company Send</a></li>
                    <li class="{{ Request::is('admin/payment-new') ? 'active' : '' }}"><a href="{{ route('payment-new') }}" class="menu-item">We Send</a></li>
                    <li class="{{ Request::is('admin/payment-history') ? 'active' : '' }}"><a href="{{ route('payment-history') }}" class="menu-item">Payment History</a></li>
                </ul>
            </li>

            @role('Super Admin')
            <li class="{{ Request::is('admin/manage-category') ? 'active' : '' }} nav-item">
                <a href="{!! route('manage-category') !!}"><i class="ft-grid"></i><span data-i18n="" class="menu-title">Product Category</span></a>
            </li>

            <li class="{{ Request::is('admin/manage-company') ? 'active' : '' }} nav-item">
                <a href="{!! route('manage-company') !!}"><i class="ft-server"></i><span data-i18n="" class="menu-title">Manage Company</span></a>
            </li>
            @endrole
            @endhasanyrole
            <li class="{{ Request::is('transaction-log') ? 'active' : '' }} nav-item">
                <a href="{{ route('transaction-log') }}"><i class="ft-activity"></i><span data-i18n="" class="menu-title">Transaction Log</span></a>
            </li>
            @role('Super Admin')
            <li class="navigation-header">
                <span>Basic Control</span><i data-toggle="tooltip" data-placement="right" data-original-title="Components" class=" ft-minus"></i>
            </li>
            <li class="{{ Request::is('admin/basic-setting') ? 'active' : '' }} nav-item">
                <a href="{!! route('basic-setting') !!}"><i class="fa fa-cogs" aria-hidden="true"></i><span data-i18n="" class="menu-title">Basic Setup</span></a>
            </li>

            <li class=" nav-item"><a href="#"><i class="ft-users"></i><span data-i18n="" class="menu-title">Manage Users</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/users/create') ? 'active' : '' }}"><a href="{{ route('users.create') }}" class="menu-item">Create User</a></li>
                    <li class="{{ Request::is('admin/users') ? 'active' : '' }}"><a href="{{ route('users.index') }}" class="menu-item">User List</a></li>
                </ul>
            </li>

            {{--<li class=" nav-item"><a href="#"><i class="ft-lock"></i><span data-i18n="" class="menu-title">Role & Permission</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/roles') ? 'active' : '' }}"><a href="{{ route('roles.index') }}" class="menu-item">Manage Role</a></li>
                    <li class="{{ Request::is('admin/permission') ? 'active' : '' }}"><a href="{{ route('permissions.index') }}" class="menu-item">Manage Permission</a></li>
                </ul>
            </li>--}}

            <li class=" navigation-header">
                <span>Web Control</span><i data-toggle="tooltip" data-placement="right" data-original-title="Components" class=" ft-minus"></i>
            </li>
            <li class="{{ Request::is('admin/manage-logo') ? 'active' : '' }} nav-item">
                <a href="{!! route('manage-logo') !!}"><i class="fa fa-picture-o"></i><span data-i18n="" class="menu-title">Manage Logo</span></a>
            </li>
            <li class="{{ Request::is('admin/manage-footer') ? 'active' : '' }} nav-item">
                <a href="{!! route('manage-footer') !!}"><i class="fa fa-sitemap"></i><span data-i18n="" class="menu-title">Manage Footer</span></a>
            </li>
            @endrole
        </ul>
    </div>
</div>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">{{ $page_title }}</h3>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ $page_title }}</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            @if($errors->any())
                @foreach ($errors->all() as $error)

                    <div class="alert alert-icon-left alert-warning alert-dismissible mb-1" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {!!  $error !!}
                    </div>

                @endforeach
            @endif

            @yield('content')

        </div>
    </div>
</div>

<footer class="footer footer-static footer-dark navbar-border">
    <p class="clearfix text-sm-center mb-0 px-2">
      <span class="float-md-left d-block d-md-inline-block">{!! $basic->copy_text !!}</span>
      <span class="float-md-right d-block d-md-inline-block">Version : v1.0.2</span>
    </p>
</footer>

@yield('vendors')
<script src="{{ asset('assets/admin/js/backend.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/toastr.js') }}"></script>
<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;
        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;
        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;
        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
    @endif
</script>
@yield('scripts')
</body>
</html>
