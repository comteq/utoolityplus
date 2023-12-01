
<link rel="stylesheetbody" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<style>
    .card-container {
        max-width: 700px; /* Set a maximum width for the card */
        width: 100%;
        margin: auto;
    }

    .inline-picker {
        display: flex;
     }

    .inline-picker input {
        flex: 1;
        margin-right: 0px;
    }
    .calendar-icon {
        color: #fff;
        }

    .btn-primary {
        width: 100%; /* Make the button take the full width of the parent container */
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .schedule-details {
        display: flex;
        align-items: center;
    }

    .container-fluid {
        display: flex;
        justify-content: space-between;
        padding-left: 20px;
    }
    #schedule-1{
        display: flex;
        flex-direction: column;
    }
    .column {
        /* flex: 1; */
        align-self: center;
        /* display: flex;
        flex-direction: column; */
        margin-right: 20px;
    }
    .perSched{
        display: flex;
        flex-direction: row;
    }
    .row {
         margin-bottom: 1px;
         display: flex;
         flex-direction: column;
    }

    #sub{
        margin-top: 20px;
    }

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right !important;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

@extends('home')

@section('content')
    
<div class="card-container" id="schedule" class="content-section">
    <div class="card">
        <div class="card-header">
            <h1>Schedule Action</h1>
            <p id="currentTime"></p>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('store.schedule') }}">
                @csrf

                <label for="device"  style="margin-bottom: 0; padding: 5px;">Device:</label>
                <select name="device" id="device" class="custom-select">
                    <option disabled selected>Utoolity+</option>
                </select>

                <label for="description"  style="margin-bottom: 0; padding: 5px;">Action:</label>
                <select name="description" id="description" class="custom-select" required>
                <option disabled selected>Select Action</option>
                    <option>ON</option>
                    <option>OFF</option>
                </select>

                <label for="dateTimePicker">From: Date & Time:</label>
                <div class="inline-picker input-group">
                    <input type="text" name="event_datetime" id="dateTimePicker" class="form-control" required autocomplete="off"/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="calendar-icon">📅</i>
                        </span>
                    </div>
                </div>

                <label for="dateTimePicker2">To:   Date & Time:</label>
                <div class="inline-picker input-group">
                    <input type="text" name="event_datetime_off" id="dateTimePicker2" class="form-control" required autocomplete="off"/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="calendar-icon">📅</i>
                        </span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary" id="sub">Set Schedule</button>
                </form>
        </div> <!-- card-body end -->
    </div><!-- card end -->

    <div class="card">
        <div class="card-header"><p>Existing Schedule:<p></div>
        <div class="card-body"><p class="card-text" id="relatedSchedulesList"></p></div>
        <div class="card-header"><p>Related Schedule:<p></div>
        <div class="card-body"><p class="card-text" id="other"></p></div>
    </div><!-- card end -->
</div><!-- card container end -->
@endsection


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

<script>
    function updateCurrentTime() {
        var currentTimeElement = document.getElementById("currentTime");
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';

        // Convert to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight (12 AM)

        // Add leading zeros if needed
        minutes = minutes < 10 ? "0" + minutes : minutes;

        var formattedTime = hours + ":" + minutes + " " + ampm;
        currentTimeElement.innerText = "Current time: " + formattedTime;
        }

        // Update the time every second
        setInterval(updateCurrentTime, 1000);

        // Initial call to set the initial time
        updateCurrentTime();
</script>




<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>



<script>
    // Initialize flatpickr on the dateTimePicker input
    flatpickr("#dateTimePicker", {
        enableTime: true, // Enable time selection
        dateFormat: "Y-m-d H:i", // Set the desired date and time format
        altInput: true, // Use an alternative input field
        altFormat: "F j, Y H:i", // Set the format for the alternative input field
        noCalendar: false, // Ensure that the calendar is shown, which helps exclude milliseconds
        onChange: function(selectedDates, dateStr, instance) {
            // Update the result paragraph with the selected date and time
            document.getElementById("relatedSchedulesList").textContent = "Selected Date & Time: " + dateStr;

        }
    });
</script>

<script>
    // Initialize flatpickr on the dateTimePicker input
    flatpickr("#dateTimePicker2", {
        enableTime: true, // Enable time selection
        dateFormat: "Y-m-d H:i", // Set the desired date and time format
        altInput: true, 
        altFormat: "F j, Y H:i", // Set the format for the alternative input field
        onChange: function(selectedDates, dateStr, instance) {
            // Update the result paragraph with the selected date and time
            document.getElementById("selectionResult").textContent = "Selected Date & Time: " + dateStr;
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
// Retrieve the stored datetime value from localStorage
var storedDateTimeValue = localStorage.getItem('dateTimeValue');
// Set the datetime value back to the input field if it exists
if (storedDateTimeValue) {
    $('#dateTimePicker').val(storedDateTimeValue);
}

$(document).ready(function () {
    $(document).on('change', '.toggleCheckbox', function () {
        $('.toggleCheckbox').not(this).prop('checked', false);// Uncheck other checkboxes
        var itemId = $(this).data('id');// Get the item id from the checkbox data-id attribute
    });

    $(document).on('click', '.delete-btn', function () {
        var itemId = $(this).data('id');
        deleteSchedule(itemId);
    });
 
    $('#dateTimePicker').change(function () {
        var event_datetime = $(this).val();
        
        $.ajax({
            type: 'GET',
            url: '/get-related-data1',
            data: { event_datetime: event_datetime },
            success: function (data) {
                // Check if the 'relatedData' property exists
                if (data.hasOwnProperty('relatedData1')) {
                    // Update the content dynamically
                    var content = '';

                    // Check if there is data in the array
                    if (data.relatedData1.length > 0) {
                        // Customize this part based on your actual data structure
                        data.relatedData1.forEach(function (item) {
                            var modalId = 'myModal-' + item.id;

                            content += '<div class="row">'
                            content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                            content += '<div class="perSched">';
                            content += '<div class="column">';
                            content += '<div class="row">';
                            content += '<p>' +'Time: '+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                            content += '</div>';
                            content += '<div class="row">';
                            content += '<p>' +'Date: '+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                            content += '</div>';
                            content += '<div class="row">';
                            content += '<p>' +'Action: '+ item.description + '</p>';
                            content += '</div>';
                            content += '</div>';

                            content += '<div class="column d-flex justify-content-end">';
                            content += '<div class="schedule-details">';
                            content += '<label class="switch">';
                            content += '<input type="checkbox" data-id="' + item.id + '" class="toggleCheckbox slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';
                            content += '<span class="slider round"></span>';
                            content += '</label>';  
                            content += '</div>';
                            content += '</div>';
                            
                            content += '<div class="column">'
                            content += '<div class="schedule-details" >';
                            content += '<div class="row">';
                            content += '<button style="margin-bottom:5px"; class="btn btn-dark delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">🗑️</button>';
                            // content += '</div>';// row end
                           
                            // content += '<div class="row">';
                            content += '<button id="myBtn"  class="btn btn-dark" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">✏️</button>';
                            content += '<div id="' + modalId + '" class="modal">';

                            content += '<div class="modal-content">';
                            content += '<span class="close" style="text-align:right"; data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                            content += '<form id="editForm-' + item.id + '">';
                            content += '@csrf';
                            content += '@method("PUT")';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime">From:</label>';
                            content += '<input type="datetime-local" name="event_datetime" class="form-control" value="' + formatDatetimeForInput(item.event_datetime) + '">';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime_off">To:</label>';
                            content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '">';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="description">Action:</label>';
                            content += '<select id="description" name="description" class="form-control">';
                            content += '<option value="ON" ' + (item.description === 'ON' ? 'selected' : '') + '>ON</option>';
                            content += '<option value="OFF" ' + (item.description === 'OFF' ? 'selected' : '') + '>OFF</option>';
                            content += '</select>';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="state">State:</label>';
                            content += '<select id="state" name="state" class="form-control">';
                            content += '<option value="Active" ' + (item.state === 'Active' ? 'selected' : '') + '>Active</option>';
                            content += '<option value="In-Active" ' + (item.state === 'In-Active' ? 'selected' : '') + '>In-Active</option>';
                            content += '</select>';
                            content += '</div>';

                            content += '<div class="form-group" >';
                            content += '<br>';
                            content += '<button type="button" class="form-control" onclick="submitForm(' + item.id + ')">Submit</button>';
                            content += '</div>';
                            content += '</form>';
          
                            content += '</div>';
                            content += '</div>';

                            content += '</div>';// row end
                            content += '</>';// column end
                            content += '</div>';
            
                            content += '</div>';
                            content += '</div>';
                            content += '<hr>'; // Add a separator
                        });
                    } else {
                        content = 'No Related Schedule';
                    }

                    $('#relatedSchedulesList').html(content);
                } else {
                    console.error('Invalid response format. Missing "relatedData" property.');
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>


<script src="{{ asset('js/dynamicUpdate.js') }}"></script>
<script>
$(document).ready(function () {
    
    $(document).on('click', '.delete-btn', function () {
        var itemId = $(this).data('id');
        deleteSchedule(itemId);
    });

    $('#dateTimePicker').change(function () {
        var event_datetime = $(this).val();

        $.ajax({
            type: 'GET',
            url: '/get-related-data',
            data: { event_datetime: event_datetime },
            success: function (data) {
                // Check if the 'relatedData' property exists
                if (data.hasOwnProperty('relatedData')) {
                    // Update the content dynamically
                    var content = '';

                    // Check if there is data in the array
                    if (data.relatedData.length > 0) {
                        // Customize this part based on your actual data structure
                        data.relatedData.forEach(function (item) {
                            var modalId = 'myModal-' + item.id;
                            
                            content += '<div class="container-fluid">';
                            content += '<div class="column">';
                            content += '<div class="row">';
                            content += '<p>' +'Time: '+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                            content += '</div>';
                            content += '<div class="row">';
                            content += '<p>' +'Date: '+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                            content += '</div>';
                            content += '<div class="row">';
                            content += '<p>' +'Action: '+ item.description + '</p>';
                            content += '</div>';
                            content += '</div>';
                            content += '<div class="column d-flex justify-content-end">';
                            content += '<div class="schedule-details">';
                            content += '<label class="switch">';
                            content += '<input type="checkbox" id="toggleButton" class="slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';
                            content += '<span class="slider round"></span>';
                            content += '</label>';
                            content += '</div>';
                            content += '</div>';

                            content += '<div class="column">'
                            content += '<div class="schedule-details" >';
                            content += '<div class="row">';
                            content += '<button style="margin-bottom:5px"; class="btn btn-dark delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">🗑️</button>';
                            // content += '</div>';// row end
                           
                            // content += '<div class="row">';
                            content += '<button id="myBtn"  class="btn btn-dark" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">✏️</button>';
                            content += '<div id="' + modalId + '" class="modal">';

                            content += '<div class="modal-content">';
                            content += '<span class="close" style="text-align:right"; data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                            content += '<form id="editForm-' + item.id + '">';
                            content += '@csrf';
                            content += '@method("PUT")';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime">From:</label>';
                            content += '<input type="datetime-local" name="event_datetime" class="form-control" value="' + formatDatetimeForInput(item.event_datetime) + '">';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime_off">To:</label>';
                            content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '">';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="description">Action:</label>';
                            content += '<select id="description" name="description" class="form-control">';
                            content += '<option value="ON" ' + (item.description === 'ON' ? 'selected' : '') + '>ON</option>';
                            content += '<option value="OFF" ' + (item.description === 'OFF' ? 'selected' : '') + '>OFF</option>';
                            content += '</select>';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="state">State:</label>';
                            content += '<select id="state" name="state" class="form-control">';
                            content += '<option value="Active" ' + (item.state === 'Active' ? 'selected' : '') + '>Active</option>';
                            content += '<option value="In-Active" ' + (item.state === 'In-Active' ? 'selected' : '') + '>In-Active</option>';
                            content += '</select>';
                            content += '</div>';

                            content += '<div class="form-group" >';
                            content += '<br>';
                            content += '<button type="button" class="form-control" onclick="submitForm(' + item.id + ')">Submit</button>';
                            content += '</div>';
                            content += '</form>';
          
                            content += '</div>';
                            content += '</div>';

                            content += '</div>';// row end
                            content += '</>';// column end
                            content += '</div>';
                            content += '</div>';
                            content += '<hr>'; // Add a separator
                        });
                    } else {
                        content = 'No Related Schedule';
                    }

                    $('#other').html(content);
                } else {
                    console.error('Invalid response format. Missing "relatedData" property.');
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    });
});

function toggleButtonClick2(itemId) {
    $.ajax({
        type: 'POST',
        url: '/update-state/' + itemId, // Update the URL with the itemId in the URL
        data: {
            _token: '{{ csrf_token() }}', // Add CSRF token for Laravel
        },
        success: function (response) {
            if (response.success) {
                console.log('Database updated successfully');
                //  updateToggleButtons(itemId); 
            } else {
                console.error('Error updating database:', response.message);
            }
        },
        error: function (error) {
            console.error('Error updating database:', error);
        }
    });
}

function deleteSchedule(itemId) {
    // Store the datetime value before the reload
    var dateTimeValue = $('#dateTimePicker').val();

    $.ajax({
        type: 'DELETE',
        url: '/delete-schedule/' + itemId,
        data: {
            _token: '{{ csrf_token() }}',
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log('Database deleted successfully');
            $('#schedule-' + itemId).remove();

            // Store the datetime value in localStorage
            localStorage.setItem('dateTimeValue', dateTimeValue);

        },
        error: function (error) {
            console.error('Error deleting schedule:', error);

            // Check the error status and responseText
            if (error.status === 401) {
                console.error('Unauthorized access. Make sure you are authenticated.');
            } else {
                console.error('Unknown error. Check the server logs for more information.');
            }
        }
    });
}
</script>

<script>
function openModal(itemId) {
    var modalId = 'myModal-' + itemId;
    var modal = document.getElementById(modalId);
    modal.style.display = "block";
}

function closeModal(itemId) {
    var modalId = 'myModal-' + itemId;
    var modal = document.getElementById(modalId);
    modal.style.display = "none";
}

function submitForm(itemId) {
    var formId = 'editForm-' + itemId;
    var formData = $('#' + formId).serialize();

    $.ajax({
        type: 'PUT',
        url: '/update-schedule/' + itemId,
        data: formData,
        success: function (response) {
            console.log('Schedule updated successfully');
            closeModal(itemId);
            var updatedDateTimeValue = $('#dateTimePicker').val();
            localStorage.setItem('dateTimeValue', updatedDateTimeValue);
            $('#dateTimePicker').trigger('change');
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}

function formatDatetimeForInput(datetime) {
    var formattedDatetime = new Date(datetime).toISOString().slice(0, 16);
    return formattedDatetime;
}
</script>