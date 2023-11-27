<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aviation Marketplace</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- Your custom styles go here -->
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Aviation Marketplace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">Dashboard</a>
                </li>

                @if(auth()->user()->hasPermissionTo('listing.index') || auth()->user()->hasDirectPermissionTo('listing.index'))

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('listings*') ? 'active' : '' }}"
                           href="{{ route('listings.index') }}">Listings</a>
                    </li>
                @endif

                @if(auth()->user()->hasPermissionTo('message.index') || auth()->user()->hasDirectPermissionTo('message.index'))

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('messages*') ? 'active' : '' }}"
                           href="{{ route('messages.index') }}">Messages</a>
                    </li>
                @endif

                @if(auth()->user()->hasPermissionTo('role.index') || auth()->user()->hasDirectPermissionTo('role.index'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('roles*') ? 'active' : '' }}"
                           href="{{ route('roles.index') }}">Roles & Permissions</a>
                    </li>
                @endif
            </ul>

            @auth
                <div class="navbar-text">
                    <span class="text-white me-2">Hello, {{ auth()->user()->first_name }}</span>
                    <span class="text-info me-2">({{ isset(auth()->user()->roles[0]) ? ucfirst(auth()->user()->roles[0]->name) : 'Super Admin' }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-white">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
    @yield('content')
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(function () {
        @if (session('message'))
        @if (session('status'))
        toastr.success("{{ session('message') }}");
        @else
        toastr.error("{{ session('message') }}");
        @endif
        @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
    });
</script>
</body>
</html>
