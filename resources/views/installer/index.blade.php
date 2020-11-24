<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Softwarezon Script Installer</title>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.softwarezon.com/assets/images/favicon.png">
    <link rel="shortcut icon" href="http://www.softwarezon.com/assets/images/favicon.png">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/font-awesome/css/font-awesome.css') }}">
    <link href="{{ asset('assets/admin/css/installer.css') }}" rel="stylesheet" type="text/css" />
</head>
<body {{--style="background-image: url('{{ asset('assets/images/installer-bg.png') }}')"--}}>
<div class="container">
    <!-- External toolbar sample -->
    <div class="row d-flex pt-3 align-items-center text-white-50">
        <div class="col-md-12">
            <div class="sw-header">
                <p>softwarezon Script Installer</p>
                <a href="mailto:softwarezon@hotmail.com?Subject=Product%20Installation%20Support" target="_top"><i class="fa fa-handshake-o"></i> Get Support</a>
            </div>
        </div>
    </div>

    <!-- SmartWizard html -->
    <div class="sw-main sw-theme-arrows">

        <ul class="nav nav-tabs step-anchor">
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #1<br><small class="font-weight-bold">Terms of Use</small></a></li>
            <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #2<br><small class="font-weight-bold">Server Requirement</small></a></li>
            <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #3<br><small class="font-weight-bold">Folder Permission</small></a></li>
            <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #4<br><small class="font-weight-bold">Database Information</small></a></li>
            <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #5<br><small class="font-weight-bold">Purchase Validation</small></a></li>
            <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #6<br><small class="font-weight-bold">Installation Complete</small></a></li>
        </ul>

        <div class="sw-container tab-content">


            <div class="tab-pane step-content" style="display: block;">
                <h2 class="pb-1 pt-2 text-center font-weight-bold text-uppercase sw-border" style="border-bottom: 2px solid #302e2e">Terms Of USE</h2>

                <div class="ml-2 pb-1 pt-1">

                    <h5 class="text-capitalize mb-1"><strong>Regular License to be used on one (1) domain only !</strong></h5>
                    <h5 class=""><strong>If you want to use it on multiple websites or domains you have to purchase more licenses.</strong></h5>
                    <br>
                    <h6 style="text-align: left;">
                        <strong>YOU CAN :</strong><br><br>
                        <i class="fa fa-check"></i> Use on one (1) domain only.<br>
                        <i class="fa fa-check"></i> Modify or edit as you want.<br>
                        <i class="fa fa-check"></i> Translate language as you want.<br><br>
                        <strong>YOU CAN'T :</strong><br><br>
                        <i class="fa fa-times" style="color:red;"></i> Resell, distribute, give away or trade by any
                        means to any third party or individual without permission.<br>
                        <i class="fa fa-times" style="color:red;"></i> Include this product into other products sold
                        on Envato market and its affiliate websites.<br>
                        <i class="fa fa-times" style="color:red;"></i> Use on more than one (1) domain.<br>
                        <br>
                        For more information, Please Check <a href="https://codecanyon.net/licenses/terms/regular" target="_blank">Envato License FAQ </a>.
                    </h6>
                </div>
            </div>

        </div>


        <div class="row btn-toolbar sw-toolbar sw-toolbar-bottom">
            <div class="col-sm-6">
                <p class="text-uppercase font-weight-bold text-left ml-2" style="margin-top: 7px;">&copy All Copyright reserve by Softwarezon</p>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-success font-weight-bold text-uppercase pull-right" onclick="window.location.href='{{ route('check-server') }}'">
                    <i class="fa fa-send"></i> Accept and Continue
                </button>
            </div>
        </div>
    </div>

</div>
</body>
</html>
