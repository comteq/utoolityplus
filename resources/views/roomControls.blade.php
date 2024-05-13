<!DOCTYPE html>
<html lang="en">
<head>
    @include('nav')
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('js/sweetalert2@10.js') }}"></script>
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #ecedee;
        }

        .site-container {
            display: flex;
            flex-direction: column;
            height: 100%; /* Set height to 100% to match the body height */
            min-height: 100vh; /* Full height of the viewport */
        }


        .custom-card {
            flex: 1; /* Make the card expand to fill the remaining space */
        }

        .card-footer {
            margin-top: auto; /* Push the footer to the bottom */
        }

        .card {
            border: none; /* Remove the default card border */
            background-color: #ecedee;

        }

        .custom {
            margin-top: 74px;
        }

        .border {
            background: rgba(107, 214, 119, 0.3);
        }

        #label{
            font-size: 4rem;
            color: #333;
            text-align: center;
            border-radius: 10px;
            width: 500px;
            height: 60px;
            line-height: 60px;
            margin: 0 auto; 
            text-shadow: 2px 3px 3px rgba(250, 250, 250);
            font-weight: bold;
        }

        .clock {
        display: inline-block;
        font-size: 3em;
        font-weight: bold;
        text-shadow: 2px 2px 2px rgba(0, 0, 0);
        color: #FAFAFA;
    }
    .flip-clock {
        display: inline-block;
        perspective: 1000px;
    }
    .flip-clock .flip-unit-container {
        display: inline-block; /* Display inline */
        margin-right: 10px; /* Add some spacing between units */
        overflow: hidden;
        position: relative;
    }
    .flip-clock .flip-unit-container .upper-card,
    .flip-clock .flip-unit-container .lower-card {
        backface-visibility: hidden;
        position: absolute;
        left: 0;
        top: 0;
        transition: transform 0.5s;
    }
    .flip-clock .flip-unit-container .lower-card {
        transform: rotateX(180deg);
    }
    .ampm {
        display: inline-block;
        vertical-align: top; /* Align to the top of the line */
        margin-left: 5px; /* Add some spacing between seconds and AM/PM */
    }
    .separator {
        display: inline-block;
        vertical-align: top; /* Align to the top of the line */
        font-size: 0.8em; /* Adjust the size of the separator */
        margin: 0 5px; /* Add some spacing around the separator */
    }
        
    </style>

</head> 
<body>

    <div class="site-container" style="padding:1%">
        <div class="custom-card">

            <div class="card-header" style="text-align:center">
                
                <h1 id="label"> Controls</h1>
                <div class="clock">
                    <div id="hours" class="flip-clock"></div>
                    <div class="separator">:</div>
                    <div id="minutes" class="flip-clock"></div>
                    <div class="separator">:</div>
                    <div id="seconds" class="flip-clock"></div>
                    <div id="ampm" class="ampm"></div>
                </div>
            </div>
        
            <div class="card-body">
        
                <div class="card-deck">
        
                {{-- AC Card --}}
                <div class="card border-0">
                    @php
                        $acState = App\Models\unit::find(1)->Status;
                        $acImage = $acState === '1' ? 'ac-on.png' : 'ac-off.png';
                    @endphp

                    <img class="card-img-top mx-auto img-fluid" src="{{ asset("images/$acImage") }}" alt="ac" style="height: auto; width: 550px;"> <!-- Add 'mx-auto' class for horizontal centering -->
                    <div class="card-body custom" style="text-align:center">
                        <form id="acForm" action="{{ route('update-ac', ['id' => 1]) }}" method="post">
                            @csrf
                            <input type="hidden" name="acValue" id="acValue" value="{{ $acState }}"> <!-- Set the value to be sent dynamically -->
                            <button type="button" id="acButton" class="btn {{ $acState === '1' ? 'btn-success' : 'btn-danger' }} btn-lg rounded-circle" onclick="toggleAC()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                    <path d="M7.5 1v7h1V1z"/>
                                    <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    
                </div>

                {{-- Lights Card --}}
                <div class="card border-0">
                    @php
                        $lightsState = App\Models\unit::find(2)->Status; // Retrieve 'Status' for Unit with ID 2
                        $lightsImage = $lightsState === '1' ? 'light-on.png' : 'light-off.png';
                    @endphp

                    <img class="card-img-top mx-auto img-fluid" src="{{ asset("images/$lightsImage") }}" alt="Lights" style="height: 250px; width: 250px;">
                    <div class="" style="text-align:center">
                        <form id="lightsForm" action="{{ route('update-lights', ['id' => 2]) }}" method="post">
                            @csrf
                            <input type="hidden" name="lightsValue" id="lightsValue" value="{{ $lightsState }}">
                            <button type="button" id="lightsButton" class="btn {{ $lightsState === '1' ? 'btn-success' : 'btn-danger' }} btn-lg rounded-circle" onclick="toggleLights()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                                    <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6m3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>

                </div><!-- card deck -->
        
            </div>
        
        </div>
    </div>

<script>
    function confirmPowerAction() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to change the AC state.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('powerForm').submit();
            }
        });
    }

    function confirmLightsAction() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to change the lights state.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('lightsForm').submit();
            }
        });
    }
</script>


<script>

// ARDUINO SCRIPTING
function toggleAC() {
    var acValueInput = document.getElementById('acValue');
    var acState = acValueInput.value;
    var acButton = document.getElementById('acButton');
    var lightsButton = document.getElementById('lightsButton');

    // Toggle the AC state
    var newACState = acState === '1' ? '0' : '1';

    // Update the hidden input value
    acValueInput.value = newACState;

    // Disable buttons during data sending
    acButton.disabled = true;
    lightsButton.disabled = true;

    // Submit the form
    document.getElementById('acForm').submit();
}

function toggleLights() {
    var lightsValueInput = document.getElementById('lightsValue');
    var lightsState = lightsValueInput.value;
    var acButton = document.getElementById('acButton');
    var lightsButton = document.getElementById('lightsButton');

    // Toggle the lights state
    var newLightsState = lightsState === '1' ? '0' : '1';

    // Update the hidden input value
    lightsValueInput.value = newLightsState;

    // Disable buttons during data sending
    acButton.disabled = true;
    lightsButton.disabled = true;

    // Submit the form
    document.getElementById('lightsForm').submit();
}
</script>

<script>
    // Function to show SweetAlert pop-up
    function showNoDevicesAlert() {
        Swal.fire({
            title: 'No connected Devices',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
</script>

<script>
    function checkForUpdates() {
        fetch("{{ route('check-updates') }}")
            .then(response => response.json())
            .then(data => {
                if (data.hasUpdates) {
                    location.reload(); // Refresh the page if updates are detected
                }
            });
    }

    // Poll for updates every 5 seconds (adjust the interval as needed)
    setInterval(checkForUpdates, 5000); // 5000 milliseconds = 5 seconds
</script>

<!-- timer -->
<script>
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert hours to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight (0 hours)

        document.getElementById('hours').innerText = pad(hours);
        document.getElementById('minutes').innerText = pad(minutes);
        document.getElementById('seconds').innerText = pad(seconds);
        document.getElementById('ampm').innerText = ampm;
    }

    function pad(number) {
        return number < 10 ? '0' + number : number;
    }

    setInterval(updateClock, 1000);
</script>


@if(Session::has('showAlert') && Session::get('showAlert'))
    <script>
        // Check for the showAlert flag and trigger SweetAlert
        showNoDevicesAlert();
    </script>
@endif

</body>
</html>

@include('footer')