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

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Created Date</th>
                                        <th>Customer Details</th>
                                        <th>Total</th>
                                        <th>Pay</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($customer as $k => $p)
                                        <tr>
                                            <td>{{ ++$k }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('dS M,y h:i A') }}</td>
                                            <td>{{ $p->name }}
                                                @if ($p->id != 1)
                                                <br>
                                                @if($p->email != null)
                                                    {{ $p->email }}
                                                    <br>
                                                @endif
                                                {{ $p->phone }}<br>
                                                {{ $p->address }}
                                                @endif
                                            </td>
                                            <td>{{$basic->symbol}}{{$p->total_amount}}</td>
                                            <td>{{$basic->symbol}}{{$p->pay_amount}}</td>
                                            <td>{{$basic->symbol}}{{$p->total_amount - $p->pay_amount}}</td>
                                            <td>
                                                <a href="{{ route('customer-view',$p->id) }}" class="btn btn-primary btn-sm font-weight-bold text-uppercase" title="View"><i class="fa fa-file-text-o"></i> invoice</a>
                                                @if ($p->id != 1)
                                                    <a href="{{ route('customer-edit',$p->id) }}" class="btn btn-warning btn-sm font-weight-bold text-uppercase" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                @endif
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
    </section><!---ROW-->



@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.js') }}"></script>
@endsection
