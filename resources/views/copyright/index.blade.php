@extends('layouts.admin')

@section('content')
    <h1>Copyrights</h1>

    <div class="row">
        @foreach($copyrights as $copyright)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-hover" onclick="goto('{{ route('copyright.show', ['copyright' => $copyright]) }}')">
                    <div class="card-header">
                        <h2 class="h5 card-title">{{ $copyright->name }}</h2>
                    </div>
                    <div class="card-body">
                        <p>{{ $copyright->text }}</p>
                        <p>Creator: {{ $copyright->getUserName() }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection


