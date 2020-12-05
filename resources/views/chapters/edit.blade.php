@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h5">Book: {{ $book->name }} Chapter: {{ $chapter->number }}</h1>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('chapters.update', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Creator</label>
                    <p class="form-control-plaintext">{{ $chapter->getCreatorName() }}</p>
                </div>
                <div class="form-group">
                    <label>Updater</label>
                    <p class="form-control-plaintext">{{ $chapter->getUpdaterName() }}</p>
                </div>
                <div class="form-group">
                    <label>Book</label>
                    <p class="form-control-plaintext">{{ $chapter->book->name }}</p>
                </div>
                <div class="form-group">
                    <label for="number">Number <span class="text-danger">*</span></label>
                    <input type="number" name="number" class="form-control" value="{{ old('number') ? old('number') : $chapter->number }}" min="1" max="66" step="1" required>
                    <p class="form-text text-muted">
                        <small>Determines the order of the book.</small>
                    </p>
                </div>
                <div class="form-group">
                    <label for="verse_limit">Verse Limit <span class="text-danger">*</span></label>
                    <input type="number" name="verse_limit" class="form-control" value="{{ old('verse_limit') ? old('verse_limit') : $chapter->verse_limit }}" min="1" step="1" required>
                    <p class="form-text text-muted">
                        <small>A check for the number of chapters one can create for this book.</small>
                    </p>
                </div>
                <div class="form-group">
                    <label>Created At</label>
                    <p class="form-control-plaintext">{{ $chapter->getCreatedAt() }}</p>
                </div>
                <div class="form-group">
                    <label>Updated At</label>
                    <p class="form-control-plaintext">{{ $chapter->getUpdatedAt() }}</p>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">
                    Submit <i class="fas fa-check"></i>
                </button>
                <a href="{{ route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]) }}" class="btn btn-outline-secondary">
                    Cancel <i class="fas fa-ban"></i>
                </a>
            </form>
        </div>
    </div>
@endsection
