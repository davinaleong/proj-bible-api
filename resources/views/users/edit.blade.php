@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1 class="h3">Update Profile</h1>

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
        </div>

        <div class="col-6">
            <h1 class="h3">Change Password</h1>

            <form method="POST" action="{{ route('users.change-password', ['user' => $user]) }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password <span class="text-danger">*</span></label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_new_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit <i class="fas fa-check"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
