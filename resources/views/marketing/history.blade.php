<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap Icons -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>History Peminjaman Barang</title>
</head>

<body>

@include('layouts.app');
<div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">History Peminjaman Barang</h1>
        </div>

        <!-- Alert Section -->
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
            Sukses!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Kode Booking</th>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Ditambah Oleh</th>
                        <th>Dihapus Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') }}</td>
                            <td>{{ $item->kode_booking }}</td>
                            <td>{{ $item->nama_item }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->createdBy->name ?? '-' }}</td>
                            <td>{{ $item->deletedBy->name ?? '-' }}</td>                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data history</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
            <a class="page-link" 
               href="{{ url()->current() }}?page={{ $currentPage - 1 }}&per_page={{ $perPage }}&status={{ request('status') }}" 
               tabindex="-1" 
               aria-disabled="{{ $currentPage == 1 ? 'true' : 'false' }}">
                Previous
            </a>
        </li>

        {{-- Page Numbers --}}
        @for ($page = 1; $page <= $totalPages; $page++)
            <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                <a class="page-link"
                   href="{{ url()->current() }}?page={{ $page }}&per_page={{ $perPage }}&status={{ request('status') }}">
                    {{ $page }}
                </a>
            </li>
        @endfor

        {{-- Next Page Link --}}
        <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
            <a class="page-link" 
               href="{{ url()->current() }}?page={{ $currentPage + 1 }}&per_page={{ $perPage }}&status={{ request('status') }}" 
               aria-disabled="{{ $currentPage == $totalPages ? 'true' : 'false' }}">
                Next
            </a>
        </li>
    </ul>
</nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>