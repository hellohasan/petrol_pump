@extends('layouts.dashboard')
@section('style')
    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap-datetimepicker.css') }}">
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

                            <form class="form-horizontal" method="post" role="form" >
                                {!! csrf_field() !!}

                                <div class="form-body">
                                    <div class="form-group row">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Customer Type</strong></label>
                                        <div class="col-md-12">
                                            <select name="customer_type" id="customer_type" data-placeholder="-- Customer Type --" class="form-control select2 font-weight-bold" required>
                                                <option value=""></option>
                                                <option value="1" class="font-weight-bold">Exist Customer</option>
                                                <option value="0" class="font-weight-bold">New Customer</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="old_customer" style="display: block;">
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

                                    <div class="repeater-default">
                                        <div class="row form-section1">
                                            <div class="col-md-6">
                                                <h4 class="">Sell Product Information</h4>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <div class="form-group">
                                                    <button id="addItem" class="btn btn-primary btn-sm"><i class="ft-plus" style="font-size: 12px;"></i>Add More Item</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label><strong class="font-weight-bold text-uppercase text-left ">Code - Product Name</strong></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label><strong class="font-weight-bold text-uppercase text-left ">Rate</strong></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label><strong class="font-weight-bold text-uppercase text-left ">Quantity</strong></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label><strong class="font-weight-bold text-uppercase text-left ">Subtotal</strong></label>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <label><strong class="font-weight-bold text-uppercase text-left ">Action</strong></label>
                                                </div>
                                            </div>


                                            <div class="row sellItem" id="itemBox">

                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><strong><i class="fa fa-list-ul"></i></strong></span>
                                                                <select name="product_id[]" id="product_id" data-placeholder="-- Select Product Code or Name --" class="form-control product_id select29 font-weight-bold" required>
                                                                    <option class="font-weight-bold" value="">Select Product</option>
                                                                    @foreach($product as $pro)
                                                                        <option value="{{ $pro->id }}" class="font-weight-bold">{{ $pro->code }} - {{ $pro->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><strong>{{ $basic->symbol }}</strong></span>
                                                                <input name="rate[]" class="form-control bold price" value="" placeholder="Rate" required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input name="quantity[]" class="form-control bold quantity" value="" placeholder="Quantity" required/>
                                                                <span class="input-group-addon"><strong class="unit">Pcs</strong></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><strong>{{ $basic->symbol }}</strong></span>
                                                                <input name="subtotal[]" class="form-control bold subtotal" value="" placeholder="Subtotal" readonly required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group row text-center">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-danger removeItem" id="removeItem"> <i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Product Price</strong></label>
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
                                                        <input name="discount" id="discount" class="form-control bold discount" value="" placeholder="DISCOUNT - LESS" />
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
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Total Price</strong></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><strong>{{$basic->symbol}}</strong></span>
                                                            <input name="due_total_price" class="form-control bold order_total" value="" readonly placeholder="Total Price" />
                                                            <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
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

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg text-uppercase"><i class="fa fa-send"></i> Sell Product</button>
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
            $('.select2,.select24').select2();
            $('.select29').select2({allowClear: true});
        });

        $('#customer_type').on('change',function (e) {
            var customer_type = e.target.value;
            if(customer_type == 0){
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

        $("#addItem").click(function(){

            $('.select29').select2("destroy");
            $("#itemBox:last").clone(true).insertAfter("div.sellItem:last").fadeIn(50);
            $("div.sellItem:last input").val('');
            $("div.sellItem:last input").val('');
            $("div.sellItem:last input").val('');
            $("div.sellItem:last input").val('');
            $('.select29').select2();
            $("div.sellItem .removeItem").prop('disabled', false);
            return false;
        });

        $(document).on("click" , "#removeItem" , function()  {

            var itemRowQty = $('.sellItem').length;

            if (itemRowQty == 1){
                $("div.sellItem .removeItem").prop('disabled', true);
                return false;
            }else{
                if (confirm('Are you sure you want to remove this item?')) {
                    $(this).closest('.sellItem').remove().fadeOut(50);

                    $('.order_subtotal').val(productPrice());
                    $('.order_total').val(subTotal());
                    $('.due_amount').val(dueamount());

                }
                if(itemRowQty==1){
                    $("div.sellItem .removeItem").prop('disabled', true);
                    return false
                }else{
                    $("div.sellItem .removeItem").prop('disabled', false);
                }
                return false;
            }
        });

        $('.sellItem').delegate('.product_id','change',function(e) {

            var itemDiv = $(this).parent().parent().parent().parent().parent();

            var code = $(this).closest('.sellItem').find('.product_id').val();

            var url = '{{ url('/') }}';
            $.get(url + '/check-product-code?code=' + code,function (data) {

                var result = $.parseJSON(data);
                if (result['errorStatus'] == "yes"){
                    toastr.error(result['errorDetails']);
                    itemDiv.find('.price').val("");
                    itemDiv.find('.quantity').val("");
                    itemDiv.find('.subtotal').val("");
                    itemDiv.find('.unit').text("Pcs");

                    $('.order_subtotal').val(productPrice());
                    $('.order_total').val(subTotal());
                    $('.due_amount').val(dueamount());

                }else{
                    /*toastr.info(result['errorDetails']);*/
                    itemDiv.find('.price').val(result.price);
                    itemDiv.find('.unit').text(result.unit);

                    $('.order_subtotal').val(productPrice());
                    $('.order_total').val(subTotal());
                    $('.due_amount').val(dueamount());
                }
            })

        });

        $('.quantity,.price').on('blur',function (e) {
            var itemDiv = $(this).parent().parent().parent().parent().parent();
            var qty = itemDiv.find('.quantity').val();
            var product_id = itemDiv.find('.product_id').val();

            var url = '{{ url('/') }}';
            $.get(url + '/check-product-store?quantity=' + qty+'&product_id='+product_id,function (data) {

                var result = $.parseJSON(data);
                if (result['errorStatus'] == "yes"){
                    toastr.error(result['errorDetails']);

                    itemDiv.find('.subtotal').val("");
                    itemDiv.find('.quantity').val("");

                    $('.order_subtotal').val(productPrice());
                    $('.order_total').val(subTotal());
                    $('.due_amount').val(dueamount());

                }else{
                    toastr.info(result['errorDetails']);
                    var rate = itemDiv.find('.price').val();
                    var total = rate * qty ;
                    itemDiv.find('.subtotal').val(parseFloat(total).toFixed(2));
                    $('.order_subtotal').val(productPrice());
                    $('.order_total').val(subTotal());
                    $('.due_amount').val(dueamount());
                }
            });
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

        function dueamount(total, pay) {
            var total = $('.order_total').val();
            var pay = $('.pay_amount').val();
            var due = total - pay;
            return Math.round(due);
        }

        function productPrice(tPrice) {
            var tPrice = 0;
            $('.subtotal').each(function(i,e){
                var price = $(this).val()-0;
                tPrice += price;
            });
            return parseFloat(tPrice).toFixed(2);
        }

        function subTotal() {
            var productPrice = Math.round($('#order_subtotal').val());
            var discount = $('#discount').val();
            if (discount == '' ){
                return productPrice;
            }else if (discount == 0){
                return productPrice;
            }else {
                var subtotal = productPrice - discount;
                return Math.round(subtotal);
            }
        }
    </script>
@endsection
