@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">{{ $user->name }}</h1>

    <p>Email: <strong>{{ $user->email }}</strong></p>
@endsection
