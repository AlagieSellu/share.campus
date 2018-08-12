@extends('layouts.app')

@section('content')
    @include('folders.inc.js')
    @include('folders.inc.modals')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                  <strong class="text-primary">{{ $folder->path() }}</strong>
                  {{ $folder->storage() }}
                  @if($folder->folder->id != '')
                  <a href="{{ route('folders.show', $folder->folder) }}" class="btn btn-link">Go Back</a>
                  @else
                  <a class="btn btn-link disabled">Go Back</a>
                  @endif
                  <span class="float-right">
                      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewShareModal">
                          <i class="fa fa-share-alt"></i> View Share <span class="badge badge-light">{{ count($folder->shares) }}</span>
                      </button>
                      <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editModal">
                          <i class="fa fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                          <i class="fa fa-trash"></i> Delete
                      </button>
                  </span>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-" data-toggle="modal" data-target="#uploadModal">
                            <i class="fa fa-upload"></i> Upload Files</button>
                        <button class="btn btn-primary btn-" data-toggle="modal" data-target="#docModal">
                            <i class="fa fa-newspaper-o"></i> Create Document</button>
                    </div>
                    <div class="col-md-12"><br>
                      <form action="{{ route('folders.store') }}" method="post">
                          @csrf
                          <input type="hidden" name="folder_id" value="{{ $folder->id }}">

                        <div class="form-group input-group">
                            <input type="text" name="name" id="name" required placeholder="Create a new folder in /{{ $folder->name }}"
                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}">
                            <input class="btn btn-outline-primary form-control col-4" type="submit" value="Create">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Shares</th>
                            <th>Created At</th>
                            <th style="width:6%;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($folders as $folder)
                        <tr>
                            <td><i class="fa fa-folder-o"></i></td>
                          <th>
                            <a href="{{ route('folders.show', $folder) }}" title="{{ $folder->path() }}">{{ $folder->name() }}</a>
                          </th>
                          <td>{{ $folder->count() }}</td>
                          <td><code>{{ count($folder->shares) }}</code></td>
                          <td>{{ $folder->created_at->diffForHumans() }}</td>
                            <td>
                                <button id="folder_{{ $folder->id }}" onclick="share({{ $folder->id }}, 0)" class="btn btn-outline-primary btn-sm"><i class="fa fa-check"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($files as $file)
                        <tr>
                            <td><i class="fa fa-{{ $file->is_doc ? 'newspaper-o':'file-o' }}"></i></td>
                          <td>
                            <a href="{{ route('files.show', $file) }}" title="{{ $file->path() }}">{{ $file->name }}</a>
                          </td>
                          <td>{{ $file->size() }}</td>
                            <td><code>{{ count($file->shares) }}</code></td>
                          <td>{{ $file->created_at->diffForHumans() }}</td>
                            <td>
                                <button id="file_{{ $file->id }}" onclick="share({{ $file->id }}, 1)" class="btn btn-outline-primary btn-sm"><i class="fa fa-check"></i></button>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                      <button class="pull-right btn btn-primary" data-toggle="modal" onclick="sendShare()" data-target="#shareModal">
                          <i class="fa fa-share-alt"></i> Share With
                      </button>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var shareFolders = [
            @foreach($folders as $folder)
            [
                {{ $folder->id }},
                0,
                '{{ $folder->name() }}',
            ],
            @endforeach
        ];
        var shareFiles = [
            @foreach($files as $file)
            [
                {{ $file->id }},
                0,
                '{{ $file->name }}',
            ],
            @endforeach
        ];
        function sendShare() {
            var shareList = new Array();
            for(var i in shareFiles){
                shareList.push([
                    shareFiles[i][0],
                    shareFiles[i][1],
                    true,
                ]);
            }
            for(var i in shareFolders){
                shareList.push([
                    shareFolders[i][0],
                    shareFolders[i][1],
                    false,
                ]);
            }
            document.getElementById('share_objects').value = JSON.stringify(shareList);
        }
        function share(id, file) {
            if (file){
                for(var i in shareFiles){
                    if (shareFiles[i][0] == id){
                        shareFiles[i][1] = !shareFiles[i][1];
                    }
                }
            }else{
                for(var i in shareFolders){
                    if (shareFolders[i][0] == id){
                        shareFolders[i][1] = !shareFolders[i][1];
                    }
                }
            }
            displayShareList(id, file);
        }
        function displayShareList(id, file) {
            if(file){
                var btn_type = 'file_';
                for(var i in shareFiles){
                    if (shareFiles[i][0] == id){
                        if (shareFiles[i][1]){
                            var btn_class = 'btn btn-primary btn-sm';
                        }else{
                            var btn_class = 'btn btn-outline-primary btn-sm';
                        }
                    }
                }
            }else{
                var btn_type = 'folder_';
                for(var i in shareFolders){
                    if (shareFolders[i][0] == id){
                        if (shareFolders[i][1]){
                            var btn_class = 'btn btn-primary btn-sm';
                        }else{
                            var btn_class = 'btn btn-outline-primary btn-sm';
                        }
                    }
                }
            }
            document.getElementById(btn_type+id).className = btn_class;
        }
    </script>
@endsection
