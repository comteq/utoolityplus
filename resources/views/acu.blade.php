<!-- resources/views/acu.blade.php -->
@extends('home')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<div class="card custom-card text-center">
    <div class="card-header">
        <h5 class="mb-0">Remote Control</h5>
    </div>
    <div class="card-body text-center">
        <div class="d-inline-block">
            <button type="button" class="btn" id="powerButton" onclick="showPowerModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                    <path d="M7.5 1v7h1V1z"/>
                    <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
                </svg>
            </button>
            <div class="mt-2">
                <p class="mb-0">Power</p>
            </div>
        </div>
        <div class="d-inline-block ml-3">
            <button type="button" class="btn btn-primary" onclick="logActivity('Pair Remote')">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                    <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                </svg>
            </button>
            <div class="mt-2">
                <p class="mb-0">Pair</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Check power state from localStorage on page load
    var isPowerOn = localStorage.getItem('powerState') === 'on';
    changeButtonColor(isPowerOn);

    // Optional: You can log the activity on page load if needed
    if (isPowerOn) {
        logActivity('Power On ACU');
    } else {
        logActivity('Power Off ACU');
    }
});

// Function to show power modal
function showPowerModal() {
    Swal.fire({
        title: 'Power Options',
        showCancelButton: true,
        confirmButtonText: 'Power On',
        cancelButtonText: 'Power Off',
        reverseButtons: true,
        focusConfirm: false,
    }).then((result) => {
        if (result.isConfirmed) {
            logActivity('Power On ACU');
            changeButtonColor(true); // Change to green
            localStorage.setItem('powerState', 'on');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            logActivity('Power Off ACU');
            changeButtonColor(false); // Change to red
            localStorage.setItem('powerState', 'off');
        }
    });
}

// Function to log activity
function logActivity(action) {
    // Get CSRF token from the meta tag
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Log the activity
    fetch('/log-activity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error(error));
}

// Function to change button color
function changeButtonColor(isPowerOn) {
    var powerButton = document.getElementById('powerButton');
    if (isPowerOn) {
        powerButton.classList.remove('btn-danger');
        powerButton.classList.add('btn-success');
    } else {
        powerButton.classList.remove('btn-success');
        powerButton.classList.add('btn-danger');
    }
}
</script>

<style>
    .custom-card {
        max-width: 400px; /* Set a maximum width for the card */
        width: 100%;
        margin: auto;
    }

    /* Add more styles as needed */
    .btn-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }

    .btn-danger {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
</style>
@endsection
