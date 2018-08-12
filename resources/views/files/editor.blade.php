@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-edit"></i> Edit
                    <strong class="text-primary"><a href="{{ route('files.show', $file->id) }}">{{ $file->name }}</a></strong>
                    Document
                    <span class="pull-right">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('files.show', $file->id) }}">Done Editing</a>
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('files.store.doc', $file->id) }}" method="post">
                        @csrf
                        <textarea id="editor" name="document_content">
                            @if(old('document_content') != null)
                                {!! old('document_content') !!}
                            @else
                                {!! Storage::get($file->address) !!}
                            @endif
                        </textarea>
                        <hr>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <a class="btn btn-outline-dark" href="{{ route('files.editor', $file->id) }}">
                                <i class="fa fa-refresh"></i> Refresh
                            </a>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- CK Editor -->
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@endsection