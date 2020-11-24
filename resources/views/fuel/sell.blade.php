@extends('layouts.dashboard')
@section('style')
    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap-datetimepicker.css') }}">
    <style>
        .images_list li {
            list-style: none;
            float: left;
            width: 288px;
            height: 120px;
            margin-right: 10px;
            margin-bottom: 10px;
            background-color: green;
            padding: 10px;
            border-radius: 10px;
            font-size: 15px;
            text-align: center;
        }

        .images_list li:last-child{
            margin-right: 0px;
        }

        .images_list li span {
            display:none;
            position:absolute;
            top: 15px;
            left: 115px;
            font-size: 60px;
            color: white;
        }

        .border {
            border: 6px solid #D8D8D8;
        }

        .selected {
            border: 6px solid green;
            position:relative;
            box-shadow: 0px 3px 22px 0px #7b7b7b;
        }

        .hidden {
            display:none;
        }

        .images_list li.selected span {
            display:block;
        }

        form .form-group, .form-group{
            margin-bottom: 1rem;
        }
    </style>
@endsection
@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            <form class="form-horizontal" action="{{ route('fuel.sell.submit') }}" method="post" role="form" >
                                {!! csrf_field() !!}

                                <div class="form-body">
                                    <div class="form-group row">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Type</strong></label>
                                        <div class="col-md-12">
                                            <select name="customer_type" id="customer_type" data-placeholder="-- Customer Type --" class="form-control select2 font-weight-bold" required>
                                                <option value=""></option>
                                                <option value="2" selected class="font-weight-bold">Bypass Customer</option>
                                                <option value="1" class="font-weight-bold">Exist Customer</option>
                                                <option value="0" class="font-weight-bold">New Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="old_customer" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">Select Customer</strong></label>
                                            <div class="col-md-12">
                                                <select name="customer_id" id="customer_id" data-placeholder="-- Select Customer Phone Or Name --" class="form-control select24 font-weight-bold select_customer">
                                                    <option value=""></option>
                                                    @foreach($customer as $cus)
                                                        <option value="{{ $cus->id }}" class="font-weight-bold">{{ $cus->phone }} - {{ $cus->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="new_customer" style="display: none">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Name</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input name="name" class="form-control bold customer_name" value="{{ old('name') }}" placeholder="Customer Name" />
                                                            <span class="input-group-addon"><strong><i class="fa fa-file-text-o"></i></strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Email</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input name="email" type="email" class="form-control bold" value="{{ old('email') }}" placeholder="Customer Email (optional)"/>
                                                            <span class="input-group-addon"><strong><i class="fa fa-envelope"></i></strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Phone</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input name="phone" class="form-control bold customer_phone" value="{{ old('phone') }}" placeholder="Customer Phone" />
                                                            <span class="input-group-addon"><strong><i class="fa fa-phone"></i></strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Address </strong></label>
                                                    <div class="col-md-12">
                                                        <textarea name="address" id="" cols="30" rows="1" class="form-control input-lg customer_address" placeholder="Customer Address" >{{ old('address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Select Machine</strong></label>
                                        <div class="col-md-12">
                                            <div class="machine-list">
                                                <div class="images_list">
                                                    @foreach($machine as $m)
                                                        <li class="border" title="{{$m->name}}" data-id="{{$m->id}}" data-rate="{{$m->product->sell_price}}">
                                                            <div style="background-color: green;color: white;margin-top: 10px;">
                                                                <h4>{{$m->name}}</h4>
                                                                <h4>Fuel: {{$m->product->name}}</h4>
                                                                <h4>Reading: {{$m->current_reading}}</h4>
                                                            </div>
                                                            <span ><i class="fa fa-check"></i></span>
                                                        </li>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="machine_id" id="machine_id" value="0">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Quantity</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="quantity" id="quantity" class="form-control input-lg font-weight-bold" value="{{ old('quantity') }}" placeholder="Quantity"  readonly/>
                                                        <span class="input-group-addon"><strong><i class="fa fa-sort-numeric-asc"></i></strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Rate Price</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="rate" id="rate" type="text" class="form-control input-lg font-weight-bold" value="{{ old('rate') }}" placeholder="Rate" {{ getLoginRole() === 'Seller' ? 'readonly' : '' }} required/>
                                                        <span class="input-group-addon"><strong>{{$basic->currency}}</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">{{ getLoginRole() !== 'Seller' ? 'Total' : '' }} Price</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="total" id="total" type="text" class="form-control input-lg font-weight-bold" value="{{ old('total') }}" placeholder="{{ getLoginRole() !== 'Seller' ? 'Total' : '' }} Price" readonly required/>
                                                        <span class="input-group-addon"><strong>{{$basic->currency}}</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    @if (getLoginRole() !== 'Seller')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Total Price</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                            <input name="order_subtotal" id="order_subtotal" class="form-control bold order_subtotal" value="" readonly placeholder="Product Price" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">DISCOUNT - LESS</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                            <input name="discount" type="number" id="discount" class="form-control bold discount" value="" placeholder="DISCOUNT - LESS" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Gross Price</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                            <input name="order_total" id="order_total" class="form-control bold order_total" value="" placeholder="Final Price" readonly required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Payment Type</strong></label>
                                                    <div class="col-md-12">
                                                        <select name="payment_type" id="payment_type" class="form-control font-weight-bold text-uppercase" required>
                                                            <option value="" class="font-weight-bold text-uppercase">Select Type</option>
                                                            <option value="0" class="font-weight-bold text-uppercase">On Paid</option>
                                                            <option value="1" class="font-weight-bold text-uppercase">Due Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Payment With</strong></label>
                                                    <div class="col-md-12">
                                                        <select name="payment_with" id="payment_with" class="form-control font-weight-bold text-uppercase" required>
                                                            <option value="0" class="font-weight-bold text-uppercase">Cash Payment</option>
                                                            <option value="1" class="font-weight-bold text-uppercase">Cheque Payment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="on_paid" style="display: none">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Total Price</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                        <input name="on_total_price" class="form-control bold order_total" value="" readonly placeholder="Total Price" />
                                                        <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="due_paid" style="display: none">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Pay Amount</strong></label>
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                                <input name="due_pay_amount" class="form-control bold pay_amount" value="" placeholder="Pay Amount" />
                                                                <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Due Amount</strong></label>
                                                        <div class="col-md-12">

                                                            <div class="input-group">
                                                                <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                                <input name="due_due_amount" class="form-control bold due_amount" value="" placeholder="Due Amount" readonly />
                                                                <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Repayment Date</strong></label>
                                                        <div class="col-md-12">
                                                            <div class='input-group'>
                                                                <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                                <input type='text' name="due_payment_date" class="form-control font-weight-bold" id='datetimepicker2' value="{{ \Carbon\Carbon::now()->addDays('7')->format('Y-m-d') }}" />
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg text-uppercase" id="sellFuelSubmit" disabled><i class="fa fa-send"></i> Sell Fuel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
@endsection
@section('vendors')
@endsection
@section('scripts')
    <script src="{{asset('assets/admin/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/picker-date-time.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
            $('.select2,.select24').select2({ width: '100%' });
            $('.select29').select2({allowClear: true,width:'100%'});
        });

        $('.images_list li').click(function() {
            var id = $(this).data('id');
            var rate = $(this).data('rate');
            $('.images_list .selected').removeClass('selected');
            $(this).toggleClass('selected');
            $("#machine_id").val(id);
            $("#rate").val(rate);
            var qty = $('#quantity');
            var total = $('#total');
            qty.attr('readonly',false)
            total.attr('readonly',false)
            qty.val('');
            total.val('');
            setOrderSubtotal(0);
        });


        $('#rate').on('input',function (e) {
            var qty = $('#quantity').val();
            var rate = $(this).val();
            var total = qty * rate;
            $('#total').val(total.toFixed(3));
            setOrderSubtotal(total);
        });

        $('#total').on('input',function (e) {
            var rate = $('#rate').val();
            var total = $(this).val();
            var qty = total / rate;
            var machine_id = $('#machine_id').val();
            if (machine_id === "0"){
                toastr.error('Select the machine first')
            }else{
                var url = '{{ url('/admin') }}';
                $.get(url + '/check-fuel-qty/' + machine_id+'/'+qty,function (data) {
                    var result = $.parseJSON(data);
                    if (result['errorStatus'] == "yes") {
                        toastr.error(result['errorDetails']);
                        document.getElementById("sellFuelSubmit").disabled = true;
                    }else {
                        document.getElementById("sellFuelSubmit").disabled = false;
                    }
                })
            }
            $('#quantity').val(qty.toFixed(3));
            var qt = $('#quantity').val();
            setOrderSubtotal(Math.round(rate*qt));
        });

        $('#quantity').on('input',function (e) {
            var rate = $('#rate').val();
            var qty = $(this).val();
            var machine_id = $('#machine_id').val();
            if (machine_id === "0"){
                toastr.error('Select the machine first')
            }else{
                var url = '{{ url('/admin') }}';
                $.get(url + '/check-fuel-qty/' + machine_id+'/'+qty,function (data) {
                    var result = $.parseJSON(data);
                    if (result['errorStatus'] == "yes") {
                        toastr.error(result['errorDetails']);
                        document.getElementById("sellFuelSubmit").disabled = true;
                    }else {
                        document.getElementById("sellFuelSubmit").disabled = false;
                    }
                })
            }
            var total = qty * rate;
            $('#total').val(total.toFixed(3));
            setOrderSubtotal(total);
        });

        function setOrderSubtotal(total){
            $('.order_subtotal').val(total.toFixed(3));
            $('.order_total').val(total.toFixed(3));
            $('.due_amount').val(total.toFixed(3));
            $('.discount').val('');
            $('.pay_amount').val('');
        }

        $('#customer_type').on('change',function (e) {
            var customer_type = e.target.value;
            if(customer_type == 2){
                document.getElementById("new_customer").style.display = 'none';
                document.getElementById("old_customer").style.display = 'none';
                $('.customer_name').removeAttr('required');
                $('.customer_phone').removeAttr('required');
                $('.customer_address').removeAttr('required');
                $('.select_customer').removeAttr('required');
            } else if(customer_type == 0){
                document.getElementById("new_customer").style.display = 'block';
                document.getElementById("old_customer").style.display = 'none';
                $('.customer_name').attr('required', 'required');
                $('.customer_phone').attr('required', 'required');
                $('.customer_address').attr('required', 'required');
                $('.select_customer').removeAttr('required');
            }else{
                document.getElementById("new_customer").style.display = 'none';
                document.getElementById("old_customer").style.display = 'block';
                $('.customer_name').removeAttr('required');
                $('.customer_phone').removeAttr('required');
                $('.customer_address').removeAttr('required');
                $('.select_customer').attr('required','required');
            }
        });

        $('#payment_type').on('change',function (e) {
            var payment_type = e.target.value;
            if(payment_type == 0){
                document.getElementById("on_paid").style.display = 'block';
                document.getElementById("due_paid").style.display = 'none';
                $('.pay_amount').removeAttr('required');
            }else if (payment_type == 1){
                document.getElementById("on_paid").style.display = 'none';
                document.getElementById("due_paid").style.display = 'block';
                $('.pay_amount').attr('required','required');
            }
        });

        $('#discount').on('input',function (e) {
            var productPrice = $('#order_subtotal').val();
            var discount = $('#discount').val();
            if (discount == '' ){
                $('.order_subtotal').val(productPrice);
                $('.order_total').val(Math.round(productPrice));
                $('.due_amount').val(dueamount());
            }else if (discount == 0){
                $('.order_subtotal').val(productPrice);
                $('.order_total').val(Math.round(productPrice));
                $('.due_amount').val(dueamount());
            }else {
                var subtotal = productPrice - discount;
                $('.order_subtotal').val(productPrice);
                $('.order_total').val(Math.round(subtotal));
                $('.due_amount').val(dueamount());
            }
        });

        $('.pay_amount').on('input',function (e) {
            var pay_amount = $('.pay_amount').val();
            var total = $('.order_total').val();
            var due = total - pay_amount;
            $('.due_amount').val(Math.round(due));
        });

        function dueamount() {
            var total = $('.order_total').val();
            var pay = $('.pay_amount').val();
            var due = total - pay;
            return Math.round(due);
        }
    </script>
@endsection
