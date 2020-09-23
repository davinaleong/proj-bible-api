@extends('layouts.admin')

@section('content')
    <h1>Copyrights</h1>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">{{ $copyright->name }}</h2>
        </div>
        <div class="card-body">
            <p>Text: {!! nl2br($copyright->text) !!}</p>
            <p>Creator: {{ $copyright->getUserName() }}</p>

            <a href="{{ route('copyright.edit', ['copyright' => $copyright]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
        </div>
    </div>
@endsection
