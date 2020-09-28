@extends('layouts.admin')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form method="post" action="{{ route('copyrights.update', ['copyright' => $copyright]) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label>Creator</label>
                    <p class="form-control-plaintext">{{ $copyright->getCreatorName() }}</p>
                </div>

                <div class="form-group">
                    <label>Updater</label>
                    <p class="form-control-plaintext">{{ $copyright->getUpdaterName() }}</p>
                </div>

                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') ? old('name') : $copyright->name }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="text">Copyright Text <span class="text-danger">*</span></label>
                    <textarea name="text"
                              class="form-control"
                              rows="4"
                              required>{{ old('text') ? old('text') : $copyright->text }}</textarea>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">Submit <i class="fas fa-check"></i></button>
                <a href="{{ route('copyrights.show', ['copyright' => $copyright]) }}" class="btn btn-outline-secondary">Cancel <i class="fas fa-ban"></i></a>
            </form>
        </div>
    </div>
@endsection
