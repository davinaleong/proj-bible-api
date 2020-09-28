@extends('layouts.admin')

@section('content')
    <h1>Translations <a href="{{ route('translations.create') }}" class="btn btn-primary">Create <i class="fas fa-plus"></i></a></h1>

    <div class="row">
        @foreach($translations as $translation)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-hover shadow mr-3 mb-3"
                     onclick="goto('{{ route('translations.show', ['translation' => $translation]) }}')">
                    <div class="card-header">
                        <h2 class="h5 card-title">{{ $translation->abbr }}</h2>
                    </div>
                    <div class="card-body">
                        <p>{{ $translation->name }}</p>
                        <p>Creator: {{ $translation->getCreatorName() }}</p>
                        <p>Updater: {{ $translation->getUpdaterName() }}</p>

                        <p><small>{{ $translation->getCopyrightText() }}</small></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
