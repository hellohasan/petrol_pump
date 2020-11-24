@extends('layouts.dashboard')
@section('style')
    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
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

                            <form action="{{ route('machine.update',$machine->id) }}" class="form-horizontal" method="post" role="form">
                                @method('put')
                                {!! csrf_field() !!}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Machine Name</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="name" class="form-control bold" value="{{ $machine['name'] }}" placeholder="Machine Name" required/>
                                                        <span class="input-group-addon"><strong><i class="fa fa-file-text-o"></i></strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Machine Code</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="code" class="form-control bold" value="{{ $machine['code'] }}" placeholder="Machine Code" required/>
                                                        <span class="input-group-addon"><strong><i class="fa fa-code"></i></strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Supply Fuel</strong></label>
                                                <div class="col-md-12">
                                                    <select name="product_id" id="product_id" required class="form-control select2 font-weight-bold">
                                                        @foreach($products as $c)
                                                            <option value="{{ $c->id }}" {{ $c->id === $machine->product_id ? 'selected' : '' }} class="font-weight-bold">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-md-12"><strong style="text-transform: uppercase;">Current Reading</strong></label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input name="current_reading" class="form-control bold" value="{{ $machine{'current_reading'} }}" placeholder="Machine Current Reading" required/>
                                                        <span class="input-group-addon"><strong><i class="fa fa-code"></i></strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> Update Machine</button>
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
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>


@endsection
