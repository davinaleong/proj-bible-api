@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h5">Book: {{ $book->name }} Create Chapter</h1>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('chapters.store', ['translation' => $translation, 'book' => $book]) }}">
                @csrf
                <div class="form-group">
                    <label>Book</label>
                    <p class="form-control-plaintext">{{ $book->name }}</p>
                </div>
                <div class="form-group">
                    <label for="number">Number <span class="text-danger">*</span></label>
                    <input type="text" name="number" class="form-control" value="{{ old('number') }}" required>
                    <p class="form-text text-muted">
                        <small>Determines the order of the chapter.</small>
                    </p>
                </div>
                <div class="form-group">
                    <label for="verse_limit">Verse Limit <span class="text-danger">*</span></label>
                    <input type="text" name="verse_limit" class="form-control" value="{{ old('verse_limit') }}" required>
                    <p class="form-text text-muted">
                        <small>A check for the number of verses one can create for this chapter.</small>
                    </p>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">
                    Submit <i class="fas fa-check"></i>
                </button>
                <a href="{{ route('books.show', ['translation' => $translation, 'book' => $book]) }}" class="btn btn-outline-secondary">
                    Cancel <i class="fas fa-ban"></i>
                </a>
            </form>
        </div>
    </div>
@endsection
