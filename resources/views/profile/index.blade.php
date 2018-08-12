@extends('layouts.app')

@section('content')
    @include('profile.inc.modals')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Profile
                    <span class="float-right">
                      <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editModal">
                          <i class="fa fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#passwordModal">
                          <i class="fa fa-key"></i> Change Password
                      </button>
                  </span>
                </div>

                <div class="card-body">
                    <h4>
                        <small class="text-info">Name</small> {{ $profile->name }}
                        <hr>
                        <small class="text-info">Email</small> {{ $profile->email }}
                        <hr>
                        <small class="text-info">Storage</small> {{ $profile->storage() }}
                        <hr>
                        <small class="text-info">Consumed Storage</small> {{ $profile->consumed_storage() }}
                        <hr>
                        <small class="text-info">Available Storage</small> {{ $profile->available_storage() }}
                        <hr>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
