@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">

            <div class="card">
                <div class="card-header">
                    Admin Panel
                    <strong class="pull-right"><i class="fa fa-certificate"></i> You are a level {{ $auth->admin }} admin</strong>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.search') }}" method="post">
                        @csrf

                        <div class="form-group input-group">
                            <input type="email" name="email" id="email" placeholder="Search user by email" required
                                   class="form-control{{ (isset($email) && count($users) == 0) ? ' is-invalid' : '' }}" value="{{ isset($email) ? $email : '' }}">
                            <button class="btn btn-outline-primary form-control col-3" type="submit">
                                <i class="fa fa-search"></i> Search
                            </button>
                            @isset($email)
                                <span class="invalid-feedback">
                                    <strong>User with email {{ $email }} not found</strong>
                                </span>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive table-hover">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Storage</th>
                                <th>Availabel</th>
                                <th>Admin</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <th>{{ $user->name }}</th>
                                    <th class="text-primary">{{ $user->email }}</th>
                                    <td>{{ $user->storage() }}</td>
                                    <td>{{ $user->available_storage() }}</td>
                                    <td>{{ $user->admin() }}</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="promote('{{ $user->email }}', {{ $user->admin }})" data-toggle="modal" data-target="#promoteModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $pagination ? $users->links() : '' }}
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        function promote(email, level) {
            document.getElementById('promote_user').innerText = email;
            document.getElementById('promote_email').value = email;
            if (level == null){
                document.getElementById('promote').style.display = 'block';
                document.getElementById('demote').style.display = 'none';
            }else {
                document.getElementById('promote').style.display = 'none';
                document.getElementById('demote').style.display = 'block';
            }
        }
    </script>
    <div class="modal fade" id="promoteModal" tabindex="-1" role="dialog" aria-labelledby="promoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="promoteModalLabel">
                        <i class="fa fa-certificate"></i> Promote <strong id="promote_user" class="text-primary"></strong>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="promote_email" name="email">

                        <div class="form-group">
                            <label>Increase Storage in GiB</label>
                            <input type="number" name="storage" required
                                   class="form-control{{ $errors->has('storage') ? ' is-invalid' : '' }}" value="{{ old('storage') == null ? 0 : old('storage') }}">
                            @if ($errors->has('storage'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('storage') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group" id="promote">
                            <label class="radio-inline">
                                <input type="checkbox" name="promote" value="1"> Promote to Admin <strong>Level {{ $auth->admin + 1 }}</strong>
                            </label>
                        </div>

                        <div class="form-group" id="demote">
                            <label class="radio-inline">
                                <input type="checkbox" name="demote" value="1">
                                <strong>Revoke admin rights</strong>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Promote</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
