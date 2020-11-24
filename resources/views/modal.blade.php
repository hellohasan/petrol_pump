

<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header bg-warning white">
                <h4 class="modal-title text-uppercase font-weight-bold" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i> Confirmation !</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-uppercase">
                <strong>Are you sure you want to Delete ?</strong>
            </div>
            <div class="modal-footer">
                <form method="post" action="{{--{{ route('product-delete') }}--}}" class="form-inline">
                    {!! csrf_field() !!}
                    {!! method_field('delete') !!}
                    <input type="hidden" name="delete_id" id="delete_id" class="delete_id" value="0">
                    <button type="button" class="btn btn-default font-weight-bold text-uppercase" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger font-weight-bold text-uppercase deleteButton"><i class="fa fa-check"></i> Yes Sure.!</button>
                </form>
            </div>

        </div>
    </div>
</div>

<button type="button" title="Delete" class="btn btn-sm btn-danger font-weight-bold text-uppercase delete_button"
        data-toggle="modal" data-target="#DelModal"
        data-id="{{ $p->id }}">
    <i class='fa fa-trash'></i> delete
</button>


<script>
    $(document).ready(function (e) {
        $(document).on("click", '.delete_button', function (e) {
            var id = $(this).data('id');
            $(".delete_id").val(id);
        });
    });
</script>