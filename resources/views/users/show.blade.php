@extends('layouts.admin')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h3 mb-2 text-gray-800">{{ $user->name }}</h1>
        </div>
        <div class="card-body">
            <p>Email: <strong>{{ $user->email }}</strong></p>

            <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-primary">
                Edit <i class="fas fa-pen"></i>
            </a>
        </div>
    </div>
@endsection
