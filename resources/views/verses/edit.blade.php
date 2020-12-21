@extends('layouts.admin')

@section('content')
    <h1>{{ $translation->name }}</h1>

    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h5">Book: {{ $book->name }} | Chapter: {{ $chapter->number }} | Verse: {{ $verse->number }}</h1>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('verses.update', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'verse' => $verse]) }}">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label>Chapter</label>
                    <p class="form-control-plaintext">{{ $chapter->number }}</p>
                </div>
                <div class="form-group">
                    <label>Creator</label>
                    <p class="form-control-plaintext">{{ $verse->getCreatorName() }}</p>
                </div>
                <div class="form-group">
                    <label>Updater</label>
                    <p class="form-control-plaintext">{{ $verse->getUpdaterName() }}</p>
                </div>
                <div class="form-group">
                    <label for="number">Number <span class="text-danger">*</span></label>
                    <input type="text" name="number" class="form-control" value="{{ old('number') ?? $verse->number }}" required>
                    <p class="form-text text-muted">
                        <small>Determines the order of the verse. For Translations with multiple verses, use hyphen. (E.g. 1-2)</small>
                    </p>
                </div>
                <div class="form-group">
                    <label for="passage">Passage <span class="text-danger">*</span></label>
                    <textarea name="passage" class="form-control">{{ old('passage') ?? $verse->passage }}</textarea>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">
                    Submit <i class="fas fa-check"></i>
                </button>
                <a href="{{ route('verses.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'verse' => $verse]) }}" class="btn btn-outline-secondary">
                    Cancel <i class="fas fa-ban"></i>
                </a>
            </form>
        </div>
    </div>
@endsection
