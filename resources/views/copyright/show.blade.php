@extends('layouts.admin')

@section('content')
    <h1>Copyrights</h1>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">{{ $copyright->name }}</h2>
        </div>
        <div class="card-body">
            <p>Text: {!! nl2br($copyright->text) !!}</p>
            <p>Creator: {{ $copyright->getUserName() }}</p>
        </div>
    </div>
@endsection
