<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">
                    Edit <span class="text-primary">{{ $profile->name }}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.rename') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <strong>Rename</strong>
                        <input class="form-control{{ $errors->has('rename') ? ' is-invalid' : '' }}" required type="text" name="rename" value="{{ $profile->name }}">
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
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="passwordModalLabel">
                    Change Password <span class="text-primary">{{ $profile->name }}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.password') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <strong>Password</strong>
                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required type="password" name="password" value="">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <strong>Confirm Current Password</strong>
                        <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" required type="password" name="password_confirmation" value="">
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="upload_button">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->