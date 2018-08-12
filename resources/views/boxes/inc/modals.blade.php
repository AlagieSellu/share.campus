<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-trash"></i> Delete <strong class="text-primary">{{ $box->name }}</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <a class="btn btn-outline-danger btn-lg btn-block" href="{{ route('boxes.destroy', $box->id) }}">Delete Folder</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->