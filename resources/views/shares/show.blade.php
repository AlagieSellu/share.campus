@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                  <strong class="text-primary">{{ $share->object->name() }}</strong>
                    Share
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Size</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($share->object->files as $file)
                        <tr>
                            <td><i class="fa fa-file-o"></i></td>
                          <td>
                            <a href="{{ route('shares.file', [$share->id, $file->id]) }}" title="{{ $file->path() }}">{{ $file->name }}</a>
                          </td>
                          <td>{{ $file->size() }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection
