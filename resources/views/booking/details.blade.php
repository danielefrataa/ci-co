<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #signature {
            border: 1px solid #ccc;
            width: 100%;
            height: 150px;
            cursor: crosshair;
        }
    </style>
</head>
<body>
    <div class="container col-lg-6 py-5">
        <h2 class="text-center mb-4">Check In</h2>
        
        <!-- Form to input name and phone number -->
        <form id="checkinForm" action="{{ route('checkin.store') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Your Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#termsModal">Check In</button>
        </form>

        <!-- Modal for Terms and Conditions -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Please read the following terms and conditions carefully:</h6>
                        <ul>
                            <li>Menjaga fasilitas, sarana, dan prasarana yang tersedia dalam Ruangan/Gedung MCC.</li>
                            <li>Dilarang menempel benda apapun pada dinding Ruangan.</li>
                            <!-- Tambahkan syarat dan ketentuan lainnya di sini -->
                        </ul>
                        <p>By clicking 'Confirm Check In', you agree to these terms and conditions.</p>

                        <!-- Signature Pad in Modal -->
                        <label for="signature" class="form-label">Signature</label>
                        <canvas id="signature" class="mb-3"></canvas>
                        <input type="hidden" name="signature" id="signatureData">
                        <button type="button" class="btn btn-secondary mb-2" id="clearSignature">Clear Signature</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="checkinForm">Confirm Check In</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- External JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/3.0.2/signature_pad.umd.min.js"></script>

    <!-- Signature Pad Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature');
            const signaturePad = new SignaturePad(canvas);

            // Resize the canvas to fit the container
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            // Clear signature button
            document.getElementById('clearSignature').addEventListener('click', () => {
                signaturePad.clear();
                document.getElementById('signatureData').value = ''; // Clear the hidden input
            });

            // Capture signature data when confirming check-in
            document.querySelector('.modal-footer button[type="submit"]').addEventListener('click', () => {
                if (!signaturePad.isEmpty()) {
                    document.getElementById('signatureData').value = signaturePad.toDataURL();
                } else {
                    alert("Please provide a signature.");
                }
            });
        });
    </script>
</body>
</html>
