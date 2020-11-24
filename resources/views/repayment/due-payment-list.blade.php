@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/datatables.min.css') }}">
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

                            <div class="row">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Sell Date</th>
                                        <th>Sell Number</th>
                                        <th>Customer Details</th>
                                        <th>Repayment Date</th>
                                        <th>Total</th>
                                        <th>Pay</th>
                                        <th>Due/Paid</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($sell as $k => $p)
                                        <tr class="{{$p->deleted_at != null ? 'bg-warning white' : ''}}">
                                            <td>{{ ++$k }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('dS M,Y h:i A') }}</td>
                                            <td>{{ $p->custom }}</td>
                                            <td>{{ $p->customer->name }} <br>{{ $p->customer->phone }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->due_repayment_date)->format('dS M,y') }}</td>
                                            <td>{{ $basic->symbol }}{{ $p->order_total }}</td>
                                            <td>{{ $basic->symbol }}{{ $p->pay_amount }}</td>
                                            <td>
                                                @if($p->payment_type == 0)
                                                    <div class="badge badge-primary font-weight-bold text-uppercase">Paid</div>
                                                @else
                                                    {{ $basic->symbol }}{{ $p->due_amount }}
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button"  class="btn btn-primary btn-sm btn-detail open_modal font-weight-bold text-uppercase" value="{{ $p->id }}"><i class="fa fa-credit-card"></i> Payment Now</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header bg-gradient-radial-primary white">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-credit-card"></i> Repayment Due</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmProducts" action="{{ route('submit-due-repayment') }}" method="post" name="frmProducts" class="form-horizontal">
                        {!! csrf_field() !!}
                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Payment Date</strong></label>
                            <div class="col-md-12">
                                <div class='input-group'>
                                    <input type='text' name="payment_date" class="form-control font-weight-bold" id='datetimepicker1' value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}" />
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Invoice Number</strong></label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input name="custom" id="custom" class="form-control font-weight-bold" readonly value="" placeholder="Invoice Number"/>
                                    <span class="input-group-addon"><strong><i class="fa fa-tasks"></i></strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Total DUE</strong></label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><strong>{{ $basic->symbol }}</strong></span>
                                    <input name="total_due" id="total_due" class="form-control font-weight-bold" readonly value="" placeholder="Total Amount"/>
                                    <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Pay Amount</strong></label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><strong>{{ $basic->symbol }}</strong></span>
                                    <input type="number" name="pay_amount" id="pay_amount" class="form-control font-weight-bold" value="" placeholder="Pay Amount" required/>
                                    <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Due Amount</strong></label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><strong>{{ $basic->symbol }}</strong></span>
                                    <input name="due_amount" id="due_amount" class="form-control font-weight-bold" value="" placeholder="Due Amount" readonly/>
                                    <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Payment Status</strong></label>
                            <div class="col-md-12">
                                <select name="payment_status" id="payment_status" class="form-control font-weight-bold" required >
                                    <option value="" class="font-weight-bold">Select One</option>
                                    <option value="1" class="font-weight-bold">Full Paid</option>
                                    <option value="0" class="font-weight-bold">Due Paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" id="nextPayment" style="display: none">
                            <label class="col-md-12"><strong style="text-transform: uppercase;">Next Payment Date</strong></label>
                            <div class="col-md-12">
                                <div class='input-group'>
                                    <input type='text' name="due_payment_date" class="form-control font-weight-bold" id='datetimepicker2' value="{{ \Carbon\Carbon::now()->addDays('7')->format('Y-m-d H:i:s') }}" />
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold text-uppercase" id="btn-save" value="add"><i class="fa fa-send"></i> Submit Repayment</button>
                                <input type="hidden" id="order_id" name="order_id" value="0">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.js') }}"></script>

    <script>

        $(function () {
            $('.select2').select2();
        });
        var url = '{{ url('/admin/get-order-details') }}';
        $(document).on('click','.open_modal',function(){
            var order_id = $(this).val();

            $.get(url + '/' + order_id, function (data) {
                $('#order_id').val(data.id);
                $('#custom').val(data.custom);
                $('#total_due').val(data.due_amount);
                $('#due_amount').val(data.due_amount);
                $('#myModal').modal('show');
            })
        });
        $('#payment_status').on('change',function (e) {
            var status = e.target.value;
            if(status == 1){
                document.getElementById('nextPayment').style.display = 'none'
            }else{
                document.getElementById('nextPayment').style.display = 'block'
            }
        });
        $('#pay_amount').on('input',function (e) {
            var pay_amount = e.target.value;
            var total_due = $('#total_due').val();
            $('#due_amount').val(total_due - pay_amount);
        });


    </script>
@endsection
