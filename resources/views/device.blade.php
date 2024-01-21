@include('nav')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Page</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('/bootstrap/bootstrap.min.css') }}">
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
        
                        <div class="">
                            <div class="">
                                <!-- Air Conditioning Component Form -->
                                <h2>{{$deviceName}}</h2>
        
                                <div class="form-group">
                                    <label for="Pin_Number">Number of Pins</label>
                                    <select class="form-control" id="Pin_Number" name="Pin_Number">
                                        @for ($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ ($deviceSettings->Pin_Number ?? 1) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>                                    
                                </div>

        
                                <!-- Container to hold pin boxes -->
                                <div id="PinContainer" class="d-flex flex-wrap">
                                    <!--  pin boxes will be dynamically added here -->
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
<!-- Custom JavaScript for dynamic pin boxes and AJAX -->
<script>
    $(document).ready(function () {
        function hasChanges() {
            // Compare the current values with the original values
            var originalDeviceIP = '{{ $deviceSettings->Device_IP ?? '' }}';
            var currentDeviceIP = $('#Device_IP').val();
            var originalACNumPins = '{{ $deviceSettings->Pin_Number ?? 1 }}';
            var currentACNumPins = $('#Pin_Number').val();

            return (
                originalDeviceIP !== currentDeviceIP ||
                originalACNumPins !== currentACNumPins
            );
        }

        // Function to update pin boxes based on selected number of pins
        function updatePins() {
            // Clear the PinContainer
            $('#PinContainer').empty();

            // Get the selected number of pins
            var numPins = $('#Pin_Number').val();

            // Fetch pin data from the unit table
            $.ajax({
                type: 'GET',
                url: '/get-pin-data',
                data: { numPins: numPins },
                success: function (pinData) {
                    // Add pin boxes dynamically
                    for (var i = 0; i < numPins; i++) {
                        var pinBox = $('<div>').addClass('pin-box m-2 p-3');

                        if (i < pinData.length) {
                            // If there is data for the pin, display it
                            var pin = pinData[i];
                            var backgroundColor = (pin && pin.Status === "1") ? 'green' : 'red';
                            var pinContent = pin ? 'Pin ' + pin.Pin_Num + ' ' + pin.Pin_Name : 'N/A';
                            pinBox.html('<label for="pin' + (pin ? pin.id : i) + '" class="text-center">' + pinContent + '</label>');
                            pinBox.css('background-color', backgroundColor);
                        } else {
                            // If there is no data for the pin, display a grey box with N/A
                            pinBox.html('<label class="text-center">N/A</label>');
                            pinBox.css('background-color', 'grey');
                        }

                        $('#PinContainer').append(pinBox);
                    }
                },
                error: function (error) {
                    console.error(error); // Log error response
                    // Handle error if needed
                }
            });
        }

        // Call the updatePins function when the Pin_Number dropdown changes
        $('#Pin_Number').change(updatePins);

        // Initial call to set up the pins based on the default selected number of pins
        updatePins();

        // Reset button event listener
        $('#resetSettings').click(function () {
            // Clear input fields and reset pin containers
            $('#deviceForm')[0].reset();
            updatePins();
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
