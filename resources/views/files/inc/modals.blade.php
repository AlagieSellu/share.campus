<!-- Modal -->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">
                    Edit <span class="text-primary">{{ $file->name }}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('files.update', $file->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <strong>Rename</strong>
                        <input class="form-control{{ $errors->has('rename') ? ' is-invalid' : '' }}" required type="text" name="rename" value="{{ $file->name }}">
                        @if ($errors->has('rename'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('rename') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="upload_button">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewShareModal" tabindex="-1" role="dialog" aria-labelledby="viewShareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewShareModalLabel">
                    <i class="fa fa-share-alt"></i> <strong class="text-primary">{{ $file->name }}</strong> Shared With
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive table-hover">
                    <table class="table">
                        <tbody>
                        @foreach($file->shares as $share)
                            <tr>
                                <td>{{ $share->user->name }}</td>
                                <td>{{ $share->user->email }}</td>
                                <td><a href="{{ route('shares.destroy', $share->id) }}"><i class="fa fa-times text-danger"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-trash"></i> Delete <strong class="text-primary">{{ $file->name }}</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <a class="btn btn-outline-danger btn-lg btn-block" href="{{ route('files.destroy', $file->id) }}">Delete Folder</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->