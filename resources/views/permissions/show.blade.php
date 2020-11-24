@extends('layouts.dashboard')

@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <a href="{{ route('permissions.index') }}" class="btn btn-primary btn-sm float-right"><i class="fa fa-list"></i> Permission List</a>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permission Name:</strong>
                                        {{ $permission->name }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permission Users ({{$users->count()}}) :</strong>
                                        @if(!empty($users))
                                            @foreach($users as $key => $v)
                                                <br><label class="label label-success">({{++$key}}) {{ $v->name }}({{$v->email}})</label>
                                            @endforeach
                                        @else
                                            <br><label class="label label-success">No User Set yet.</label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

@endsection
