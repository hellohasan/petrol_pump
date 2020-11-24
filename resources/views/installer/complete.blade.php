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
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #2<br><small class="font-weight-bold">Server Requirement</small></a></li>
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #3<br><small class="font-weight-bold">Folder Permission</small></a></li>
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #4<br><small class="font-weight-bold">Database Information</small></a></li>
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #5<br><small class="font-weight-bold">Purchase Validation</small></a></li>
            <li class="nav-item active"><a href="#" class="nav-link font-weight-bold text-uppercase">Step #6<br><small class="font-weight-bold">Installation Complete</small></a></li>
        </ul>

        <div class="sw-container tab-content">


            <div class="tab-pane step-content" style="display: block;">
                <h2 class="pb-1 pt-2 text-center font-weight-bold text-uppercase sw-border" style="border-bottom: 2px solid #302e2e">Install Complete</h2>
                <hr>
                <div class="text-center">
                    <button class="btn btn-success font-weight-bold text-uppercase" onclick="window.open('{{ url('/') }}','_blank')">
                        <i class="fa fa-send"></i> go to Your Web
                    </button>
                </div>
                <hr>
                <div class="pb-2 pt-1 text-center">
                    <h5 class="text-capitalize mb-1"><strong>Thanks for purchase this script</strong></h5>
                    <h5 class=""><strong>If Need any help, Please Contact us.</strong></h5>
                    <br>
                    <h6><i class="fa fa-envelope-open-o"></i> e-mail : <a href="mailto:softwarezon@hotmail.com?Subject=Product%20Installation%20Support" target="_top">softwarezon@hotmail.com</a></h6>
                    <h6><i class="fa fa-skype"></i> Skype : <a href="skype:live:softwarezon?add" target="_top">live:softwarezon</a></h6>
                    <h6><i class="fa fa-whatsapp"></i> Whatsapp : <a href="https://api.whatsapp.com/send?phone=8801974447300" target="_top">8801974447300</a></h6>
                </div>
            </div>

        </div>


        <div class="row btn-toolbar sw-toolbar sw-toolbar-bottom">
            <div class="col-sm-12">
                <p class="text-uppercase font-weight-bold text-center ml-2" style="margin-top: 7px;">&copy All Copyright reserve by Softwarezon</p>
            </div>
        </div>
    </div>

</div>
</body>
</html>
