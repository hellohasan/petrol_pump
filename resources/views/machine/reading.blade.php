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
                        <h4 class="card-title" id="horz-layout-basic">
                            <a href="{{ route('machine.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-list-alt"></i> Machine List</a>
                        </h4>
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
                                        <th>Machine Details</th>
                                        <th>Fuel</th>
                                        <th>Start Reading</th>
                                        <th>End Reading</th>
                                        <th>Difference</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($lists as $k => $p)
                                        <tr>
                                            <td>{{ ++$k }}</td>
                                            <td>{{ $p->machine->name }} - ({{ $p->machine->code }})</td>
                                            <td>{{ $p->machine->product->name }}</td>
                                            <td>{{ $p->start_reading }} == {{ \Carbon\Carbon::parse($p->created_at)->format('d-m-y h:i A') }}</td>
                                            <td>
                                                @if ($p->end_reading)
                                                    {{ $p->end_reading }} == {{ \Carbon\Carbon::parse($p->created_at)->format('d-m-y h:i A') }}
                                                @else
                                                    <div class="badge badge-warning font-weight-bold text-uppercase"><i class="fa fa-spinner"></i> Still Running</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($p->end_reading)
                                                    {{ $p->end_reading - $p->start_reading }}
                                                @else
                                                    <div class="badge badge-warning font-weight-bold text-uppercase"><i class="fa fa-spinner"></i> Still Running</div>
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
