document.addEventListener('DOMContentLoaded', function () {
    // Handle modal button clicks to set the booking ID
    const modalButtons = document.querySelectorAll('[data-bs-target="#dutyOfficerModal"]');

    modalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const bookingId = this.getAttribute('data-booking-id');
            const hiddenInput = document.getElementById('bookingId');
            if (hiddenInput) {
                hiddenInput.value = bookingId;
            }
        });
    });
});

// Function to update the booking status
function updateStatus(bookingId, newStatus) {
    fetch(`/bookings/${bookingId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log data for debugging

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Reload the page to show the updated status
            });
        } else {
            Swal.fire('Error', 'Failed to update status', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'An error occurred while updating the status.', 'error');
    });
}

// Function to update the items per page
function updatePerPage() {
    const perPage = document.getElementById('per-page').value;
    const currentUrl = "{{ url()->current() }}";
    window.location.href = `${currentUrl}?per_page=${perPage}`;
}
