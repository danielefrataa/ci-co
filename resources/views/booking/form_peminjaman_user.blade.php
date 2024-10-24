
<!-- Modal for Borrowing Form -->
<div class="modal fade" id="borrowingModal" tabindex="-1" aria-labelledby="borrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none;">
            <div class="modal-header justify-content-center" style="border-bottom: none;">
                <h5 class="modal-title" id="borrowingModalLabel" style="color: #091F5B; text-align: center; font-weight:800;">Formulir Peminjaman Barang</h5>
                <button type="button" class="btn-close position-absolute" style="right: 25px;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="borrowingForm" action="{{ route('borrowing.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">

                    <!-- Borrowing Form Fields -->
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <!-- Add more fields as needed -->

                    <div class="modal-footer" style="border: none;">
                        <button type="submit" class="btn btn-primary btn-center">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>