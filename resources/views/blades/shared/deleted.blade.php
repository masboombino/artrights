@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-sm py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-circle fa-5x text-warning"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Content Unavailable</h2>
                    <p class="lead text-muted mb-4">
                        The content you are looking for has been deleted or is no longer available.
                    </p>
                    <a href="{{ url()->previous() }}" class="btn btn-primary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Go Back
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 ms-2">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
