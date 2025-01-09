<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
@include('layouts.app')

<body>
    <div class="container">
        <h1>Daftar Pengguna</h1>
        <a href="{{ route('it.users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

        <!-- Tampilkan Pesan Alert jika ada -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Pengguna -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('it.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('it.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination and Dropdown for per page selection -->
        <div class="d-flex justify-center align-items-center mb-3">
            <!-- Pagination Links -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @for ($page = 1; $page <= $users->lastPage(); $page++)
                        <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $users->url($page) }}&per_page={{ request('per_page') }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>
