@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">{{ $user->name }}</h1>

    <p>Email: <strong>{{ $user->email }}</strong></p>

    <a href="{{ route('user.edit', ['user' => $user]) }}" class="btn btn-primary">
        Edit <i class="fas fa-pen"></i>
    </a>
@endsection
