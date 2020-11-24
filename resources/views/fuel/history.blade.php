@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap.min.css">
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
                                    <table class="table table-striped table-bordered table-hover zero-configuration nowrap" width="100%"  id="table">
                                        <thead>
                                        <tr>
                                            <th>ID#</th>
                                            <th>Sell Date</th>
                                            <th>Invoice</th>
                                            <th>Customer Details</th>
                                            {{--<th>Subtotal</th>
                                            <th>Discount</th>--}}
                                            <th>Total</th>
                                            <th>Pay</th>
                                            <th>Due?</th>
                                            @role('Super Admin')
                                                <th>SellBy</th>
                                            @endrole
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
                <div class="modal-header bg-warning white">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i><strong> Confirmation !</strong> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <strong>Are you sure you want to do this ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('sell-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="delete_id" id="delete_id" value="0">
                        <button type="button" class="btn btn-warning font-weight-bold text-uppercase" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>&nbsp;
                        <button type="submit" class="btn btn-danger font-weight-bold text-uppercase"><i class="fa fa-check"></i> Yes Sure</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/datatables.min.js') }}"></script>
    {{--<script src="{{ asset('assets/admin/js/datatable-basic.js') }}"></script>--}}
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('fuel.sell.history') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'custom', name: 'custom' },
                    { data: 'customer_id', name: 'customer_id' },
                    /*{ data: 'order_subtotal', name: 'order_subtotal' },
                    { data: 'discount', name: 'discount' },*/
                    { data: 'order_total', name: 'order_total' },
                    { data: 'pay_amount', name: 'pay_amount' },
                    { data: 'payment_type', name: 'payment_type' },
                    @role('Super Admin')
                    { data: 'user_id', name: 'user_id' },
                    @endrole
                    { data: 'action', name: 'action' },
                ],
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(4)').html('{{$basic->symbol}}'+data.order_total);
                    $( row ).find('td:eq(5)').html('{{$basic->symbol}}'+data.pay_amount);
                    if (/^-?\d+$/.test( data.payment_type )) {
                        $( row ).find('td:eq(6)').html('{{$basic->symbol}}'+data.due_amount);
                    }
                }
            });

            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $("#delete_id").val(id);
            });
        });
    </script>
@endsection
