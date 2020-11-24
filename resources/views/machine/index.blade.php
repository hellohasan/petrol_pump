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
                            <a href="{{ route('machine.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Machine</a>
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
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Fuel</th>
                                        <th>Reading</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($machine as $k => $p)
                                        <tr>
                                            <td>{{ ++$k }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->code }}</td>
                                            <td>{{ $p->product->name }}</td>
                                            <td>{{ $p->current_reading }}</td>
                                            <td>
                                                @if ($p->activated)
                                                    <button type="button"
                                                            class="btn btn-sm btn-success font-weight-bold text-uppercase delete_button cursor-pointer"
                                                            data-toggle="modal" data-target="#DelModal"
                                                            data-id="{{ $p->id }}"
                                                            data-reading="{{ $p->current_reading }}"
                                                            title="Click to stop machine"
                                                            data-name="{{$p->name}}"
                                                            data-type="0"
                                                            data-title="Stop Machine">
                                                        <i class='fa fa-gear'></i> Running
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning font-weight-bold text-uppercase delete_button cursor-pointer"
                                                            data-toggle="modal" data-target="#DelModal"
                                                            data-id="{{ $p->id }}"
                                                            data-reading="{{ $p->current_reading }}"
                                                            title="Click to Run machine"
                                                            data-name="{{$p->name}}"
                                                            data-type="1"
                                                            data-title="Run Machine">
                                                        <i class='fa fa-close'></i> Stop
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('machine.edit',$p->id) }}" class="btn btn-warning btn-sm font-weight-bold text-uppercase" title="Edit"><i class="fa fa-edit"></i> Edit</a>
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

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-gear'></i> <span class="title"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('machine.activation') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="machine_id" value="0">
                        <input type="hidden" name="type" id="type" value="0">
                        <div class="form-group">
                            <label for="current_reading" class="font-weight-bold text-uppercase">Current reading</label>
                            <input type="text" name="current_reading" id="current_reading" value="{{old('current_reading')}}" class="form-control" placeholder="Current reading" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block text-uppercase font-weight-bold"><i class="fa fa-send"></i> <span class="title"></span></button>
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
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                var reading = $(this).data('reading');
                var title = $(this).data('title');
                var name = $(this).data('name');
                var type = $(this).data('type');
                $("#machine_id").val(id);
                $("#type").val(type);
                $("#current_reading").val(reading);
                $(".title").text(title+' - '+name);
            });
        });
    </script>


@endsection
