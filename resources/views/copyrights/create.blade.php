@extends('layouts.admin')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form method="post" action="{{ route('copyrights.store') }}">
                @csrf

                <div class="form-group">
                    <label>Creator</label>
                    <p class="form-control-plaintext">{{ auth()->user()->name }}</p>
                </div>

                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="text">Copyright Text <span class="text-danger">*</span></label>
                    <textarea name="text" class="form-control" rows="4" required>{{ old('text') }}</textarea>
                </div>

                <p class="text-danger">* required</p>
                <button type="submit" class="btn btn-primary">Submit <i class="fas fa-check"></i></button>
                <a href="{{ route('copyrights.index') }}" class="btn btn-outline-secondary">Cancel <i class="fas fa-ban"></i></a>
            </form>
        </div>
    </div>
@endsection
