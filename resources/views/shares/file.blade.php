@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                  Shared File
                </div>
                <div class="card-body">
                    <div class="card-header text-primary">
                        <h4>
                            {{ $file->name }}
                            <br>
                            <small class="text-muted">
                                <i class="fa fa-caret-right"></i> size {{ $file->size() }}
                                <br>
                                <i class="fa fa-user"></i>
                                <strong>
                                    {{ $share->user->name }}
                                    <small><i class="fa fa-angle-double-right"></i> {{ $share->object->user->email }}</small>
                                </strong>
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
