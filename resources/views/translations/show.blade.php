@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            <h2 class="h5 card-title">Other Details</h2>
        </div>
        <div class="card-body">
            <p>Abbr: {{ $translation->abbr }}</p>
            <p>Creator: {{ $translation->getCreatorName() }}</p>
            <p>Updater: {{ $translation->getUpdaterName() }}</p>

            @if ($translation->copyright)
                <p>
                    Copyright: <a href="{{ route('copyrights.show', ['copyright' => $translation->copyright]) }}">{{ $translation->copyright->name }}</a><br>
                    <small>{{ $translation->copyright->text }}</small>
                </p>
            @endif

            <a href="{{ route('translations.edit', ['translation' => $translation]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">
                Books ({{ $translation->books->count() }})
                @if ($translation->books->count() < 66)
                    <a href="{{ route('books.create', ['translation' => $translation]) }}" class="btn btn-primary">
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
                        <th>Name</th>
                        <th>Abbr</th>
                        <th>Number</th>
                        <th>Chapter Limit</th>
                        <th>Creator</th>
                        <th>Updater</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Abbr</th>
                        <th>Number</th>
                        <th>Chapter Limit</th>
                        <th>Creator</th>
                        <th>Updater</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($translation->books as $book)
                        <tr class="clickable" onclick="goto('{{ route('books.show', ['translation' => $translation, 'book' => $book]) }}')">
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->name }}</td>
                            <td>{{ $book->abbr }}</td>
                            <td>{{ $book->number }}</td>
                            <td>{{ $book->chapter_limit }}</td>
                            <td>{{ $book->getCreatorName() }}</td>
                            <td>{{ $book->getUpdaterName() }}</td>
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
                <form method="post" action="{{ route('translations.destroy', ['translation' => $translation]) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Translation</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        Are you sure?
                        <br>This will delete <strong>ALL</strong> books, chapters and verses associated with this translation.
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
