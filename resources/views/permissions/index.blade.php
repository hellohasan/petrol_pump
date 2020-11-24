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
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Create New Permission</a>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="products-list">
                                    @foreach ($permissions as $key => $permission)
                                        <tr id="product{{$permission->id}}">
                                            <td>{{$permission->id}}</td>
                                            <td>{{$permission->name}}</td>
                                            <td>
                                                <a class="btn btn-info btn-xs bold uppercase" href="{{ route('permissions.show',$permission->id) }}"><i class="fa fa-eye"></i> Show</a>
                                                @role('Super Admin')
                                                <a class="btn btn-primary btn-xs bold uppercase" href="{{ route('permissions.edit',$permission->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                                {!! Form::button('<i class="fa fa-trash"></i> Delete', ['class' => 'btn btn-danger btn-xs bold uppercase delete_button','data-toggle'=>"modal",'data-target'=>"#DelModal",'data-id'=>$permission->id]) !!}
                                                @endrole
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

    @include('__deleteModal',['route'=>route('permissions.destroy', 0)])
@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                var url = '{{ route("permissions.destroy",":id") }}';
                url = url.replace(':id',id);
                $("#deleteForm").attr("action",url);
                $("#delete_id").val(id);
            });
        });
    </script>
@stop
