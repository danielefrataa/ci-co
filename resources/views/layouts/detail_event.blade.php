<!-- resources/views/components/modal-event-details.blade.php -->
<div class="modal fade" id="eventModal{{ $booking->id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content p-0 rounded-3">
            <div class="modal-header" style="border: none; padding-bottom: 0px;">
                <h3 class="modal-title w-100 text-center" id="eventModalLabel{{ $booking->id }}" style="color: #091F5B;">
                    Detail Acara
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <div class="text-center mb-2" style="border-bottom: 3px solid #091F5B;">
                    <div style="font-size: 1.5rem;">{{ $booking->nama_event }}</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama PIC:</strong></p>
                        <p>{{ $booking->nama_pic }}</p>
                        <p><strong>Kategori Ekraf:</strong></p>
                        <p>{{ $booking->kategori_ekraf }}</p>
                        <p><strong>Jumlah Peserta:</strong></p>
                        <p>{{ $booking->jumlah_peserta }} Orang</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>No Telp:</strong></p>
                        <p>{{ $booking->no_pic }}</p>
                        <p><strong>Kategori Event:</strong></p>
                        <p>{{ $booking->kategori_event }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
