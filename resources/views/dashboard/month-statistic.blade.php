@extends('layouts.dashboard')
@section('style')

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
                                    <table class="table table-striped table-bordered table-hover zero-configuration1">
                                        <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Invoice</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            @role('Super Admin')
                                            <th>Profit</th>
                                            @endrole
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($items->reverse() as $k => $p)

                                            @php $buy = DB::table('order_items')
                                                        ->where('created_at','like',$k.'%')
                                                        ->sum(DB::raw('(sell_price * quantity) - (buy_price * quantity)'));
                                            @endphp
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($k)->format('F Y') }}</td>
                                                <td>{{ $p['total_invoice'] }}'s</td>
                                                <td>{{$basic->symbol}} {{ $p['total_order'] }}</td>
                                                <td>{{$basic->symbol}} {{ $p['total_pay'] }}</td>
                                                <td>{{$basic->symbol}} {{ $p['total_due'] }}</td>
                                                @role('Super Admin')
                                                <td>{{$basic->symbol}} {{ $buy }}</td>
                                                @endrole
                                                <td>
                                                    <a href="{{ route('sell-list',$k) }}" class="btn btn-primary btn-sm  font-weight-bold text-uppercase" title="Sell History"><i class="fa fa-history"></i> Sell History</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    {!! $items->render('basic.pagination') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->


@endsection
@section('scripts')

@endsection
