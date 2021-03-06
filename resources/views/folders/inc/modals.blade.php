<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="uploadModalLabel">
                    Upload in <span class="text-primary">/{{ $folder->name }}</span>
                    <small>{{ $folder->user->available_storage() }} storage available</small>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('files.store') }}" id="upload_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div style="display:none;" id="error_msg" class="alert alert-danger"></div>
                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    <input type="hidden" id="selected" name="selected" value="{}">
                    <div class="form-group row">
                        <div class="col-12">
                            <input class="form-control" required type="file" multiple name="files[]" onchange="listFiles()" id="input_files">
                        </div>
                    </div>
                    <hr>
                    <strong id="files_size"></strong>
                    <div id="list_files"></div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default" onclick="resetFiles()">Reset</button>
                        <button type="submit" class="btn btn-primary" id="upload_button">Upload</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">
                    Edit <span class="text-primary">/{{ $folder->name }}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('folders.update', $folder->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <strong>Rename</strong>
                        <input class="form-control{{ $errors->has('rename') ? ' is-invalid' : '' }}" required type="text" name="rename" value="{{ $folder->name }}">
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

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="shareModalLabel">
                    <i class="fa fa-share-alt"></i> Share Selected Items With
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('shares.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    <input type="hidden" id="share_objects" name="objects" value="{}">
                    <div class="form-group input-group">
                        <input type="email" name="user_email" id="user_email" placeholder="User email" required
                               class="form-control{{ $errors->has('user_email') ? ' is-invalid' : '' }}" value="{{ old('user_email') }}">
                        <input class="btn btn-outline-primary form-control col-3" type="submit" value="Share">
                        @if ($errors->has('user_email'))
                            <span class="invalid-feedback">
                                    <strong>{{ $errors->first('user_email') }}</strong>
                                </span>
                        @endif
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
                    <i class="fa fa-share-alt"></i> <strong class="text-primary">{{ $folder->name() }}</strong> Shared With
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive table-hover">
                    <table class="table">
                        <tbody>
                        @foreach($folder->shares as $share)
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
                    <i class="fa fa-trash"></i> Delete <strong class="text-primary">{{ $folder->name() }}</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <a class="btn btn-outline-danger btn-lg btn-block" href="{{ route('folders.destroy', $folder->id) }}">Delete Folder</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="docModal" tabindex="-1" role="dialog" aria-labelledby="docModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="docModalLabel">
                    <i class="fa fa-newspaper-o"></i> Create Document file in <strong class="text-primary">{{ $folder->name() }}</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">

                <form action="{{ route('files.create', $folder->id) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Document Name</label>
                        <input class="form-control{{ $errors->has('document_name') ? ' is-invalid' : '' }}" required type="text" name="document_name">
                        @if ($errors->has('document_name'))
                            <span class="invalid-feedback">
                                    <strong>{{ $errors->first('document_name') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default">Reset</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
