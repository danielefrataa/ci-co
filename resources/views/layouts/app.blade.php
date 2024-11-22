<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="https://event.mcc.or.id/assets/images/logo.png" width="250" alt="Event Malang Creative Center">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <!-- Right side info -->
            <div class="d-flex align-items-center">
                <!-- Date and Day Display in Indonesian -->
                <div class="me-3">
                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat(format: 'l, d F Y') }}</span>
                </div>

                <!-- User Info Display with Dropdown -->
                <!-- User Info Display with Dropdown -->
                @auth
                    <div class="d-flex align-items-center rounded-pill border px-3 py-1"
                        style="border: 2px solid #091F5B; font-family: 'Montserrat', sans-serif; color: #091F5B; background-color: transparent;">
                        <!-- Profile Icon -->
                        <i class="fas fa-user-circle me-2" style="font-size: 26px; color: #091F5B;"></i>

                        <!-- Role and Name -->
                        <div class="text-start">
                            <div class="fw-bold" style="color: #091F5B; font-size: 12px;">{{ auth()->user()->role }}</div>
                            <div style="font-size: 14px; color: #091F5B;">{{ auth()->user()->name }}</div>
                        </div>

                        <!-- Dropdown Arrow for Logout -->
                        <div class="ms-2">
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none" id="dropdownUser"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-chevron-down" style="color: #091F5B;"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>