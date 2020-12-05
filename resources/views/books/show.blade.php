@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            <h2 class="h5 card-title">{{ $book->name }}</h2>
        </div>
        <div class="card-body">
            <p>Abbr: {{ $book->abbr }}</p>
            <p>Number: {{ $book->number }}</p>
            <p>Chapter Limit: {{ $book->chapter_limit }}</p>
            <p>Creator: {{ $book->getCreatorName() }}</p>
            <p>Updater: {{ $book->getUpdaterName() }}</p>
            <p>Created At: {{ $book->getCreatedAt() }}</p>
            <p>Updated At: {{ $book->getUpdatedAt() }}</p>

            <a href="{{ route('books.edit', ['translation' => $translation, 'book' => $book]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">
                Chapters ({{ $book->chapters->count() }})
                @if ($book->chapters->count() < $book->chapter_limit)
                    <a href="{{ route('chapters.create', ['translation' => $translation, 'book' => $book]) }}" class="btn btn-primary">
                        Create <i class="fas fa-plus"></i>
                    </a>
                @endif
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Number</th>
                        <th>Verse Limit</th>
                        <th>Creator</th>
                        <th>Updater</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Number</th>
                        <th>Verse Limit</th>
                        <th>Creator</th>
                        <th>Updater</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($book->chapters as $chapter)
                        <tr class="clickable" onclick="goto('{{ route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}')">
                            <td>{{ $chapter->id }}</td>
                            <td>{{ $chapter->number }}</td>
                            <td>{{ $chapter->verse_limit }}</td>
                            <td>{{ $chapter->getCreatorName() }}</td>
                            <td>{{ $chapter->getUpdaterName() }}</td>
                            <td>{{ $chapter->getCreatedAt() }}</td>
                            <td>{{ $chapter->getUpdatedAt() }}</td>
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
                <form method="post" action="{{ route('books.destroy', ['translation' => $translation, 'book' => $book]) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Book</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        Are you sure?
                        <br>This will delete <strong>ALL</strong> chapters and verses associated with this book.
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
