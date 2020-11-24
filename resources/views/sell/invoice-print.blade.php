<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $site_title }} | Invoice</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap3.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/font-awesome/css/font-awesome.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/AdminLTE.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .invoice {
            margin: 0px !important;
        }
        .invoice .invoice-logo {
            margin-bottom: 0px;
        }
        .invoice .invoice-logo-space {
            margin-bottom: 0px;
        }
        .invoice .invoice-logo p {
            padding: 5px 0;
            font-size: 14px;
            line-height: 28px;
            margin-top: 50px;
        }
        .invoice-extra-p{
            font-size: 13px;
        }
        .extra-h{
            margin-top: 0;
        }
        .extra-well{
            margin-bottom: 0;
        }
        .extra-table{
            font-size: 13px;
        }
        .extra-table2{
            font-size: 13px;
            margin-bottom: 1px;
        }
        .paymentStatus{
            padding: 0px 5px;
            border: 1px solid;
            border-radius: 3px;
            font-weight: bold;
        }
        .extra-well{
            padding: 10px 15px;
        }
        @media print {
            #printHide{
                display: none;
            }
        }
    </style>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row invoice-logo">
            <div class="col-xs-6 invoice-logo-space">
                <img src="{{ asset('assets/images/logo.png') }}" class="center-block" alt="" />
                <div class="text-center">
                    <h4>{{ $basic->title }}</h4>
                    <h5>{{ $basic->email }}, {{ $basic->phone }}</h5>
                    <h5>{{ $basic->address }}</h5>
                </div>
            </div>
            <div class="col-xs-6">
                <p class="text-center"> #Invoice : {{ $sell->custom }} <br>
                    <span class="invoice-extra-p">{{ \Carbon\Carbon::parse($sell->created_at)->format('dS M, Y - h:i A ') }}</span>
                </p>
                <div class="text-center">
                    <h5>
                        @if($sell->status == 1)
                            <span class="paymentStatus"> Paid Payment</span>
                        @elseif($sell->status == 0)
                            <span class="paymentStatus"> Due Payment</span>
                        @endif
                    </h5>

                </div>

            </div>
        </div>
        <hr style="margin-top: 0px;margin-bottom: 10px;"/>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-6 col-xs-6">
                <div class="well extra-well extra-table2">
                <h4 class="extra-h">Customer Details</h4>
                    <b>{{ $sell->customer->name }} </b><br>
                    <b>{{ $sell->customer->phone }}</b><br>
                    <b>{{ $sell->customer->address }}</b>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-sm-6 col-xs-6">
                <div class="well extra-well extra-table2">
                    <h4 class="extra-h">Invoice Details</h4>
                    <b>Total :  {{ $basic->symbol }}{{ $sell->order_total }}</b><br>
                    <b>Paid :  {{ $basic->symbol }}{{ $sell->pay_amount }}</b><br>
                    @if($sell->status == 1)
                        <span class="label label-default"> Paid</span>
                    @else
                        <b>Due : {{ $basic->symbol }}{{ $sell->due_amount }} - {{ \Carbon\Carbon::parse($sell->due_payment_date)->format('dS F, Y') }}</b><br>
                    @endif
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <hr style="margin-top: 10px;margin-bottom: 10px;">
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-bordered extra-table2">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Code - Product Name</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sellItem as $key => $sl)
                    <tr>
                        <td>{{ $sl->product->category->name }}</td>
                        <td>{{$sl->code}} - {{ $sl->product->name }}</td>
                        <td>{{$basic->symbol}}{{ $sl->sell_price }}</td>
                        <td>{{ $sl->quantity }}'Pcs</td>
                        <td>{{$basic->symbol}}{{ $sl->subtotal }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-xs-6 col-xs-offset-6">
                <div class="table-responsive">
                    <table class="table extra-table">
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-right">{{ $basic->symbol }}{{ $sell->order_subtotal }}</td>
                        </tr>
                        <tr>
                            <td>Discount - Less</td>
                            <td class="pink text-right">(-) {{ $basic->symbol }}{{ $sell->discount }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold-600">Gross Total</td>
                            <td class="text-bold-600 text-right"> {{ $basic->symbol }}{{ $sell->order_total }}</td>
                        </tr>
                        <tr class="bg-grey bg-lighten-4">
                            <td class="text-bold-600">Paid Amount</td>
                            <td class="text-bold-600 text-right">{{ $basic->symbol }}{{ $sell->pay_amount }}</td>
                        </tr>
                        @if($sell->status == 0)
                            <tr class="bg-grey bg-lighten-4">
                                <td class="red text-bold-600">Due Amount</td>
                                <td class="red text-bold-600 text-right">{{ $basic->symbol }}{{ $sell->due_amount }}</td>
                            </tr>
                            <tr class="bg-grey bg-lighten-4">
                                <td class="red text-bold-600">Due Payment Date</td>
                                <td class="red text-bold-600 text-right">{{ \Carbon\Carbon::parse($sell->due_payment_date)->format('dS F, Y') }}</td>
                            </tr>
                        @endif
                        <tr class="bg-grey bg-lighten-4">
                            <td class="red text-bold-600">In Word</td>
                            <td class="red text-bold-600 text-right text-capitalize">{{ \App\TraitsFolder\CommonTrait::wordAmount($sell->order_total) }}.</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row" id="printHide">
            <hr>
            <div class="col-md-3">
                <a href="{{ route('dashboard') }}" class="btn btn-info btn-block text-uppercase font-weight-bold btn-lg"><i class="fa fa-dashboard"></i> Dashboard</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('sell-new') }}" class="btn btn-warning btn-block text-uppercase font-weight-bold btn-lg"><i class="fa fa-shopping-cart"></i> Sell Another</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('sell-history') }}" class="btn btn-info btn-block text-uppercase font-weight-bold btn-lg"><i class="fa fa-history"></i> Sell History</a>
            </div>
            <div class="col-md-3">
                <button onclick="window.print();" class="btn btn-primary btn-block text-uppercase font-weight-bold btn-lg"><i class="fa fa-print"></i> Print Invoice</button>
            </div>
        </div>
        {{--<hr>
        <div class="text-center">

            <strong>{{ $basic->title }} - Developed By SoftwareZon. Contact : 01716199668</strong>
        </div>--}}
        <!-- /.row -->
    </section>

    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
