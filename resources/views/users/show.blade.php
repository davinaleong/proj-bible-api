@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-3">
        <div class="card-header">
            <h1 class="card-title h3">{{ $user->name }}</h1>
        </div>
        <div class="card-body">
            <p>Email: <strong>{{ $user->email }}</strong></p>

            <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-primary">
                Edit <i class="fas fa-pen"></i>
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h1 class="card-title h3">Activity</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Message</th>
                        <th>Source</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user->logs()->orderByDesc('id')->take(10)->get() as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->message }}</td>
                            <td><a href="/{{ $log->source }}/{{ $log->source_id }}">{{ $log->source }}/{{ $log->source_id }}</a></td>
                            <td>{{ $log->getCreatedAt() }}</td>
                            <td>{{ $log->getUpdatedAt() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
