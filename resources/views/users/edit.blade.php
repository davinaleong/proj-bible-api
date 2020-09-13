@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('users.update', ['user' => $user]) }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') ? old('name') : $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control-plaintext" value="{{ $user->email }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">
            Submit <i class="fas fa-check"></i>
        </button>
        <a href="{{ route('users.show', ['user' => $user]) }}" class="btn btn-outline-secondary">
            Cancel <i class="fas fa-ban"></i>
        </a>
    </form>
@endsection
