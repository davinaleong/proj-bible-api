@extends('layouts.admin')

@section('content')
    <h1>Copyrights</h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            <h2 class="h5 card-title">{{ $copyright->name }}</h2>
        </div>
        <div class="card-body">
            <p>Text: {{ $copyright->text }}</p>
            <p>Creator: {{ $copyright->getCreatorName() }}</p>
            <p>Updater: {{ $copyright->getUpdaterName() }}</p>
            <p>Created At: {{ $copyright->getCreatedAt() }}</p>
            <p>Updated At: {{ $copyright->getUpdatedAt() }}</p>

            <a href="{{ route('copyrights.edit', ['copyright' => $copyright]) }}" class="btn btn-primary">Edit <i class="fas fa-pen"></i></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                Delete <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h2 class="h5 card-title">
                Translations ({{ $copyright->translations->count() }})
                <a href="{{ route('translations.create') }}" class="btn btn-primary">
                    Create <i class="fas fa-plus"></i>
                </a>
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Abbr</th>
                        <th>Creator</th>
                        <th>Updater</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Abbr</th>
                        <th>Creator</th>
                        <th>Updater</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($copyright->translations as $translation)
                        <tr class="clickable" onclick="goto('{{ route('translations.show', ['translation' => $translation]) }}')">
                            <td>{{ $translation->id }}</td>
                            <td>{{ $translation->name }}</td>
                            <td>{{ $translation->abbr }}</td>
                            <td>{{ $translation->getCreatorName() }}</td>
                            <td>{{ $translation->getUpdaterName() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('copyrights.destroy', ['copyright' => $copyright]) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        Are you sure?
                        <br>This action cannot be undone
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">
                            Cancel <i class="fas fa-ban"></i>
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Delete <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
