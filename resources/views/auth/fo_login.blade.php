<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Office Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1 style="color: #091F5B;">Login</h1>
    <form action="{{ route('front_office.login.post') }}" method="POST" style="border-radius: 10px" class="card p-5 w-75 shadow-lg p-3">
        @csrf
        <div class="form-group justify-content-between my-1">
            <label class="py-1 fw-bold fs-5" for="email">Email</label>
            <input type="email" style="background-color: white; border-radius: 10px;" name="email" id="email"
                class="form-control py-3" required>
        </div>
        <div class="form-group justify-content-between my-1">
            <label class="py-1 fw-bold fs-5" for="password">Password</label>
            <input type="password" style="border-radius: 10px" name="password" id="password" class="form-control py-3" required>
        </div>
        <div class="my-3">
            <p style="">Lupa kata sandi? </p>
        </div>
        <div class="d-flex flex-column">
            <button type="submit" class="btn" style="background-color: #091F5B; color: white; padding: 16px; font-size: 20px; border-radius: 10px;"><strong>Masuk</strong></button>
            <p style="">
                Belum punya akun? <a href="{{ route('front_office.register') }}" style="color: #091F5B;">Daftar di sini</a>
            </p>
        </div>
    </form>
</body>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif



</html>
