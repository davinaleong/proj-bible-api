@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            <h2 class="h5 card-title">Book: {{$book->name}} Chapter: {{ $chapter->number }} Verse: {{ $verse->number }}</h2>
        </div>
        <div class="card-body">
            <p>Number: {{ $verse->number }}</p>
            <p>Passage: {{ $verse->passage }}</p>
            <p>Creator: {{ $verse->getCreatorName() }}</p>
            <p>Updater: {{ $verse->getUpdaterName() }}</p>
            <p>Created At: {{ $verse->getCreatedAt() }}</p>
            <p>Updated At: {{ $verse->getUpdatedAt() }}</p>

            <a href="{{ route('verses.edit', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'verse' => $verse]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('verses.destroy', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'verse' => $verse]) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Chapter</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        Are you sure?
                        <br>This will delete the <strong>CURRENT</strong> verse.
                        <br><span class="text-danger">This action cannot be undone.</span>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
