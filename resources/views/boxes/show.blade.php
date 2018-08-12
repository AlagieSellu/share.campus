@extends('layouts.app')

@section('content')
    @include('boxes.inc.modals')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    By
                    <strong class="text-primary">
                        {{ $box->user->name }}
                        <small>{{ $box->user->email }}</small>
                    </strong>
                    @if(Auth::user()->id == $box->user_id)
                    <span class="float-right">
                        <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                          <i class="fa fa-trash"></i> Delete
                        </button>
                    </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="">
                            <small class="text-muted">Box ID:</small>
                            <code>{{ $box->address }}</code>
                            <br>
                            <small class="text-muted">
                                <i class="fa fa-caret-right"></i> file {{ $box->name }}<br>
                                <i class="fa fa-caret-right"></i> size {{ $box->size() }}<br>
                                <i class="fa fa-caret-right"></i> uploaded {{ $box->created_at->diffForHumans() }}<br>
                                <i class="fa fa-caret-right"></i> will expire in {{ $box->expire_at()->diffForHumans() }}<br>
                            </small>
                        </h3>
                    </div>
                    <br>
                    <a class="btn btn-warning btn-lg" href="{{ route('boxes.download', $box->id) }}" target="_blank">
                        <i class="fa fa-download"></i> Download <span class="badge badge-light">{{ $box->downloads }} Downloads</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
