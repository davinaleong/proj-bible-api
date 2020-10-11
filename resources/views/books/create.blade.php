@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h5">Create Book</h1>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('books.store', ['translation' => $translation]) }}">
                @csrf
                <div class="form-group">
                    <label>Translation</label>
                    <p class="form-control-plaintext">{{ $translation->name }}</p>
                </div>
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="abbr">Abbr <span class="text-danger">*</span></label>
                    <input type="text" name="abbr" class="form-control" value="{{ old('abbr') }}" required>
                </div>
                <div class="form-group">
                    <label for="number">Number <span class="text-danger">*</span></label>
                    <input type="number" name="number" class="form-control" value="{{ old('number') }}" min="1" max="66" step="1" required>
                    <p class="form-text text-muted">
                        <small>Determines the order of the book.</small>
                    </p>
                </div>
                <div class="form-group">
                    <label for="chapter_limit">Chapter Limit <span class="text-danger">*</span></label>
                    <input type="number" name="chapter_limit" class="form-control" value="{{ old('chapter_limit') }}" min="1" step="1" required>
                    <p class="form-text text-muted">
                        <small>A check for the number of chapters one can create for this book.</small>
                    </p>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">
                    Submit <i class="fas fa-check"></i>
                </button>
                <a href="{{ route('translations.show', ['translation' => $translation]) }}" class="btn btn-outline-secondary">
                    Cancel <i class="fas fa-ban"></i>
                </a>
            </form>
        </div>
    </div>
@endsection
