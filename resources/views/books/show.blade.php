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

            <a href="{{ route('books.edit', ['translation' => $translation, 'book' => $book]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

{{--    <div class="card shadow">--}}
{{--        <div class="card-header">--}}
{{--            <h2 class="h5 card-title">--}}
{{--                Books ({{ $translation->books->count() }})--}}
{{--                @if ($translation->books->count() < 66)--}}
{{--                    <a href="{{ route('books.create', ['translation' => $translation]) }}" class="btn btn-primary">--}}
{{--                        Create <i class="fas fa-plus"></i>--}}
{{--                    </a>--}}
{{--                @endif--}}
{{--            </h2>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            @foreach($translation->books as $book)--}}
{{--                <div class="card card-hover" onclick="goto('{{ route('books.show', ['translation' => $translation, 'book' => $book]) }}')">--}}
{{--                    <div class="card-body">--}}
{{--                        <h3>{{ $book->name }} ({{ $book->abbr }})</h3>--}}
{{--                        <p>Creator: {{ $book->getCreatorName() }}</p>--}}
{{--                        <p>Updater: {{ $book->getUpdaterName() }}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}

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
                        Are you sure? This will delete all chapters and verses associated with this book.
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
