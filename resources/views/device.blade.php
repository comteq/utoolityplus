@include('nav')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Page</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5 wrapper">
        <div class="card border-box-form">
            <div class="card-body">
                <h1 class="card-title">Device Settings</h1>
    
                <form id="deviceForm">
                    @csrf
                    <div class="form-group">
                        <label for="Device_IP">Arduino IP Address</label>
                        <input type="text" class="form-control" id="Device_IP" name="Device_IP" placeholder="Enter IP Address" value="{{ $deviceSettings->Device_IP ?? '' }}" required>
                    </div>
    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Air Conditioning Component Form -->
                            <h2>Air Conditioning Component</h2>
    
                            <div class="form-group">
                                <label for="acNumPins">Number of AC Pins</label>
                                <select class="form-control" id="acNumPins" name="acNumPins">
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ ($deviceSettings->acNumPins ?? 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
    
                            <!-- Container to hold AC pin boxes -->
                            <div id="acPinContainer" class="d-flex flex-wrap">
                                <!-- AC pin boxes will be dynamically added here -->
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <!-- Light Component Form -->
                            <h2>Light Component</h2>
    
                            <div class="form-group">
                                <label for="lightsNumPins">Number of Light Pins</label>
                                <select class="form-control" id="lightsNumPins" name="lightsNumPins">
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ ($deviceSettings->lightsNumPins ?? 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
    
                            <!-- Container to hold light pin boxes -->
                            <div id="lightPinContainer" class="d-flex flex-wrap">
                                <!-- Light pin boxes will be dynamically added here -->
                            </div>
                        </div>
                    </div>
    
                    <div class="d-flex justify-content-end align-items-center m-3">
                        <!-- Move buttons to the right -->
                        <button type="reset" id="resetSettings" class="btn btn-secondary mr-2">Reset</button>
                        <button type="button" id="saveSettings" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

<!-- Bootstrap JS and Popper.js CDN (required for Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JavaScript for dynamic pin boxes and AJAX -->
<script>
    $(document).ready(function () {
        function hasChanges() {
            // Compare the current values with the original values
            var originalDeviceIP = '{{ $deviceSettings->Device_IP ?? '' }}';
            var currentDeviceIP = $('#Device_IP').val();
            var originalLightsNumPins = '{{ $deviceSettings->lightsNumPins ?? 1 }}';
            var currentLightsNumPins = $('#lightsNumPins').val();
            var originalACNumPins = '{{ $deviceSettings->acNumPins ?? 1 }}';
            var currentACNumPins = $('#acNumPins').val();

            return (
                originalDeviceIP !== currentDeviceIP ||
                originalLightsNumPins !== currentLightsNumPins ||
                originalACNumPins !== currentACNumPins
            );
        }
        // Function to update light pin boxes based on selected number of pins and status
        function updateLightPins() {
            // Clear the lightPinContainer
            $('#lightPinContainer').empty();

            // Get the selected number of light pins
            var lightsNumPins = $('#lightsNumPins').val();

            // Simulated light pin status, replace this with your actual logic to fetch pin status
            var lightPinStatus = ["on", "off", "on", "off", "on", "off", "on", "off"];

            // Add light pin boxes dynamically
            for (var i = 1; i <= lightsNumPins; i++) {
                var lightPinBox = $('<div>').addClass('pin-box m-2 p-3');
                var lightStatus = lightPinStatus[i - 1] || "off"; // Default to "off" if status is undefined
                lightPinBox.html('<label for="lightPin' + i + '" class="text-center">Light Pin ' + i + '</label>');
                lightPinBox.css('background-color', (lightStatus === "on") ? 'green' : 'red');
                $('#lightPinContainer').append(lightPinBox);
            }
        }

        // Function to update AC pin boxes based on selected number of pins and status
        function updateACPins() {
            // Clear the acPinContainer
            $('#acPinContainer').empty();

            // Get the selected number of AC pins
            var acNumPins = $('#acNumPins').val();

            // Simulated AC pin status, replace this with your actual logic to fetch pin status
            var acPinStatus = ["on", "off", "on", "off", "on", "off", "on", "off"];

            // Add AC pin boxes dynamically
            for (var i = 1; i <= acNumPins; i++) {
                var acPinBox = $('<div>').addClass('pin-box m-2 p-3');
                var acStatus = acPinStatus[i - 1] || "off"; // Default to "off" if status is undefined
                acPinBox.html('<label for="acPin' + i + '" class="text-center">AC Pin ' + i + '</label>');
                acPinBox.css('background-color', (acStatus === "on") ? 'green' : 'red');
                $('#acPinContainer').append(acPinBox);
            }
        }

        // Call the updateLightPins function when the lightsNumPins dropdown changes
        $('#lightsNumPins').change(updateLightPins);

        // Initial call to set up the light pins based on the default selected number of light pins
        updateLightPins();

        // Call the updateACPins function when the acNumPins dropdown changes
        $('#acNumPins').change(updateACPins);

        // Initial call to set up the AC pins based on the default selected number of AC pins
        updateACPins();

        // Reset button event listener
        $('#resetSettings').click(function () {
            // Clear input fields and reset pin containers
            $('#deviceForm')[0].reset();
            updateLightPins();
            updateACPins();
        });

        // AJAX request on Save button click
        $('#saveSettings').click(function () {
            // Check if there are changes
            if (hasChanges()) {
                // If there are changes, proceed with the AJAX request
                $.ajax({
                    type: 'POST',
                    url: '{{ route("update-device-settings") }}',
                    data: $('#deviceForm').serialize(), // Serialize form data
                    success: function (response) {
                        console.log(response); // Log success response
                        // Show success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Settings Updated',
                            text: 'Settings updated successfully.'
                        });
                    },
                    error: function (error) {
                        console.error(error); // Log error response
                        // Show error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating settings.'
                        });
                    }
                });
            } else {
                // If there are no changes, show a message
                Swal.fire({
                    icon: 'info',
                    title: 'No Changes Detected',
                    text: 'No changes detected.'
                });
            }
        });
    });
</script>

<style>
    .border-box-form {
    border: 1px solid #ced4da; /* Border color */
    border-radius: 10px; /* Border radius for rounded corners */
    padding: 20px; /* Adjust padding as needed */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow for floating effect */
    background-color: #FFFFFF;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    margin-bottom: 20px
}

body {
    background-color: #ecedee;
}

.wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            /* Add other styles for your content */
            margin-top: 20px; /* Adjust as needed */
            margin-bottom: 20px; /* Adjust as needed */
        }

        .footer {
            /* Add styles for your footer */
            background-color: #f8f9fa; /* Adjust as needed */
            padding: 10px;
        }
</style>

</body>
</html>
