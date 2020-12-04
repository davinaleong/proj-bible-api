@extends('layouts.admin')

@section('content')
    <h1>Copyrights <a href="{{ route('copyrights.create') }}" class="btn btn-primary">Create <i class="fas fa-plus"></i></a></h1>

    <div class="row">
        @foreach($copyrights as $copyright)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-hover shadow mr-3 mb-3"
                     onclick="goto('{{ route('copyrights.show', ['copyright' => $copyright]) }}')">
                    <div class="card-header">
                        <h2 class="h5 card-title">{{ $copyright->name }}</h2>
                    </div>
                    <div class="card-body">
                        <p>{{ $copyright->text }}</p>
                        <p>Creator: {{ $copyright->getCreatorName() }}</p>
                        <p>Updater: {{ $copyright->getUpdaterName() }}</p>
                        <p>Created At: {{ $copyright->getCreatedAt() }}</p>
                        <p>Updated At: {{ $copyright->getUpdatedAt() }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection


