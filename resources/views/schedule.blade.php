<link rel="stylesheetbody" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .card-container {
        display: flex;
        justify-content: space-between; /* Optional: adjust as needed */
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

    .column {
        flex: 1;
        margin-right: 20px;
    }

    .row {
        margin-bottom: 1px;
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

    .card-body.custom {
        padding: 0;
    }

</style>

<div class="card-deck">
    
  <div class="card">

    <div class="card-header" style="text-align: left">
        <h1>Schedule Action</h1>
        <p id="currentTime"></p>
    </div><!-- cardheader end -->

    <div class="card-body">
        <form method="post" action="{{ route('store.schedule') }}">
            @csrf
        
            <div class="form-group">
                <label for="device" >Device:</label>
                <select name="device" id="device"  class="custom-select w-100">
                    <option disabled selected>Utoolity+</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Action:</label>
                <select name="description" id="description" class="custom-select w-100" required>
                    <option disabled selected>Select Action</option>
                    <option value="ON">ON</option>
                    <option value="OFF">OFF</option>
                </select>
            </div>

            <div class="form-group">
                <button type="button" name="custom_schedule" class="btn btn-dark" id="showHideButton">Custom Schedule</button>
            </div>

            <div class="date-time-group">

                <div class="form-group">
                    <label for="dateTimePicker3">From: Date & Time:</label>
                    <div class="inline-picker input-group">
                        <input type="text" name="event_datetime" id="dateTimePicker3" class="form-control" required autocomplete="off"/>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="calendar-icon">📅</i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dateTimePicker4">To: Date & Time:</label>
                    <div class="inline-picker input-group">
                        <input type="text" name="event_datetime_off" id="dateTimePicker4" class="form-control" required autocomplete="off"/>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="calendar-icon">📅</i></span>
                            </div>
                    </div>
                </div>

                <button type="submit" name="custom_schedule" class="btn btn-primary w-100" id="sub">Set Schedule</button>
            </div> <!-- date-time-group -->
      
            <div class="other-form-elements">

                <div class="form-group">
                    <label for="yearmonth">Month & Year:</label>
                    <input type="text" class="form-control" name="yearmonth" id="yearmonth" placeholder="Select Month & Year"/>
                </div>

                <div class="form-group">
                    <label for="day">Day:</label>
                        <select name="day" id="day" class="custom-select w-100" required>
                            <option disabled selected>Select Day</option>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                            <option>Sunday</option>
                        </select>
                </div>

                <div class="form-group">
                    <label for="fromtime">From:</label>
                    <div class="inline-picker input-group">
                        <input type="text" name="fromtime" id="dateTimePicker" class="form-control" required autocomplete="off" placeholder="From"/>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="calendar-icon">⏰</i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="totime">To:</label>
                    <div class="inline-picker input-group">
                        <input type="text" name="totime" id="dateTimePicker2" class="form-control" required autocomplete="off" placeholder="To"/>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="calendar-icon">⏰</i></span>
                        </div>
                    </div>
                </div> 

                <button type="submit"  class="btn btn-primary w-100" id="sub">Set Schedule</button>

            </div> <!-- other-form-elements -->

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

                    @if(isset($errorMessage))
                        <div class="alert alert-danger">
                            {{ $errorMessage }}
                            @if(!empty($failedSchedules))
                                <ul>
                                    @foreach($failedSchedules as $failedSchedule)
                                        <li>{{ $failedSchedule['start']->format('Y-m-d h:i A') }} - {{ $failedSchedule['end']->format('Y-m-d h:i A') }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

<!-- Rest of your Blade content -->


        </form><!-- form end --> 

    </div><!-- cardbody end -->   
    
  </div><!-- card end -->

<!-- ---------------------------------------------------------------------- -->

  <div class="card">
    <div class="card-header" style="text-align: left">
            <h1>Existing Schedule</h1>        
    </div><!-- cardheader end -->

    <div class="card-body">
        <p class="card-text" id="relatedSchedulesList"></p>
        <button id="prevBtn" class="btn btn-secondary" style='width: auto;'>Prev</button>
        <button id="nextBtn" class="btn btn-secondary" style='width: auto;'>Next</button>
 
    </div><!-- cardbody end -->

    <div class="card-header" style="text-align: left">
        <h1>Related Schedule</h1>
    </div><!-- cardheader2 end -->

    <div class="card-body">
        <p class="card-text" id="other"></p>
    </div><!-- cardbody2 end -->
      
  </div><!-- card deck end2 -->

<!-- ---------------------------------------------------------------------- -->
</div><!-- card deck end -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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
</script> <!-- Current time script -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script>
    $(document).ready(function(){
        $("#yearmonth").datepicker({
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months",
            autoclose:true
        });   
    })
</script><!-- year and month picker -->

<script>
    flatpickr("#dateTimePicker", {
        enableTime: true, // Enable time selection
        noCalendar: true, // Disable the calendar
        dateFormat: "h:i K", // Set the desired time format with AM/PM
        altFormat: "h:i K", // Set the format for the alternative input field
        altInput: true, // Use an alternative input field
        time_24hr: false, // Use 12-hour time format with AM/PM
    });
</script><!-- dateTimePicker timepicker -->

<script>
    flatpickr("#dateTimePicker2", {
        enableTime: true, // Enable time selection
        noCalendar: true, // Disable the calendar
        dateFormat: "h:i K", // Set the desired time format with AM/PM
        altFormat: "h:i K", // Set the format for the alternative input field
        altInput: true, // Use an alternative input field
        time_24hr: false, // Use 12-hour time format with AM/PM
    });
</script><!-- dateTimePicker2 timepicker2 -->

<script>
    flatpickr("#dateTimePicker3", {
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
</script><!-- dateTimePicker3 specific date and time -->

<script>
    flatpickr("#dateTimePicker4", {
        enableTime: true, // Enable time selection
        dateFormat: "Y-m-d H:i", // Set the desired date and time format
        altInput: true, // Use an alternative input field
        altFormat: "F j, Y H:i", // Set the format for the alternative input field
        onChange: function(selectedDates, dateStr, instance) {
            // Update the result paragraph with the selected date and time
            document.getElementById("relatedSchedulesList").textContent = "Selected Date & Time: " + dateStr;
        }
    });
</script><!-- dateTimePicker4 specific off date and time -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        var currentPage = 1;
        var schedulesPerPage = 2;

        $('#description, #yearmonth, #day, #dateTimePicker, #dateTimePicker2').on('change', function () {
            currentPage = 1; // Reset page to 1 when filters change
            updateRelatedSchedules();
        });

        $('#nextBtn').on('click', function () {
            currentPage++;
            updateRelatedSchedules();
        });

        $('#prevBtn').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                updateRelatedSchedules();
            }
        });

        function updateRelatedSchedules() {
            var description = $('#description').val();
            var yearmonth = $('#yearmonth').val();
            var day = $('#day').val();
            var fromtime = $('#dateTimePicker').val();
            var totime = $('#dateTimePicker2').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: '/update-related-schedules',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    description: description,
                    yearmonth: yearmonth,
                    day: day,
                    fromtime: fromtime,
                    totime: totime,
                    page: currentPage,
                    perPage: schedulesPerPage
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function (data) {
                    if (data.trim() === '') {
                        $('#relatedSchedulesList').html('<p>No existing schedules found.</p>');
                    } else {
                        $('#relatedSchedulesList').html(data);
                    }
                },
                error: function (error) {
                    console.error('Error updating related schedules:', error);
                }
            });
        }
    });

</script><!-- filter for to default datepicker -->

<script>
    var storedDateTimeValue = localStorage.getItem('dateTimeValue'); // Retrieve the stored datetime value from localStorage
    // Set the datetime value back to the input field if it exists
    if (storedDateTimeValue) {
        $('#dateTimePicker3').val(storedDateTimeValue);
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
    
        $('#description, #dateTimePicker3, #dateTimePicker4').change(function () {
            var event_datetime = $('#dateTimePicker3').val();
            var event_datetime_off = $('#dateTimePicker4').val();
            var description = $('#description').val();
            
            $.ajax({
                type: 'GET',
                url: '/get-related-data1',
                data: { event_datetime: event_datetime, 
                        event_datetime_off: event_datetime_off, 
                        description: description },
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
    
                                content += '<div class="container">';

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>Schedule Details:</p>';
                                content += '</div>';
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<p> State: </p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>'+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                                content += '</div>';
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<label class="switch">';
                                content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                                content += '<span class="slider round"></span>';
                                content += '</label>';  
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>'+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                                content += '</div>';
                                content += '<div class="col">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '</div>';//row end
                                
                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>' +'Action: '+ item.description + '</p>';
                                content += '</div>';
                                content += '<div class="col">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '</div>';//container end
                                content += '</div>';//container fluid end
                                
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
</script><!-- existing schedule for datetimepicker3 -->

<script src="{{ asset('js/dynamicUpdate.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#dateTimePicker3').change(function () {
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
    
                                content += '<div class="container">';

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>Schedule Details:</p>';
                                content += '</div>';
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<p> State: </p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>'+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                                content += '</div>';
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<label class="switch">';
                                content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                                content += '<span class="slider round"></span>';
                                content += '</label>';  
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>'+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                                content += '</div>';
                                content += '<div class="col">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '</div>';//row end
                                
                                content += '<div class="row">';
                                content += '<div class="col">';
                                content += '<p>' +'Action: '+ item.description + '</p>';
                                content += '</div>';
                                content += '<div class="col">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '</div>';//container end
                                content += '</div>';//container fluid end
                                
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
</script><!-- related schedule for datetimepicker3 -->


<script>
    function formatDatetimeForInput(datetime) {
        var formattedDatetime = new Date(datetime).toISOString().slice(0, 16);
        return formattedDatetime;
    }
</script><!-- formatDatetimeForInput -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
    // Initially hide the date and time input fields and disable them
    $(".date-time-group").hide();
    $(".date-time-group input, .date-time-group select").prop("disabled", true);

    // Handle button click
    $("#showHideButton").click(function() {
        // Toggle visibility
        $(".date-time-group").toggle();
        $(".other-form-elements").toggle();

        // Set the value of the hidden field based on the button click
        $("#customScheduleClicked").val($(".date-time-group").is(":visible") ? "1" : "0");

        // Disable/enable other form elements based on the button click
        if ($(".date-time-group").is(":visible")) {
            // Enable inputs in date-time-group and set the name attribute
            $(".date-time-group input, .date-time-group select").prop("disabled", false);
            // Disable inputs in other-form-elements
            $(".other-form-elements input, .other-form-elements select").prop("disabled", true);
        } else {
            $(".date-time-group input, .date-time-group select").prop("disabled", true);
            $(".other-form-elements input, .other-form-elements select").prop("disabled", false);
        }
    });

    // Handle form submission
    $("#sub").click(function() {
        // Rename fields to match validation rules if custom_schedule button is clicked
        if ($("#customScheduleClicked").val() === "1") {
            $("input[name='event_datetime_disabled']").attr("name", "event_datetime");
            $("input[name='event_datetime_off_disabled']").attr("name", "event_datetime_off");
        }
        $("form").submit();
    });
    });

</script> <!-- hide button -->
