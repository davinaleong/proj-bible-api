@if ($errors->any())
    <!-- Error Alert -->
    <div class="alert alert-danger">
        <h5 class="alert-heading">
            <i class="fas fa-times-circle"></i> Errors
        </h5>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
