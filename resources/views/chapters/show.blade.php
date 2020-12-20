@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            <h2 class="h5 card-title">Book: {{$book->name}} Chapter: {{ $chapter->number }}</h2>
        </div>
        <div class="card-body">
            <p>Number: {{ $chapter->number }}</p>
            <p>Verse Limit: {{ $chapter->verse_limit }}</p>
            <p>Creator: {{ $chapter->getCreatorName() }}</p>
            <p>Updater: {{ $chapter->getUpdaterName() }}</p>
            <p>Created At: {{ $chapter->getCreatedAt() }}</p>
            <p>Updated At: {{ $chapter->getUpdatedAt() }}</p>

            <a href="{{ route('chapters.edit', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">
                Verses ({{ $chapter->verses->count() }})
                <a href="{{ route('verses.create', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}" class="btn btn-primary">
                    Create <i class="fas fa-plus"></i>
                </a>
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Number</th>
                        <th>Passage</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Number</th>
                        <th>Passage</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($chapter->verses as $verse)
                        <tr class="clickable" onclick="goto('{{ route('verses.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'verse' => $verse]) }}')">
                            <td>{{ $verse->id }}</td>
                            <td>{{ $verse->number }}</td>
                            <td>{{ $verse->truncatePassage() }}</td>
                            <td>{{ $verse->getCreatorName() }}</td>
                            <td>{{ $verse->getUpdaterName() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('chapters.destroy', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}">
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
                        <br>This will delete <strong>ALL</strong> verses associated with this chapter.
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
