@extends('layouts.admin')

@section('content')
    <h1>Copyrights</h1>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">{{ $copyright->name }}</h2>
        </div>
        <div class="card-body">
            <p>Text: {!! nl2br($copyright->text) !!}</p>
            <p>Creator: {{ $copyright->getCreatorName() }}</p>
            <p>Updater: {{ $copyright->getUpdaterName() }}</p>

            <a href="{{ route('copyright.edit', ['copyright' => $copyright]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('copyright.destroy', ['copyright' => $copyright]) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        Are you sure?
                        <br>This action cannot be undone
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">
                            Cancel <i class="fas fa-ban"></i>
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Delete <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection