@extends('layouts.app')

@section('content')
    @include('files.inc.modals')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <strong><a href="{{ route('folders.show', $file->folder->id) }}">{{ $file->folder->path() }}</a></strong>
                    {{ $file->is_doc ? 'Document':'File' }}
                    <span class="float-right">
                      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewShareModal">
                          <i class="fa fa-share-alt"></i> View Share <span class="badge badge-light">{{ count($file->shares) }}</span>
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
                    <div class="card-header text-primary">
                        @if($file->is_doc)
                        <a class="btn btn-outline-dark btn-sm pull-right" href="{{ route('files.editor', $file->id) }}">
                            <i class="fa fa-edit"></i> Edit Document
                        </a>
                        @endif
                        <h4>
                            {{ $file->name }}
                            <br>
                            <small class="text-muted">
                                <i class="fa fa-caret-right"></i> size {{ $file->size() }},
                                added {{ $file->created_at->diffForHumans() }},
                                modified {{ $file->updated_at->diffForHumans() }}
                            </small>
                        </h4>
                    </div>
                    @if($file->is_doc)
                        <div class="border" style="padding:2.5%">
                            {!! Storage::get($file->address) !!}
                        </div>
                    @elseif(App\Fun::canPlay($file))
                        <object width="100%" data="{{ asset('storage/'.$file->address) }}"></object>
                    @endif
                    <br>
                    <a class="btn btn-warning btn-sm" href="{{ route('files.download', $file->id) }}" target="_blank">
                        <i class="fa fa-download"></i> Download <span class="badge badge-light">{{ $file->downloads }} Downloads</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
