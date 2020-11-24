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


                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Date TIme</th>
                                    <th>Invoice</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>P. Amount</th>
                                </tr>
                                </thead>

                                {{--<tbody>
                                @foreach($log as $k => $p)
                                    <tr>
                                        <td>{{ ++$k }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->created_at)->format('dS m,y h:i A') }}</td>
                                        <td>{{ $p->custom }}</td>

                                        <td>
                                            @if($p->type == 1)
                                                <div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-briefcase font-medium-2"></i>
                                                    <span>Company Payment</span>
                                                </div>
                                            @elseif($p->type == 2)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-briefcase font-medium-2"></i>
                                                    <span>Re Company Payment</span>
                                                </div>
                                            @elseif($p->type == 3)
                                                <div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-download-cloud font-medium-2"></i>
                                                    <span>Deposit Amount</span>
                                                </div>
                                            @elseif($p->type == 4)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-download-cloud font-medium-2"></i>
                                                    <span>Re Deposit Amount</span>
                                                </div>
                                            @elseif($p->type == 5)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-upload-cloud font-medium-2"></i>
                                                    <span>Withdraw Amount</span>
                                                </div>
                                            @elseif($p->type == 6)
                                                <div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-upload-cloud font-medium-2"></i>
                                                    <span>Re Withdraw Amount</span>
                                                </div>
                                            @elseif($p->type == 7)
                                                <div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-cloud-lightning font-medium-2"></i>
                                                    <span>Expense Amount</span>
                                                </div>
                                            @elseif($p->type == 8)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-cloud-lightning font-medium-2"></i>
                                                    <span>Re Expense Amount</span>
                                                </div>
                                            @elseif($p->type == 9)
                                                <div class="badge badge-success font-weight-bold text-uppercase">
                                                    <i class="ft ft-shopping-cart font-medium-2"></i>
                                                    <span>Sell Price</span>
                                                </div>
                                            @elseif($p->type == 10)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-shopping-cart font-medium-2"></i>
                                                    <span>Re Sell Price</span>
                                                </div>
                                            @elseif($p->type == 11)
                                                <div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-copy font-medium-2"></i>
                                                    <span>Due Repayment</span>
                                                </div>
                                            @elseif($p->type == 12)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-copy font-medium-2"></i>
                                                    <span>Re Due Repayment</span>
                                                </div>
                                            @elseif($p->type == 13)
                                                <div class="badge badge-primary font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Instalment Repayment</span>
                                                </div>
                                            @elseif($p->type == 14)
                                                <div class="badge badge-warning font-weight-bold text-uppercase">
                                                    <i class="ft ft-sliders font-medium-2"></i>
                                                    <span>Re Instalment Repayment</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <b>({{ $p->status == 1 ? '-' : '+' }}) {{ $basic->symbol }} {{ $p->balance }}</b>
                                        </td>
                                        <td>
                                            <b>{{ $basic->symbol }} {{ $p->post_balance }}</b>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>--}}
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/datatables.min.js') }}"></script>
    {{--<script src="{{ asset('assets/admin/js/datatable-basic.js') }}"></script>--}}
    <script>
        $(function() {
            $('#table').DataTable({
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                processing: true,
                serverSide: true,
                ajax: '{{ route('transaction-log') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'custom', name: 'custom' },
                    { data: 'type', name: 'type' },
                    { data: 'balance', name: 'balance' },
                    { data: 'post_balance', name: 'post_balance' },

                ],
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(4)').html('{{$basic->symbol}}'+data.balance);
                    $( row ).find('td:eq(5)').html('{{$basic->symbol}}'+data.post_balance);
                    if (data.status) {
                        $( row ).find('td:eq(4)').html('(-) '+'{{$basic->symbol}}'+data.balance);
                    }else{
                        $( row ).find('td:eq(4)').html('(+) '+'{{$basic->symbol}}'+data.balance);
                    }
                }

            });
        });
    </script>
@endsection
