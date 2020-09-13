@if (session('message'))
    <div class="alert alert-primary">
        <p>{{ session('message') }}</p>
    </div>
@endif
