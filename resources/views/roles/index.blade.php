@extends('layouts.dashboard')
@section('content')

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        {{--<div class="heading-elements">
                            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Create New Role</a>
                        </div>--}}
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        {{--<th>Actions</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody id="products-list">
                                    @foreach ($roles as $key => $role)
                                        <tr id="product{{$role->id}}">
                                            <td>{{$role->id}}</td>
                                            <td>{{$role->name}}</td>
                                            {{--<td>
                                                @if ($role->id != 1)
                                                    <a class="btn btn-info btn-sm bold uppercase" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye"></i> Show</a>
                                                    <a class="btn btn-primary btn-sm bold uppercase" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                                    {!! Form::button('<i class="fa fa-trash"></i> Delete', ['class' => 'btn btn-danger btn-sm bold uppercase delete_button','data-toggle'=>"modal",'data-target'=>"#DelModal",'data-id'=>$role->id]) !!}
                                                @endif
                                            </td>--}}
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

    @include('__deleteModal',['route'=>route('roles.destroy', $role->id)])
@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $("#delete_id").val(id);
            });
        });
    </script>
@stop
