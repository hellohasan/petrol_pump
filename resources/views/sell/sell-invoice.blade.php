@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/invoice.css') }}">

    <script>
    function printDiv(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    </script>
@endsection
@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-content collpase show">
                        <div class="card-body">
                            <section class="card">
                                <div id="invoice-template" class="invoice-template card-body">
                                <!-- Invoice Company Details -->
                                <div id="invoice-company-details" class="row">
                                    <div class="col-6 text-center">
                                        <img src="{{ asset('assets/images/logo.png') }}" class="center-block" alt="" />
                                        <div class="text-center">
                                            <h4>{{ $basic->title }}</h4>
                                            <h5>{{ $basic->email }}, {{ $basic->phone }}</h5>
                                            <h5>{{ $basic->address }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-3 text-right">
                                        <h6 class="text-center"> #Invoice : {{ $sell->custom }} </h6>
                                        <h6 class="text-center"> {{ \Carbon\Carbon::parse($sell->updated_at)->format('dS F Y, h:i A') }}  </h6>
                                        @if($sell->status == 1)
                                            <h6 class="text-center"><span class="p-35 border rounded">Paid Payment</span></h6>
                                        @else
                                            <h6 class="text-center"><span class="p-35 border rounded">Due Payment</span></h6>
                                        @endif
                                    </div>
                                </div>
                                <!--/ Invoice Company Details -->
                                <!-- Invoice Customer Details -->
                                <div id="invoice-customer-details" class="row pt-0">
                                    <div class="col-6 text-left ">
                                        <div class="p-1 mb-0 border rounded">
                                            <h5 class="extra-h">Customer Details</h5>
                                            <ul class="px-0 mb-0 list-unstyled">
                                                <li class="text-bold-800">{{ $sell->customer->name }}</li>
                                                <li>{{ $sell->customer->phone }}</li>
                                                <li>{{ $sell->customer->address }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="p-1 mb-0 border rounded">
                                            <h5 class="extra-h">Invoice Details</h5>
                                            <ul class="px-0 mb-0 list-unstyled">
                                                <li class="text-bold-800">{{ \Carbon\Carbon::parse($sell->created_at)->format('dS M,y - h:i A') }}</li>
                                                <li><span class="text-muted">Payment :</span>
                                                    @if($sell->payment_type == 0)
                                                        On Paid
                                                    @elseif($sell->payment_type == 1)
                                                        Due Payment
                                                    @endif
                                                    -
                                                    @if($sell->payment_with == 0)
                                                        Cash
                                                    @else
                                                        Cheque
                                                    @endif

                                                </li>
                                                @if($sell->payment_type == 0)
                                                    Paid Amount : {{ $basic->symbol }}{{ $sell->pay_amount }}
                                                @elseif($sell->payment_type == 1)
                                                    <li><span class="text-muted">Payment Date :</span> {{ \Carbon\Carbon::parse($sell->due_payment_date)->format('dS M, Y') }}</li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Invoice Customer Details -->
                                <!-- Invoice Items Details -->
                                <div id="invoice-items-details" class="pt-1">
                                    <div class="row">
                                        <div class="table-responsive col-12">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">SL#</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Item & Description</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Rate</th>
                                                    <th class="text-right">Subtotal</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sellItem as $key => $sl)
                                                <tr>
                                                    <th class="text-center" scope="row">{{++$key}}</th>
                                                    <td class="text-center">{{ $sl->product->category->name }}</td>
                                                    <td class="text-center">{{ $sl->code }} - {{ $sl->product->name }}</td>
                                                    <td class="text-center">{{ $sl->quantity }} {{ $sl->product->category->unit }}</td>
                                                    <td class="text-center">{{$basic->symbol}} {{ $sl->sell_price }}</td>
                                                    <td class="text-right">{{$basic->symbol}} {{ $sl->subtotal }}</td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-center rowAlign">
                                            <div class="row bottom-align-text">
                                                <div class="col-6">
                                                    <div class="text-center displayHide">
                                                        <h6>________________________</h6>
                                                        <h6>Buyer Signature</h6>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center displayHide">
                                                        <h6>________________________</h6>
                                                        <h6>Seller Signature</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="table-responsive">
                                                <table class="table p-0">
                                                    <tbody>
                                                    <tr>
                                                        <td>Subtotal</td>
                                                        <td class="text-right">{{$basic->symbol}} {{ $sell->order_subtotal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Discount - Less</td>
                                                        <td class="pink text-right">(-) {{$basic->symbol}} {{ $sell->discount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-bold-600">Gross Total</td>
                                                        <td class="text-bold-600 text-right"> {{$basic->symbol}} {{ $sell->order_total }}</td>
                                                    </tr>
                                                    <tr class="bg-grey bg-lighten-4">
                                                        <td class="text-bold-600">Paid Amount</td>
                                                        <td class="text-bold-600 text-right">{{$basic->symbol}} {{ $sell->pay_amount }}</td>
                                                    </tr>
                                                    @if($sell->status == 0)
                                                        <tr class="bg-grey bg-lighten-4">
                                                            <td class="red text-bold-600">Due Amount</td>
                                                            <td class="red text-bold-600 text-right">{{$basic->symbol}} {{ $sell->due_amount }}</td>
                                                        </tr>
                                                    @endif
                                                    @if (($sell->customer->total_amount - $sell->customer->pay_amount) > 0)
                                                        <tr class="bg-grey bg-lighten-4">
                                                            <td class="red text-bold-600">Present Due</td>
                                                            <td class="red text-bold-600 text-right">{{$basic->symbol}} {{ $sell->customer->total_amount - $sell->customer->pay_amount }}</td>
                                                        </tr>
                                                    @endif

                                                    <tr class="bg-grey bg-lighten-4">
                                                        <td class="red text-bold-600">In Word</td>
                                                        <td class="red text-bold-600 text-right text-capitalize">{!! \App\TraitsFolder\CommonTrait::wordAmount($sell->order_total) !!}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Footer -->
                                <div id="invoice-footer">
                                    <div class="row">
                                        <div class="col-3 offset-6 text-center">
                                            @if (getLoginRole() === 'Seller')
                                                <a href="{{ route('fuel.sell') }}" class="btn btn-primary btn-block btn-lg my-1"><i class="fa fa-shopping-cart"></i> New Sell</a>
                                            @else
                                                <a href="{{ route('fuel.sell') }}" class="btn btn-success btn-lg my-1"><i class="fa fa-filter"></i> Fuel</a>
                                                <a href="{{ route('sell-new') }}" class="btn btn-primary btn-lg my-1"><i class="fa fa-shopping-cart"></i> Others</a>
                                            @endif
                                        </div>
                                        <div class="col-3 text-center">
                                            <button type="button" onclick="printDiv('invoice-template')" style="cursor: pointer" class="btn btn-primary btn-block btn-lg my-1"><i class="fa fa-print"></i> Print Invoice</button>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Invoice Footer -->
                                <!--<div class="company-text text-hide">
                                  <p class="text-center">Created & Developed By : Softwarezon, +8801974447300</p>
                                </div>-->
                            </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
@endsection
@section('scripts')

@endsection
