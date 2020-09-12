@if (session('message'))
    <div class="alert alert-primary">
        <h5 class="alert-heading">
            <i class="fas fa-fw fa-info"></i> Note
        </h5>
        <p>{{ session('message') }}</p>
    </div>
@endif
