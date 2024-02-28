@include('nav')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/external-styles.css') }}">

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
        width: 48%;
    }

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

    .hr-text {
        line-height: 1em;
        position: relative;
        outline: 0;
        border: 0;
        color: black;
        text-align: center;
        height: 1.5em;
        opacity: .5;
        &:before {
            content: '';
            background: linear-gradient(to right, transparent, #818078, transparent);
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            }
        &:after {
            content: attr(data-content);
            position: relative;
            display: inline-block;
            color: black;

            padding: 0 .5em;
            line-height: 1.5em;
            color: #818078;
            background-color: #fcfcfa;
        }
    }

    .error-message {
        color: red;
    }

    .button-container {
        display: flex;
        align-items: center;
    }

    .button-container p {
        margin-right: auto; /* Push the paragraph to the left */
    }

    .button-container button {
        margin-left: 5px; 
    }


</style>

<div class="card" style="overflow-x: hidden;">
    <div class="card-deck">

    <div class="card" style="margin-left: 30px; margin-top: 20px; margin-bottom: 30px;">
        <div class="card-header" style="text-align: left">
            <h1>Schedule Action</h1>
            <p id="currentTime"></p>
           
        </div><!-- cardheader end -->


        <div class="card-body">
            <form method="post" action="{{ route('storeadmin.schedule') }}" id="form">
                @csrf
            
                <div class="form-group">
                    <label for="device" >Device:</label>
                    <select name="device" id="device"  class="custom-select w-100">
                        <option disabled selected>Utoolity+</option>
                    </select>
                </div>

                <div class="date-time-group">

                    <div class="form-group">
                        <label for="description">Action:</label>
                        <select name="description" id="description1" class="custom-select w-100" required>
                            <option Selected value="ON">ON</option>
                        </select>
                        <!-- <span id="existingactionerror" class="text-danger"></span> -->
                    </div>    

                    <div class="form-group">
                        <label for="dateTimePicker3">From: Date & Time:</label>
                        <div class="inline-picker input-group">
                            <input type="text" name="event_datetime" id="dateTimePicker3" class="form-control" required autocomplete="off" required/>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="calendar-icon">📅</i></span>
                            </div>
                        </div>
                        <span id="existingSchedulesError" class="text-danger"></span>
                    </div>

                    <input type="hidden" name="fromtime_hidden-custom" id="fromtime_hidden-custom" value="" />

                    <div class="form-group">
                        <label for="dateTimePicker4">To: Date & Time:</label>
                        <div class="inline-picker input-group">
                            <input type="text" name="event_datetime_off" id="dateTimePicker4" class="form-control" required autocomplete="off" required/>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="calendar-icon">📅</i></span>
                                </div>
                        </div>
                        <span id="existingSchedulesErrorTo" class="text-danger"></span>
                        <span id="pasterror" class="text-danger"></span>
                        <span id="separateError" class="text-danger"></span>
                        <span id="datespanerror" class="text-danger"></span>
                    </div>

                    <button type="submit" name="custom_schedule" class="btn btn-primary w-100" id="sub" data-custom-id="subcustom">Set Schedule</button>
                </div> <!-- date-time-group -->
        
                <div class="other-form-elements">

                    <div class="form-group">
                        <label for="description">Action:</label>
                        <select name="description" id="description" class="custom-select w-100" required>
                            <option Selected value="ON">ON</option>
                        </select>
                        <!-- <span id="existingactionerror" class="text-danger"></span> -->
                    </div>

                    <div class="form-group">
                        <label for="yearmonth">Month & Year:</label>
                        <input type="text" class="form-control" name="yearmonth" id="yearmonth" placeholder="Select Month & Year" required/>
                        <div id="yearmonthError" class="text-danger"></div>
                    </div>

                    <div id="result"></div>

                    <div class="form-group">
                        <label for="day">Day:</label>
                        <select name="day[]" id="day" class="custom-select w-100" multiple required>
                            <option disabled selected value="">Select Day</option>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                            <option>Sunday</option>
                        </select>
                        <div id="dayError" class="text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label for="fromtime">From:</label>
                        <div class="inline-picker input-group">
                            <input type="text" name="fromtime" id="dateTimePicker" class="form-control" required autocomplete="off" placeholder="From" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="calendar-icon">⏰</i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="totime">To:</label>
                        <div class="inline-picker input-group">
                            <input type="text" name="totime" id="dateTimePicker2" class="form-control" required autocomplete="off" placeholder="To" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="calendar-icon">⏰</i></span>
                            </div>
                        </div>
                        <span id="toError" class="text-danger"></span>
                    </div> 

                    <input type="hidden" name="fromtime_hidden" id="fromtime_hidden" value="" />

                    <button type="submit" name="default_schedule" class="btn btn-primary w-100" id="sub" data-custom-id="subd">Set Schedule</button>
                    
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

            </form><!-- form end --> 

            <div class="form-group" style="text-align: center; margin-top: 15px;">
                <button type="button" class="btn btn-light w-80" id="clearButton" >Reset</button>
            </div>

            <hr class="hr-text" data-content="OR">
            
                <div class="form-group" style="text-align: center">
                    <button type="button" name="custom_schedule" class="btn btn-dark" id="showHideButton">Custom Schedule</button>
                </div>

        </div><!-- cardbody end -->   
        
    </div><!-- card end -->

    <!-- ---------------------------------------------------------------------------------------------------------------------------->

    <div class="card" style="margin-right: 30px; margin-top: 20px; margin-bottom: 30px;">
        <div class="card-header" style="text-align: left">
            <h1>Existing Schedule</h1>        
        </div><!-- cardheader end -->

            <div class="card-body">
                <p class="card-text" id="relatedSchedulesList"></p>

                    <div class="button-container">
                        <p id="totalEntries">Total Entries: <span id="entryCount"></span></p>
                        <button id="prevBtn" class="btn btn-secondary" style='width: auto;'>Prev</button>
                        <button id="nextBtn" class="btn btn-secondary" style='width: auto; margin-right:50px'>Next</button>
                    </div>

                    <div class="button-container">
                        <p id="totalEntries4">Total Entries: <span id="entryCount4">--</span></p>
                        <button id="prevBtn1" class="btn btn-secondary" style='width: auto;'>Prev</button>
                        <button id="nextBtn1" class="btn btn-secondary" style='width: auto; margin-right:50px'>Next</button>
                    </div><!-- custom -->
                    
            </div><!-- cardbody end -->


        <div class="card-header" style="text-align: left" id="otherschedheader">
            <h1 id="textchangetorela">All Schedule</h1>
        </div><!-- cardheader2 end -->

        <div class="card-body" id="othersched">
            <p class="card-text" id="other"></p>

                <div class="button-container">
                    <p id="totalEntries2">Total Entries: <span id="entryCount2">--</span></p>
                    <button id="prevBtn3" class="btn btn-secondary" style='width: auto;'>Prev</button>
                    <button id="nextBtn3" class="btn btn-secondary" style='width: auto; margin-right:50px'>Next</button>
                </div>

                <div class="button-container">
                    <p id="totalEntries5" style='display: none;'>Total Entries: <span id="entryCount5" style='display: none;'>--</span></p>
                    <button id="prevBtn2" class="btn btn-secondary" style='width: auto; display: none;'>Prev</button>
                    <button id="nextBtn2" class="btn btn-secondary" style='width: auto; display: none; margin-right:50px'>Next</button>
                </div><!-- custom -->


        </div><!-- cardbody2 end -->
        
    </div><!-- card deck end2 -->

    </div><!-- card deck end -->
</div><!-- card deck end -->

@include('footer')
<!------------------------------------------------------------------------------------------------------------------------------->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="{{ asset('js/dynamicUpdate.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<!-- __________________________________________________pickers__________________________________________________________________-->

<script>
    $(document).ready(function(){
        $("#yearmonth").datepicker({
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months",
            autoclose:true,
            onSelect: calculateDays
        });   
    })
</script><!-- year and month picker -->

<script>
    flatpickr("#dateTimePicker", {
        enableTime: true, // Enable time selection
        noCalendar: true, // Disable the calendar
        dateFormat: "H:i", // Set the desired time format with AM/PM
        altFormat: "H:i", // Set the format for the alternative input field
        altInput: true, // Use an alternative input field
        time_24hr: true, // Use 12-hour time format with AM/PM
    });
</script><!-- dateTimePicker timepicker -->

<script>
    flatpickr("#dateTimePicker2", {
        enableTime: true, // Enable time selection
        noCalendar: true, // Disable the calendar
        dateFormat: "H:i", // Set the desired time format with AM/PM
        altFormat: "H:i", // Set the format for the alternative input field
        altInput: true, // Use an alternative input field
        time_24hr: true, // Use 12-hour time format with AM/PM
    });
</script><!-- dateTimePicker2 timepicker2 -->

<script>
    flatpickr("#dateTimePicker3", {
        enableTime: true, // Enable time selection
        dateFormat: "Y-m-d H:i:s", // Set the desired date and time format
        altInput: true, // Use an alternative input field
        altFormat: "F j, Y H:i", // Set the format for the alternative input field
        noCalendar: false, 
        time_24hr: true, 
        onChange: function(selectedDates, dateStr, instance) {
            // Update the result paragraph with the selected date and time
            document.getElementById("relatedSchedulesList").textContent = "Selected Date & Time: " + dateStr;
            console.log(dateStr);
        }
    });
</script><!-- dateTimePicker3 specific date and time -->

<script>
    flatpickr("#dateTimePicker4", {
        enableTime: true, // Enable time selection
        dateFormat: "Y-m-d H:i", // Set the desired date and time format
        altInput: true, // Use an alternative input field
        altFormat: "F j, Y H:i", // Set the format for the alternative input field
        time_24hr: true, // Use 12-hour time format with AM/PM
    });
</script><!-- dateTimePicker4 specific off date and time -->

<!-- _______________________________________________existing & related__________________________________________________________-->

<script>
    $(document).ready(function () {

        updateRelatedSchedulesadmin();
        
        $(document).on('change', '.toggleCheckbox1', function () {
            var clickedCheckbox = $(this);
            var clickedItemId = clickedCheckbox.data('id');
            var clickedEventDatetime = clickedCheckbox.data('event-datetime'); 

            $('.toggleCheckbox').not(clickedCheckbox).each(function () {
                var otherCheckbox = $(this);
                var otherEventDatetime = otherCheckbox.data('event-datetime'); 

                if (otherEventDatetime === clickedEventDatetime) {
                    otherCheckbox.prop('checked', false);
                }
            });
        });// Uncheck other checkboxes with the same event_datetime

        $('#yearmonth').on('change', function () {
            checkSelectedDate();
        });

        var currentPage = 1;
        var schedulesPerPage = 2;

        $('#description, #yearmonth, #day, #dateTimePicker, #dateTimePicker2').on('change', function () {
            currentPage = 1; // Reset page to 1 when filters change
            updateRelatedSchedulesadmin();
        });

        $('#nextBtn').on('click', function () {
            currentPage++;
            updateRelatedSchedulesadmin();
        });

        $('#prevBtn').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                updateRelatedSchedulesadmin();
            }
        });

        
        function updateRelatedSchedulesadmin() {
            var description = $('#description').val();
            var yearmonth = $('#yearmonth').val();
            var day = $('#day').val();
            var fromtime = $('#dateTimePicker').val();
            var totime = $('#dateTimePicker2').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({    
                url: '/update-related-schedules-admin',
                method: 'POST',
                data: {
                    description: description,
                    _token: csrfToken,
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
                if (data.hasOwnProperty('relatedData3')) {
                    var content = '';
                    if (data.relatedData3.length > 0) {
                        data.relatedData3.forEach(function (item) {
                            console.log('Item:', item);
                            var modalId = 'myModal-' + item.id;
                            content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                            content += '<div class="container">';

                            content += '<div class="row">';
                            content += '<div class="col-6">';
                            content += '<p>Schedule Details:</p>';
                            content += '</div>';
                            content += '<div class="col-3" >';
                            content += '<p> State: </p>';
                            content += '</div>';
                            content += '<div class="col-3" style="text-align: center;">';
                            content += '<p>Action:</p>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-6">';
                            content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                            content += '</div>';
                            content += '<div class="col-3"  style="text-align: center;">';
                            content += '<div class="schedule-details">';
                            content += '<label class="switch">';
                            content += '<input type="checkbox" data-id="' + item.id + '" data-event-datetime="'+ item.event_datetime+'" class="toggleCheckbox slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';
                            content += '<span class="slider round"></span>';
                            content += '</label>';
                            content += '</div>';
                            content += '</div>';
                            content += '<div class="col-3" style="text-align: center;">';
                            content += '<button id="myBtn" class="btn btn-info" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">';
                            content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">';
                            content += '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>';
                            content += '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>';
                            content += '</svg>';
                            content += '</button>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-6" >';
                            content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                            content += '</div>';
                            content += '<div class="col-3">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '<div class="col-3" style="text-align: center;">';
                            content += '<button class="btn btn-danger delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">';
                            content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                            content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                            content += '</svg>';
                            content += '</button>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-6">';
                            content += '<p>Action: ' + item.description + '</p>';
                            content += '</div>';
                            content += '<div class="col-3">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '<div class="col-3" style="text-align: center;">';
                            content += '<button class="btn btn-warning delete-btn" data-id="' + item.id + '" onclick="forcedeleteSchedule(' + item.id + ')">';
                            content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                            content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                            content += '</svg>';
                            content += '</button>';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '<div class="row">';
                            content += '<div class="col-6">';
                            content += '<p>Status: ' + item.status + '</p>';
                            content += '</div>';
                            content += '<div class="col-3">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '<div class="col-3" style="text-align: center;">';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '</div>';//container end
                            content += '</div>';//container fluid end

                            content += '<div id="' + modalId + '" class="modal">';
                            content += '<div class="modal-content">';
                            content += '<span class="close" style="text-align:right";data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                            content += '<h4>Status: ' + item.status + '</h4>';
                            content += '<form id="editForm-' + item.id + '">';
                            content += '@csrf';
                            content += '@method("PUT")';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime">From:</label>';
                            content += '<input type="hidden" name="schedule_status" value="' + item.status + '">';
                            content += '<input type="datetime-local" name="event_datetime" class="form-control" value="' + formatDatetimeForInput(item.event_datetime) + '">';
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="event_datetime_off">To:</label>';
                            // content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '">';
                            content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '" onchange="checkDatetimeValidity(' + item.id + ')">';
                            content += '<div id="toDatetimeError-' + item.id + '" class="error-message"></div>'; // Use dynamic ID based on the item ID
                            content += '</div>';

                            content += '<div class="form-group">';
                            content += '<label for="description">Action:</label>';
                            content += '<select id="description" name="description" class="form-control">';
                            content += '<option value="ON" ' + (item.description === 'ON' ? 'selected' : '') + '>ON</option>';
                            content += '</select>';
                            content += '</div>';

                            content += '<div class="form-group" >';
                            content += '<br>';
                            content += '<button type="button" id="submitedit" class="form-control btn btn-primary" onclick="submitForm(' + item.id + ')">Submit</button>';
                            content += '</div>';
                            content += '</form>';

                            content += '</div>';
                            content += '</div>';
                            content += '<hr>'; // Add a separator

                    });
                    
                    $('#entryCount').text(data.totalEntries);
                    } else {
                        content = 'No Related Schedule';
                        $('#entryCount').text(0);
                    }
                    $('#relatedSchedulesList').html(content);
                    
                    } else {
                        console.error('Invalid response format. Missing "relatedData" property.');
                    }
                },

                error: function (error) {
                    console.error('Error updating related schedules:', error);
                }
                
            });
            $('#totalEntries4, #entryCount4').hide();
            $('#prevBtn1, #nextBtn1').prop('disabled', true).hide();
            $('#prevBtn, #nextBtn').prop('disabled', false).show();
            
        }
    });
</script><!-- filter for to default datepicker -->

<script>
    $(document).ready(function () {
        updateotherRelatedSchedulesadmin();
        $(document).on('change', '.toggleCheckbox1', function () {
            var clickedCheckbox = $(this);
            var clickedItemId = clickedCheckbox.data('id');
            var clickedEventDatetime = clickedCheckbox.data('event-datetime'); 

            $('.toggleCheckbox').not(clickedCheckbox).each(function () {
                var otherCheckbox = $(this);
                var otherEventDatetime = otherCheckbox.data('event-datetime'); 

                if (otherEventDatetime === clickedEventDatetime) {
                    otherCheckbox.prop('checked', false);
                }
            });
        });// Uncheck other checkboxes with the same event_datetime

        $('#yearmonth').on('change', function () {
            checkSelectedDate();
        });

        var currentPage = 1;
        var schedulesPerPage = 2;

        $('#description, #yearmonth, #day, #dateTimePicker, #dateTimePicker2').on('change', function () {
            currentPage = 1; // Reset page to 1 when filters change
            updateotherRelatedSchedulesadmin();
        });

        $('#nextBtn3').on('click', function () {
            currentPage++;
            updateotherRelatedSchedulesadmin();
        });

        $('#prevBtn3').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                updateotherRelatedSchedulesadmin();
            }
        });

        function updateotherRelatedSchedulesadmin() {
            var description = $('#description').val();
            var yearmonth = $('#yearmonth').val();
            var day = $('#day').val();
            var fromtime = $('#dateTimePicker').val();
            var totime = $('#dateTimePicker2').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');


            $.ajax({    
                url: '/get-other-related-data',
                method: 'POST',
                data: {
                    description: description,
                    _token: csrfToken,
                    yearmonth: yearmonth,
                    day: day,
                    fromtime: fromtime,
                    totime: totime,
                    page: currentPage,
                    perPage: schedulesPerPage,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function (data) {
                if (data.hasOwnProperty('otherrelatedSchedules')) {
                    var content = '';
                    if (data.otherrelatedSchedules.length > 0) {
                        data.otherrelatedSchedules.forEach(function (item) {
                            
                                console.log('Item:', item);
                                var modalId = 'myModal-' + item.id;
                                content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                                content += '<div class="container">';

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Schedule Details:</p>';
                                content += '</div>';
                                content += '<div class="col-3" >';
                                content += '<p> State: </p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<p>Action:</p>';
                                content += '</div>';
                                content += '</div>'; // row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                                content += '</div>';
                                content += '<div class="col-3"  style="text-align: center;">';
                                content += '<div class="schedule-details">';
                                content += '<label class="switch">';
                                content += '<input type="checkbox" data-id="' + item.id + '" data-event-datetime="'+ item.event_datetime+'" class="toggleCheckbox slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';
                                content += '<span class="slider round"></span>';
                                content += '</label>';
                                content += '</div>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<button id="myBtn" class="btn btn-info" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">';
                                content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">';
                                content += '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>';
                                content += '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>';
                                content += '</svg>';
                                content += '</button>';
                                content += '</div>';
                                content += '</div>'; // row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<button class="btn btn-danger delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">';
                                content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                                content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                                content += '</svg>';
                                content += '</button>';
                                content += '</div>';
                                content += '</div>'; // row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Action: ' + item.description + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<button class="btn btn-warning delete-btn" data-id="' + item.id + '" onclick="forcedeleteSchedule(' + item.id + ')">';
                                content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                                content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                                content += '</svg>';
                                content += '</button>';
                                content += '</div>';
                                content += '</div>';// row end

                                
                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Status: ' + item.status + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '</div>';
                                content += '</div>';// row end

                                content += '</div>';//container end
                                content += '</div>';//container fluid end

                                content += '<div id="' + modalId + '" class="modal">';
                                content += '<div class="modal-content">';
                                content += '<span class="close" style="text-align:right";data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                                content += '<h4>Status: ' + item.status + '</h4>';
                                content += '<form id="editForm-' + item.id + '">';
                                content += '@csrf';
                                content += '@method("PUT")';

                                content += '<div class="form-group">';
                                content += '<label for="event_datetime">From:</label>';
                                content += '<input type="hidden" name="schedule_status" value="' + item.status + '">';
                                content += '<input type="datetime-local" name="event_datetime" class="form-control" value="' + formatDatetimeForInput(item.event_datetime) + '">';
                                content += '</div>';

                                content += '<div class="form-group">';
                                content += '<label for="event_datetime_off">To:</label>';
                                // content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '">';
                                content += '<input type="datetime-local" name="event_datetime_off" class="form-control" value="' + formatDatetimeForInput(item.event_datetime_off) + '" onchange="checkDatetimeValidity(' + item.id + ')">';
                                content += '<div id="toDatetimeError-' + item.id + '" class="error-message"></div>'; // Use dynamic ID based on the item ID
                                content += '</div>';

                                content += '<div class="form-group">';
                                content += '<label for="description">Action:</label>';
                                content += '<select id="description" name="description" class="form-control">';
                                content += '<option value="ON" ' + (item.description === 'ON' ? 'selected' : '') + '>ON</option>';
                                content += '</select>';
                                content += '</div>';

                                content += '<div class="form-group" >';
                                content += '<br>';
                                content += '<button type="button" id="submitedit" class="form-control btn btn-primary" onclick="submitForm(' + item.id + ')">Submit</button>';
                                content += '</div>';
                                content += '</form>';

                                content += '</div>';
                                content += '</div>';
                                content += '<hr>'; // Add a separator
                            
                    });
                    $('#entryCount2').text(data.totalEntries2);
                    
                    } else {
                        content = 'No Related Schedule';
                        $('#entryCount2').text(0);
                    }
                    $('#other').html(content);
                    } else {
                        console.error('Invalid response format. Missing "relatedData" property.');
                    }
                },

                error: function (error) {
                    console.error('Error updating related schedules:', error);
                }
            });

            // $('#othersched, #otherschedheader').prop('disabled', true).hide();
           
            $('#prevBtn2, #nextBtn2').prop('disabled', true).hide();
            $('#prevBtn3, #nextBtn3').prop('disabled', false).show();
            $('#totalEntries5, #entryCount5').hide();
        }
    });
</script><!-- filter for to related-default datepicker -->

<script>
    $(document).ready(function () {

        $(document).on('change', '.toggleCheckbox1', function () {
            var clickedCheckbox = $(this);
            var clickedItemId = clickedCheckbox.data('id');
            var clickedEventDatetime = clickedCheckbox.data('event-datetime');

            $('.toggleCheckbox').not(clickedCheckbox).each(function () {
                var otherCheckbox = $(this);
                var otherEventDatetime = otherCheckbox.data('event-datetime');

                if (otherEventDatetime === clickedEventDatetime) {
                    otherCheckbox.prop('checked', false);
                }
            });
        }); // Uncheck other checkboxes with the same event_datetime

        var currentPages1 = 1;
        var schedulesPerPage1 = 2;

        $('#description1, #dateTimePicker3').on('change', function () {
            currentPages = 1; // Reset page to 1 when filters change
            updateRelatedData1();
        });

        $('#nextBtn1').on('click', function () {
            currentPages++;
            updateRelatedData1();
        });

        $('#prevBtn1').on('click', function () {
            if (currentPages > 1) {
                currentPages--;
                updateRelatedData1();
            }
        });

        function updateRelatedData1() {
            var description = $('#description1').val();
            var event_datetime = $('#dateTimePicker3').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/get-related-data1',
                method: 'GET',
                data: {
                    description: description,
                    event_datetime: event_datetime,
                    page: currentPages
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function (data) {
                    if (data.hasOwnProperty('relatedData1')) {
                        updateContent(data); 
                    } else {
                        console.error('Invalid response format. Missing "relatedData1" property.');
                    }
                },

                error: function (error) {
                    console.error('Error updating related schedules:', error);
                }
            });
            $('#prevBtn1, #nextBtn1').prop('disabled', false).show();
            $('#prevBtn2, #nextBtn2').prop('disabled', false).show();
            $('#prevBtn, #nextBtn').prop('disabled', true).hide();
            
        }
    });
</script><!-- filter for custom datepicker -->

<script>
    function updateContent(data) {
        if (data.hasOwnProperty('relatedData1')) {
            var content = '';

            if (data.relatedData1.length > 0) {
                
                data.relatedData1.forEach(function (item) {
                    
                    var modalId = 'myModal-' + item.id;
        
                    content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                    content += '<div class="container">';

                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>Schedule Details:</p>';
                    content += '</div>';
                    content += '<div class="col-3">';
                    content += '<p> State: </p>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '<p>Action:</p>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>'+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                    content += '</div>';
                    content += '<div class="col-3">';
                    content += '<div class="schedule-details">';
                    content += '<label class="switch">';
                    content += '<input type="checkbox" data-id="' + item.id + '" data-event-datetime="'+ item.event_datetime+'" class="toggleCheckbox slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';

                    content += '<span class="slider round"></span>';
                    content += '</label>';  
                    content += '</div>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '<button id="myBtn" class="btn btn-info" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">';
                    content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">';
                    content += '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>';
                    content += '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>';
                    content += '</svg>';
                    content += '</button>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>'+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                    content += '</div>';
                    content += '<div class="col-3">';
                    content += '<p></p>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '<button class="btn btn-danger delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">';
                    content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                    content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                    content += '</svg>';
                    content += '</button>';
                    content += '</div>';
                    content += '</div>';//row end
                    
                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>' +'Action: '+ item.description + '</p>';
                    content += '</div>';
                    content += '<div class="col-3">';
                    content += '<p></p>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '<button class="btn btn-warning delete-btn" data-id="' + item.id + '" onclick="forcedeleteSchedule(' + item.id + ')">';
                    content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                    content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                    content += '</svg>';
                    content += '</button>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>Status: ' + item.status + '</p>';
                    content += '</div>';
                    content += '<div class="col-3">';
                    content += '<p></p>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '</div>';
                    content += '</div>';// row end                    

                    content += '</div>';//container end
                    content += '</div>';//container fluid end
                    
                    content += '<div id="' + modalId + '" class="modal">';
                    content += '<div class="modal-content">';
                    content += '<span class="close" style="text-align:right";data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                    content += '<h4>Status: ' + item.status + '</h4>';
                    content += '<form id="editForm-' + item.id + '">';
                    content += '@csrf';
                    content += '@method("PUT")';

                    content += '<div class="form-group">';
                    content += '<label for="event_datetime">From:</label>';
                    content += '<input type="hidden" name="schedule_status" value="' + item.status + '">';
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
                    content += '</select>';
                    content += '</div>';

                    content += '<div class="form-group" >';
                    content += '<br>';
                    content += '<button type="button" id="submitedit" class="form-control" onclick="submitForm2(' + item.id + ')">Submit</button>';
                    content += '</div>';
                    content += '</form>';

                    content += '</div>';
                    content += '</div>';
                    content += '<hr>'; // Add a separator
                });
                    $('#relatedSchedulesList').data('current-page', data.relatedData1.current_page);
                    $('#relatedSchedulesList').data('last-page', data.relatedData1.last_page);
                    
                    $('#entryCount4').text(data.totalEntries4);
            } else {
                content = 'No Related Schedule';
                $('#entryCount4').text(0);
            }

            $('#relatedSchedulesList').html(content);
        } else {
            console.error('Invalid response format. Missing "relatedData" property.');
        }
    }
</script><!-- update content dynamicly  -->

<script>
    $(document).ready(function () {
        
        var currentPages2 = 1;
        var schedulesPerPage2 = 2;  // Adjust this according to your requirements

        $(document).on('change', '.toggleCheckbox', function () {
            var clickedCheckbox = $(this);
            var clickedEventDatetime = clickedCheckbox.data('event-datetime');

            $('.toggleCheckbox').not(clickedCheckbox).each(function () {
                var otherCheckbox = $(this);
                var otherEventDatetime = otherCheckbox.data('event-datetime');

                if (otherEventDatetime === clickedEventDatetime) {
                    otherCheckbox.prop('checked', false);
                }
            });
        });// Uncheck other checkboxes with the same event_datetime

        $('#dateTimePicker3').change(function () {
            currentPages2 = 1; // Reset current page when the date changes
            loadRelatedData2();
        });

        $('#nextBtn2').on('click', function () {
            currentPages2++;
            loadRelatedData2();
        });

        $('#prevBtn2').on('click', function () {
            if (currentPages2 > 1) {
                currentPages2--;
                loadRelatedData2();
            }
        });

        function loadRelatedData2() {
            var event_datetime = $('#dateTimePicker3').val();

            $.ajax({
                type: 'GET',
                url: '/get-related-data',
                data: {
                    event_datetime: event_datetime,
                    page: currentPages2,
                },
                success: function (data) {
                    if (data.hasOwnProperty('relatedData')) {
                        var content = '';
                        if (data.relatedData.length > 0) {
                            data.relatedData.forEach(function (item) {
                                var modalId = 'myModal-' + item.id;

                                content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                                content += '<div class="container">';

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Schedule Details:</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p> State: </p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<p>Action:</p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<div class="schedule-details">';
                                content += '<label class="switch">';
                                content += '<input type="checkbox" data-id="' + item.id + '" data-event-datetime="' + item.event_datetime + '" class="toggleCheckbox slider ' + (item.state === 'Active' ? 'green' : 'red') + '" onclick="toggleButtonClick2(' + item.id + ')" ' + (item.state === 'Active' ? 'checked' : '') + '>';
                                content += '<span class="slider round"></span>';
                                content += '</label>';
                                content += '</div>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<button id="myBtn" class="btn btn-info" data-id="' + item.id + '" onclick="openModal(' + item.id + ')">';
                                content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">';
                                content += '<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>';
                                content += '<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>';
                                content += '</svg>';
                                content += '</button>';
                                content += '</div>';

                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '<button class="btn btn-danger delete-btn" data-id="' + item.id + '" onclick="deleteSchedule(' + item.id + ')">';
                                content += '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">';
                                content += '<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>';
                                content += '</svg>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + 'Action: ' + item.description + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '</div>';
                                content += '</div>';

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Status: ' + item.status + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '</div>';
                                content += '</div>';// row end     

                                content += '</div>';
                                content += '</div>';

                                content += '<div id="' + modalId + '" class="modal">';
                                content += '<div class="modal-content">';
                                content += '<span class="close" style="text-align:right";data-id="' + item.id + '" onclick="closeModal(' + item.id + ')">&times;</span>';
                                content += '<h4>Status: ' + item.status + '</h4>';
                                content += '<form id="editForm-' + item.id + '">';
                                content += '@csrf';
                                content += '@method("PUT")';

                                content += '<div class="form-group">';
                                content += '<label for="event_datetime">From:</label>';
                                content += '<input type="hidden" name="schedule_status" value="' + item.status + '">';
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

                                content += '<div class="form-group" >';
                                content += '<br>';
                                content += '<button type="button" class="form-control" onclick="submitForm2(' + item.id + ')">Submit</button>';
                                content += '</div>';
                                content += '</form>';

                                content += '</div>';
                                content += '</div>';
                                content += '<hr>'; // Add a separator
                            });
                            $('#entryCount5').text(data.totalEntries5);
                        } else {
                            content = 'No Related Schedule';
                            $('#entryCount5').text(0);
                        }

                        $('#other').html(content);
                    } else {
                        console.error('Invalid response format. Missing "relatedData" property.');
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            }); //ajax end
            
        }
    });
</script><!-- related schedule for datetimepicker3 -->

<!-- ______________________________________________________Crude________________________________________________________________-->

<script>
    function toggleButtonClick2(itemId) {
        $.ajax({
            type: 'POST',
            url: '/update-state/' + itemId, 
            data: {
                _token: '{{ csrf_token() }}', 
            },
            success: function (response) {
                if (response.success) {
                    console.log('Database updated successfully');
                    checkForOverlap();
                } else {
                    console.error('Error updating database:', response.message);
                }
            },
            error: function (error) {
                console.error('Error updating database:', error);
            }
        });
    }


</script><!-- update state --> 

<script>
    function deleteSchedule(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // Store the datetime value before the reload
                var dateTimeValue = $('#dateTimePicker').val();
                // var status = $('#' + itemId + ' [name="schedule_status"]').val();

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
                        $('#entryCount').text(response.totalEntries - 1);
                      
                        // Store the datetime value in localStorage
                        localStorage.setItem('dateTimeValue', dateTimeValue);
                        checkExistingSchedules(dateTimeValue);

                        
                        Swal.fire({
                            title: 'Success',
                            text: 'Schedule deleted successfully',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the confirm button is pressed, reload the page
                                location.reload();
                            }
                        });

                       
                    },
                    error: function (error) {
                        console.error('Error deleting schedule:', error);

                        // Check the error status and responseText
                        if (error.status === 401) {
                            console.error('Unauthorized access. Make sure you are authenticated.');
                        } else {
                            console.error('Unknown error. Check the server logs for more information.');
                            Swal.fire({
                                title: 'Error!',
                                text: 'Cannot Delete schedule with status "Processing"',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        });
    }

</script><!-- Delete --> 

<script>
    function forcedeleteSchedule(itemId) {
        Swal.fire({
            title: 'Force Delete: Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // Store the datetime value before the reload
                var dateTimeValue = $('#dateTimePicker').val();
                // var status = $('#' + itemId + ' [name="schedule_status"]').val();

                $.ajax({
                    type: 'DELETE',
                    url: '/forcedelete-schedule/' + itemId,
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
                        checkExistingSchedules(dateTimeValue);
                        Swal.fire({
                            title: 'Success',
                            text: 'Schedule deleted successfully',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the confirm button is pressed, reload the page
                                location.reload();
                            }
                        });;
                    },
                    error: function (error) {
                        console.error('Error deleting schedule:', error);

                        // Check the error status and responseText
                        if (error.status === 401) {
                            console.error('Unauthorized access. Make sure you are authenticated.');
                        } else {
                            console.error('Unknown error. Check the server logs for more information.');
                            Swal.fire({
                                title: 'Error!',
                                text: 'Cannot Delete schedule with status "Processing"',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        });
    }

</script><!-- Force Delete --> 

<script>

    function openModal(itemId) {
        // Show SweetAlert instead of directly opening the modal
        Swal.fire({
            title: 'Edit Confirmation',
            text: 'Are you sure you want to edit this schedule?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with opening the modal
                var modalId = 'myModal-' + itemId;
                var modal = document.getElementById(modalId);
                modal.style.display = "block";
            }
        });
    }

    function closeModal(itemId) {
        var modalId = 'myModal-' + itemId;
        var modal = document.getElementById(modalId);
        modal.style.display = "none";
        resetForm(itemId);
    } 

    function resetForm(itemId) {
        var formId = 'editForm-' + itemId;
        $('#' + formId)[0].reset(); // Reset the form using the DOM element
        $('#toDatetimeError-' + itemId).html('').hide(); // Clear and hide the error message
    }

    function submitForm(itemId) {
        var formId = 'editForm-' + itemId;
        var formData = $('#' + formId).serialize();
        var selectedState = $('#state').val();
        var fromDatetime = $('#' + formId + ' [name="event_datetime"]').val();
        var toDatetime = $('#' + formId + ' [name="event_datetime_off"]').val();
        var status = $('#' + formId + ' [name="schedule_status"]').val();
        var startDate = new Date(fromDatetime);
        var endDate = new Date(toDatetime);
        var currentDate = new Date(); // Get the current date

        if (startDate < currentDate && endDate < currentDate) {
            Swal.fire({
                title: 'Error!',
                text: 'Cannot create a schedule in the past.',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return; // Stop the submission
        }

        if (startDate.toDateString() !== endDate.toDateString()) {
            Swal.fire({
                title: 'Error!',
                text: 'Schedules cannot span multiple days.',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return; // Stop the submission
        }

        if (status === 'Processing') {
                // Display SweetAlert for status check
                Swal.fire({
                    title: 'Error!',
                    text: 'Cannot edit schedule with status "Processing"',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return; // Stop the submission
        }

        // Make an AJAX call to check for overlapping schedules
        $.ajax({
            type: 'POST',
            url: '/check-overlapping-schedule',
            data: {
                event_datetime: fromDatetime,
                event_datetime_off: toDatetime,
                schedule_id: itemId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.overlap && !response.editingOverlap) {
                    // Display SweetAlert for overlapping schedule
                    Swal.fire({
                        title: 'Error!',
                        text: 'There is an overlapping schedule.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/get-schedules-count',
                        data: { event_datetime: fromDatetime },
                        success: function (response) {
                            var existingSchedulesCount = response.count;

                            if (existingSchedulesCount >= 2) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Cannot insert more than 2 schedules with the same Start Time',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                // Proceed with form submission
                                updateSchedule(itemId, formData);
                            }
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function updateSchedule(itemId, formData) {
        $.ajax({
            type: 'PUT',
            url: '/update-schedule/' + itemId,
            data: formData,
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Schedule updated successfully.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        closeModal(itemId);
                        var updatedDateTimeValue = $('#dateTimePicker').val();
                        localStorage.setItem('dateTimeValue', updatedDateTimeValue);
                        $('#dateTimePicker').trigger('change');
                    }
                });
            },
            error: function (error) {
                // Handle error
                if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.event_datetime_off) {
                    var errorMessage = error.responseJSON.errors.event_datetime_off[0];
                    if (errorMessage.includes('Cannot insert more than 2 schedules with the same Start Time')) {
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        return; // Stop the submission
                    } else {
                        // Display the default error message
                        $('#toDatetimeError').html(errorMessage);
                    }
                }

                if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.event_datetime_off.includes('There is an overlapping schedule.')) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There is an overlapping schedule.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return submitForm(itemId); // Stop the submission
                }

                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error updating the schedule.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                console.error('Error:', error);
            }
        });
    }


    function submitForm2(itemId) {
        var formId = 'editForm-' + itemId;
        var formData = $('#' + formId).serialize();
        var selectedState = $('#state').val();
        var fromDatetime = $('#' + formId + ' [name="event_datetime"]').val();
        var toDatetime = $('#' + formId + ' [name="event_datetime_off"]').val();
        var status = $('#' + formId + ' [name="schedule_status"]').val();
        var startDate = new Date(fromDatetime);
        var endDate = new Date(toDatetime);
        var currentDate = new Date(); // Get the current date

        if (startDate < currentDate && endDate < currentDate) {
            Swal.fire({
                title: 'Error!',
                text: 'Cannot create a schedule in the past.',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return; // Stop the submission
        }

        if (startDate.toDateString() !== endDate.toDateString()) {
            Swal.fire({
                title: 'Error!',
                text: 'Schedules cannot span multiple days.',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return; // Stop the submission
        }

        if (status === 'Processing') {
                // Display SweetAlert for status check
                Swal.fire({
                    title: 'Error!',
                    text: 'Cannot edit schedule with status "Processing"',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return; // Stop the submission
        }

        // Make an AJAX call to check for overlapping schedules
        $.ajax({
            type: 'POST',
            url: '/check-overlapping-schedule',
            data: {
                event_datetime: fromDatetime,
                event_datetime_off: toDatetime,
                schedule_id: itemId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.overlap && !response.editingOverlap) {
                    // Display SweetAlert for overlapping schedule
                    Swal.fire({
                        title: 'Error!',
                        text: 'There is an overlapping schedule.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/get-schedules-count',
                        data: { event_datetime: fromDatetime },
                        success: function (response) {
                            var existingSchedulesCount = response.count;

                            if (existingSchedulesCount >= 2) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Cannot insert more than 2 schedules with the same Start Time',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                // Proceed with form submission
                                updateSchedule2(itemId, formData);
                            }
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function updateSchedule2(itemId, formData) {
        $.ajax({
            type: 'PUT',
            url: '/update-schedule/' + itemId,
            data: formData,
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Schedule updated successfully.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        closeModal(itemId);
                        var updatedDateTimeValue = $('#dateTimePicker3').val();
                        localStorage.setItem('dateTimeValue', updatedDateTimeValue);
                        $('#dateTimePicker3').trigger('change');
                    }
                });
            },
            error: function (error) {
                // Handle error
                if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.event_datetime_off) {
                    var errorMessage = error.responseJSON.errors.event_datetime_off[0];
                    if (errorMessage.includes('Cannot insert more than 2 schedules with the same Start Time')) {
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        return; // Stop the submission
                    } else {
                        // Display the default error message
                        $('#toDatetimeError').html(errorMessage);
                    }
                }

                if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.event_datetime_off.includes('There is an overlapping schedule.')) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There is an overlapping schedule.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return submitForm(itemId); // Stop the submission
                }

                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error updating the schedule.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                console.error('Error:', error);
            }
        });
    }

    function checkDatetimeValidity(itemId) {
        var fromDatetime = $('#editForm-' + itemId + ' [name="event_datetime"]').val();
        var toDatetime = $('#editForm-' + itemId + ' [name="event_datetime_off"]').val();

        $.ajax({
            type: 'POST',
            url: '/modal-validate-datetime',
            data: {
                event_datetime: fromDatetime,
                event_datetime_off: toDatetime,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                // Check the validation result
                if (response.success) {
                    $('#toDatetimeError-' + itemId).html('').hide(); // Clear and hide the error message
                } else {
                    $('#toDatetimeError-' + itemId).html(response.errors.event_datetime_off[0]).show(); // Display the error message
                }
            },
            error: function (xhr, status, error) {
                // Handle the error response and display the message
                var errorMessage = xhr.responseJSON && xhr.responseJSON.errors
                    ? xhr.responseJSON.errors.event_datetime_off[0]
                    : 'An error occurred while validating the datetime.';             
                $('#toDatetimeError-' + itemId).html(errorMessage).show(); // Display the error message
            }
        });
    }

    function formatDatetimeForInput(datetime) {
        var parsedDatetime = new Date(datetime);
        var year = parsedDatetime.getFullYear();
        var month = ('0' + (parsedDatetime.getMonth() + 1)).slice(-2);
        var day = ('0' + parsedDatetime.getDate()).slice(-2);
        var hours = ('0' + parsedDatetime.getHours()).slice(-2);
        var minutes = ('0' + parsedDatetime.getMinutes()).slice(-2);

        // Construct the formatted datetime string for input
        var formattedDatetime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

        console.log('Formatted Datetime:', formattedDatetime);
        return formattedDatetime;
    }

</script><!-- modal -->

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
        $(this).toggleClass("clicked");
        var buttonText = $(this).text().trim();
        $(this).text(buttonText === "Custom Schedule" ? "Default Schedule" : "Custom Schedule");
        // Set the value of the hidden field based on the button click
        $("#customScheduleClicked").val($(".date-time-group").is(":visible") ? "1" : "0");

        if (buttonText === "Custom Schedule") {
            $('#textchangetorela').text('Related Schedule');
        } else {
            $('#textchangetorela').text('All Schedule');
        }

        // Disable/enable other form elements based on the button click
        if ($(".date-time-group").is(":visible")) {
            $(".date-time-group input, .date-time-group select").prop("disabled", false);
            $(".other-form-elements input, .other-form-elements select").prop("disabled", true);
            $('#othersched, #otherschedheader').show();
            $('#totalEntries, #entryCount').hide();
            $('#totalEntries2, #entryCount2').hide();
            $('#totalEntries4, #entryCount4').show();
            $('#prevBtn, #nextBtn').hide();
            $('#prevBtn2, #nextBtn2').show();
            $('#prevBtn3, #nextBtn3').hide();
            $('#prevBtn1, #nextBtn1').prop('disabled', false).show();
            $('#totalEntries5, #entryCount5').show();
          
        } else {
            $(".date-time-group input, .date-time-group select").prop("disabled", true);
            $(".other-form-elements input, .other-form-elements select").prop("disabled", false);
            $('#othersched, #otherschedheader').show();
            $('#totalEntries, #entryCount').show();
            $('#totalEntries2, #entryCount2').show();
            $('#totalEntries4, #entryCount4').hide();
            $('#prevBtn1, #nextBtn1').prop('disabled', false).hide();
            $('#prevBtn, #nextBtn').show();
            $('#totalEntries5, #entryCount5').hide();
            $('#prevBtn2, #nextBtn2').hide();
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

<!-- __________________________________________________automatic detection ______________________________________________________-->

<script>
    function checkSelectedDate() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var selectedDate = $('#yearmonth').val();
    var parsedDate = moment(selectedDate, 'MM-YYYY');
    var formattedDate = parsedDate.isValid() ? parsedDate.format('YYYY-MM') : '';

    $.ajax({
        url: '/validate-date',
        method: 'POST',
        data: { selectedDate: formattedDate, 
                _token: csrfToken },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            if (response.error) {
                $('#yearmonthError').text(response.message);
                $('[data-custom-id="subd"]').prop('disabled', true);
            } else {
                $('#yearmonthError').text('');
                $('[data-custom-id="subd"]').prop('disabled', false);
            }
        },
        error: function (error) {
            console.error('Error validating date:', error);
        }
    });
    }
</script><!-- automatic detect for default month and year error -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("dateTimePicker").addEventListener("change", function () {
            validateTime();
        });

        document.getElementById("dateTimePicker2").addEventListener("change", function () {
            validateTime();
        });
    });

    function validateTime() {
        var fromTime = document.getElementById("dateTimePicker").value;
        var toTime = document.getElementById("dateTimePicker2").value;
        var toError = document.getElementById("toError");

        // Parse time strings to extract hours and minutes
        var fromTimeParts = fromTime.split(":");
        var toTimeParts = toTime.split(":");

        var fromHours = parseInt(fromTimeParts[0]);
        var fromMinutes = parseInt(fromTimeParts[1]);
        var toHours = parseInt(toTimeParts[0]);
        var toMinutes = parseInt(toTimeParts[1]);

        if (toHours < fromHours || (toHours === fromHours && toMinutes <= fromMinutes)) {
            toError.textContent = "The schedule end time must be later than the start time.";
            $('[data-custom-id="subd"]').prop('disabled', true);
        } else {
            toError.textContent = "";
            $('[data-custom-id="subd"]').prop('disabled', false);
            document.getElementById("fromtime_hidden").value = fromTime;
            sendAjaxRequest();
        }
    }

    function sendAjaxRequest() {
        var fromTimeHidden = document.getElementById("fromtime_hidden").value;
        var toTime = document.getElementById("dateTimePicker2").value;
        $.ajax({
            type: 'POST',
            url: '/validate-time',
            data: {
                fromtime: fromTimeHidden,
                totime: toTime,
                _token: '{{ csrf_token() }}' 
            },
            success: function (response) {
                if (response.error) {
                    document.getElementById("toError").textContent = response.error;       
                } else {
                    document.getElementById("toError").textContent = "";
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script><!-- automatic detect for from & time error -->

<script>
    function checkForOverlap() {
        var fromDateTime = $('#dateTimePicker3').val();
        var toDateTime = $('#dateTimePicker4').val();

        $.ajax({
            type: 'GET',
            url: '/check-overlap',
            data: {
                fromDateTime: fromDateTime,
                toDateTime: toDateTime,
            },
            success: function (response) {
                if (response.overlap || response.error) {
                    $('#existingSchedulesErrorTo').text('Schedule overlaps with an active schedule!');
                    $('#sub').prop('disabled', true);
                } else {
                    $('#existingSchedulesErrorTo').text('');
                    
                }
            },
            error: function (error) {
                console.error('Error checking for overlap:', error);
            }
        });
    }
</script><!-- dynamic change of error message for updating state -->

<script>
    function checkExistingSchedules(eventDateTime) {
        $.ajax({
            url: '/check-existing-schedules',
            type: 'GET',
            data: {event_datetime: eventDateTime},
            success: function (response) {
                if (response.error) {
                    $('#existingSchedulesError').text(response.error);
                    $('#sub').prop('disabled', true);
                } else {
                    $('#existingSchedulesError').text('');   
                }
            },
            error: function () {
                console.error('Error in AJAX request');
            }
        });
    }
</script><!-- dynamic change of error message for Cannot insert more than 2 schedules with the same Start Time -->

<script>
    $(document).ready(function () {
        $('#dateTimePicker3').on('input', function () {
            var eventDateTime = $(this).val();

            // Make an AJAX request to check existing schedules
            $.ajax({
                url: '/check-existing-schedules',
                type: 'GET',
                data: {event_datetime: eventDateTime},
                success: function (response) {
                    if (response.error) {
                        $('#existingSchedulesError').text(response.error);
                        $('#sub').prop('disabled', true);
                    } else {
                        $('#existingSchedulesError').text('');
                    }
                },
                error: function () {
                    console.error('Error in AJAX request');
                }
            });
        });
    });
</script><!-- automatic detect for from: date & time --> 

<script>
    $(document).ready(function () {
        // Function to check for overlapping schedules
        function checkForOverlap() {
            var fromDateTime = $('#dateTimePicker3').val();
            var toDateTime = $('#dateTimePicker4').val();
            var lineBreak = $('<br>');
            // Perform an AJAX request to check for overlaps
            $.ajax({
                type: 'GET',
                url: '/check-overlap',
                data: {
                    fromDateTime: fromDateTime,
                    toDateTime: toDateTime,
                },
                success: function (response) {
                    if (response.overlap || response.error) {
                        $('#existingSchedulesErrorTo').text('Schedule Error: Schedule overlaps with an active schedule!').append(lineBreak);;
                        $('#sub').prop('disabled', true);
                    } else {
                        $('#existingSchedulesErrorTo').text('');
                    }
                },
                error: function (error) {
                    console.error('Error checking for overlap:', error);
                }
            });
        }

        // Attach the checkForOverlap function to the change event of the date and time inputs
        $('#dateTimePicker3, #dateTimePicker4').on('change', function () {
            checkForOverlap();
        });
    });
</script><!-- automatic detect for to: date & time  Schedule Error: Schedule overlaps with an active schedule!--> 

<script>
    $(document).ready(function () {
        function checkFordatetime() {
            var fromDateTime = $('#dateTimePicker3').val();
            var toDateTime = $('#dateTimePicker4').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '/validate-event-time',
                data: {
                    fromDateTime: fromDateTime,
                    toDateTime: toDateTime,
                    _token: csrfToken,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    if (response.error) {
                        $('#pasterror').text('Schedule Error: To Date & Time must be after From Date & Time.').append('<br>');
                        $('#sub').prop('disabled', true);
                    } else {
                        $('#pasterror').text('');
                    }
                },
                error: function (error) {
                    console.error('Error checking for Schedule Error:', error);
                }
            });
        }
        
        var dateTimePicker3Changed = false;
        var dateTimePicker4Changed = false;

        $('#dateTimePicker3').on('change', function () {
            dateTimePicker3Changed = true;
            checkIfBothChanged();
        });

        $('#dateTimePicker4').on('change', function () {
            dateTimePicker4Changed = true;
            checkIfBothChanged();
        });

        function checkIfBothChanged() {
            if (dateTimePicker3Changed && dateTimePicker4Changed) {
                checkFordatetime();
            }
            else{
                $('#pasterror').text('');
            }
        }
    });
</script><!-- automatic detect for from and to: date & time custom Schedule Error: To date & time must be after From date & time-->

<script>
    $(document).ready(function () {
        function checkFordatetime() {
            var fromDateTime = $('#dateTimePicker3').val();
            var toDateTime = $('#dateTimePicker4').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make an AJAX request to the server for validation
            $.ajax({
                url: '/validate-dates', // Change this to your Laravel route
                type: 'POST',
                data: {
                    fromDateTime: fromDateTime,
                    toDateTime: toDateTime,
                    _token: csrfToken,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    displayErrorMessages(response.dateError);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function displayErrorMessages(dateError) {
            // Clear existing error messages
            $('#datespanerror').text('');

            // Display the specific error message
            if (dateError) {
                $('#datespanerror').text(dateError);
                $('#sub').prop('disabled', true);
            } else {
                // Enable the submit button if no error
                $('#datespanerror').text('');
            }
        }

        // $('#dateTimePicker3, #dateTimePicker4').on('change', function () {
        //     checkFordatetime();
        // });

        var dateTimePicker3Changed = false;
        var dateTimePicker4Changed = false;

        $('#dateTimePicker3').on('change', function () {
            dateTimePicker3Changed = true;
            checkIfBothChanged();
        });

        $('#dateTimePicker4').on('change', function () {
            dateTimePicker4Changed = true;
            checkIfBothChanged();
        });

        function checkIfBothChanged() {
            if (dateTimePicker3Changed && dateTimePicker4Changed) {
                checkFordatetime();
            }
            else{
                $('#datespanerror').text('');
            }
        }

    });
</script><!-- check for schedule that cover multiple days-->

<script>
    $(document).ready(function() {
        $('#dateTimePicker4').on('change', function() {
            var dateTime = $(this).val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/validate-datetime',
                method: 'POST',
                data: { dateTime: dateTime,
                        _token: csrfToken },
                success: function(response) {
                    if (!response.isValid) {
                        $('#separateError').text('The scheduled start time has already passed').append('<br>');;
                        $('#sub').prop('disabled', true);
                    } else {
                        $('#separateError').text('');
                    }
                },
                error: function(error) {
                    console.error('Error validating date and time:', error);
                }
            });
        });
    });
</script><!-- scheduled start time has already passed-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the form elements
        var descriptionInput = document.getElementById('description1');
        var dateTimePicker3Input = document.getElementById('dateTimePicker3');
        var dateTimePicker4Input = document.getElementById('dateTimePicker4');
        var submitButton = document.getElementById('sub');

        // Disable the submit button initially
        submitButton.disabled = true;

        // Function to check if all inputs are filled
        function checkInputs() {
            var descriptionValue = descriptionInput.value;
            var dateTimePicker3Value = dateTimePicker3Input.value;
            var dateTimePicker4Value = dateTimePicker4Input.value;

            if (descriptionValue.trim() !== '' && dateTimePicker3Value.trim() !== '' && dateTimePicker4Value.trim() !== '') {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }

        // Add event listeners to input fields
        descriptionInput.addEventListener('input', checkInputs);
        dateTimePicker3Input.addEventListener('input', checkInputs);
        dateTimePicker4Input.addEventListener('input', checkInputs);
    });
</script><!-- disble the button if it does not have a data-->

<!-- _______________________________________________________extra________________________________________________________________-->

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

<script>
    $(document).ready(function () {
        // Clear input fields on page refresh
        $('input[type="text"]').val('');
        $('select').prop('selectedIndex', 0);
    });
</script><!-- remove the input if refresd; -->

<script>
    $(document).ready(function () {

        $('#clearButton').click(function () {
            // Clear all input fields
            $('input[type="text"]').val('');
            $('select').prop('selectedIndex', 0);

            // Clear any error messages
            $('.text-danger').text('');
            location.reload(true);

        });
    });
</script><!-- reset -->

<script>
    $(document).ready(function() {       
        $('#countries').multiselect({		
            nonSelectedText: 'Select Teams'				
        });
    });
</script><!-- multiple -->

{{-- <script>
    document.getElementById('yearmonth').addEventListener('change', checkDateValidity);
    document.getElementById('day').addEventListener('change', checkDateValidity);
    
    function checkDateValidity() {
        var yearmonth = document.getElementById('yearmonth').value;
        var days = document.getElementById('day').selectedOptions;

        var all_Days = ["Monday","Tuesday", "Wednesday", "Thursday", "Friday","Saturday", "Sunday"];
        var num_md=["1","2","3","4","5"];

        var selectedDays = [];
        for (var i = 0; i < days.length; i++) {
            selectedDays.push(days[i].text);
        }
        var isValid = true;
    
        var month = parseInt(yearmonth.split('-')[0]);
        var year = parseInt(yearmonth.split('-')[1]);
        var currentDay = new Date().getDate();
        var currentDate = new Date(year, month - 1, currentDay);
        for (var i = 0; i < selectedDays.length; i++) {
            var dayOfWeek = getDayOfWeek(selectedDays[i]);
            var dayDiff = dayOfWeek - currentDate.getDay();
            if (dayDiff < 0) {
                dayDiff += 7;
            }
            var targetDate = new Date(year, month - 1, currentDay + dayDiff);
            if (targetDate.getMonth() !== month - 1) {
                isValid = false;
                break;
            }
        }
        if (!isValid) {
            document.getElementById('dayError').innerText = 'There is no more selected day in the selected month.';
            $('[data-custom-id="subd"]').prop('disabled', true);
        } else {
            document.getElementById('dayError').innerText = '';
            $('[data-custom-id="subd"]').prop('disabled', false);
        }
    }
    
    function getDayOfWeek(day) {
        switch (day) {
            case 'Sunday': return 0;
            case 'Monday': return 1;
            case 'Tuesday': return 2;
            case 'Wednesday': return 3;
            case 'Thursday': return 4;
            case 'Friday': return 5;
            case 'Saturday': return 6;
        }
    }
</script> --}}

<script>
    document.getElementById("yearmonth").addEventListener("change", calculateDays);

    function calculateDays() {

        var ui = document.getElementById("yearmonth").value;

        var inputValue = document.getElementById("yearmonth").value;
        var inputParts = inputValue.split('-');
        if (inputParts.length !== 2) {
            // Invalid input format, clear result and return
            document.getElementById("result").innerHTML = "Invalid input format. Please enter in MM-YYYY format.";
            return;
        }
        var month = parseInt(inputParts[0]) - 1; // Adjusting month to be zero-based
        var year = parseInt(inputParts[1]);
        if (isNaN(month) || isNaN(year) || month < 0 || month > 11) {
            // Invalid month or year, clear result and return
            document.getElementById("result").innerHTML = "Invalid month or year.";
            return;
        }

        var daysInMonth = new Date(year, month + 1, 0).getDate(); // Get the last day of the month
        var resultDiv = document.getElementById("result");
        resultDiv.innerHTML = ""; // Clear previous result

        // Initialize an array to store counts for each day of the week
        var dayCounts = [0, 0, 0, 0, 0, 0, 0];

        for (var day = 1; day <= daysInMonth; day++) {
            // Calculate the day of the week for the given date
            var dayOfWeek = new Date(year, month, day).getDay();
            // Increment the count for the corresponding day of the week
            dayCounts[dayOfWeek]++;
        }

        // Create an array of day names
        var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Display the count of each day of the week in the result div
        for (var i = 0; i < daysOfWeek.length; i++) {
            resultDiv.innerHTML += daysOfWeek[i] + ': ' + dayCounts[i] + '<br>';
        }

    }
</script>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Attach event listener using pure JavaScript
        document.getElementById("yearmonth").addEventListener("change", calculateDays);

        function calculateDays() {
            var dateText = document.getElementById("yearmonth").value; // Get the value of the input
            var inputParts = dateText.split('-');
            if (inputParts.length !== 2) {
                // Invalid input format, clear result and return
                document.getElementById("result").innerHTML = "Invalid input format. Please enter in MM-YYYY format.";
                return;
            }
            var month = parseInt(inputParts[0]) - 1; // Adjusting month to be zero-based
            var year = parseInt(inputParts[1]);
            if (isNaN(month) || isNaN(year) || month < 0 || month > 11) {
                // Invalid month or year, clear result and return
                document.getElementById("result").innerHTML = "Invalid month or year.";
                return;
            }

            var selectedDate = new Date(year, month, 1); // Create a date object for the first day of the selected month
            var currentDate = new Date(); // Get the current date
            if (selectedDate > currentDate) {
                // Selected month is in the future, clear result and return
                document.getElementById("result").innerHTML = "Selected month is in the future.";
                return;
            }

            var resultDiv = document.getElementById("result");
            resultDiv.innerHTML = ""; // Clear previous result

            // Initialize an array to store counts for each day of the week
            var dayCounts = [0, 0, 0, 0, 0, 0, 0];

            // Get the day of the week for the selected date
            var startDayOfWeek = selectedDate.getDay();

            // Loop through each day starting from the selected date until the end of the month
            for (var day = 1; day <= selectedDate.daysInMonth(); day++) {
                // Increment the count for the corresponding day of the week
                dayCounts[(startDayOfWeek + day - 1) % 7]++;
            }

            // Create an array of day names
            var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Display the count of each day of the week in the result div
            for (var i = 0; i < daysOfWeek.length; i++) {
                resultDiv.innerHTML += daysOfWeek[i] + ': ' + dayCounts[i] + '<br>';
            }
        }
    });
</script> --}}

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Attach event listener using pure JavaScript
        document.getElementById("yearmonth").addEventListener("change", calculateDays);

        function calculateDays() {
            var dateText = document.getElementById("yearmonth").value; // Get the value of the input
            var inputParts = dateText.split('-');
            if (inputParts.length !== 2) {
                // Invalid input format, clear result and return
                document.getElementById("result").innerHTML = "Invalid input format. Please enter in MM-YYYY format.";
                return;
            }
            var month = parseInt(inputParts[0]) - 1; // Adjusting month to be zero-based
            var year = parseInt(inputParts[1]);
            if (isNaN(month) || isNaN(year) || month < 0 || month > 11) {
                // Invalid month or year, clear result and return
                document.getElementById("result").innerHTML = "Invalid month or year.";
                return;
            }

            var selectedDate = new Date(year, month, 1); // Create a date object for the first day of the selected month
            var currentDate = new Date(); // Get the current date
            if (selectedDate > currentDate) {
                // Selected month is in the future, clear result and return
                document.getElementById("result").innerHTML = "Selected month is in the future.";
                return;
            }

            var resultDiv = document.getElementById("result");
            resultDiv.innerHTML = ""; // Clear previous result

            // Initialize an array to store counts for each day of the week
            var dayCounts = [0, 0, 0, 0, 0, 0, 0];

            // Loop through each day of the month starting from the selected date
            var daysInMonth = new Date(year, month + 1, 0).getDate(); // Get the last day of the month
            for (var day = selectedDate.getDate(); day <= daysInMonth; day++) {
                // Calculate the day of the week for the given date
                var dayOfWeek = selectedDate.getDay();

                // Increment the count for the corresponding day of the week
                dayCounts[dayOfWeek]++;

                // Move to the next day
                selectedDate.setDate(selectedDate.getDate() + 1);
            }

            // Create an array of day names
            var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Display the count of each day of the week in the result div
            for (var i = 0; i < daysOfWeek.length; i++) {
                resultDiv.innerHTML += daysOfWeek[i] + ': ' + dayCounts[i] + '<br>';
            }
        }
    });
</script> --}}

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Attach event listener using pure JavaScript
        document.getElementById("yearmonth").addEventListener("change", calculateDays);

        function calculateDays() {
            var dateText = document.getElementById("yearmonth").value; // Get the value of the input
            var inputParts = dateText.split('-');
            if (inputParts.length !== 2) {
                // Invalid input format, clear result and return
                document.getElementById("result").innerHTML = "Invalid input format. Please enter in MM-YYYY format.";
                return;
            }
            var month = parseInt(inputParts[0]) - 1; // Adjusting month to be zero-based
            var year = parseInt(inputParts[1]);
            if (isNaN(month) || isNaN(year) || month < 0 || month > 11) {
                // Invalid month or year, clear result and return
                document.getElementById("result").innerHTML = "Invalid month or year.";
                return;
            }

            var selectedDate = new Date(year, month, 1); // Create a date object for the first day of the selected month
            var currentDate = new Date(); // Get the current date
            if (selectedDate > currentDate) {
                // Selected month is in the future, clear result and return
                document.getElementById("result").innerHTML = "Selected month is in the future.";
                return;
            }

            var resultDiv = document.getElementById("result");
            resultDiv.innerHTML = ""; // Clear previous result

            // Get the selected day of the week
            var selectedDay = new Date(dateText).getDay();

            // Get the last day of the selected month
            var lastDayOfMonth = new Date(year, month + 1, 0).getDate();

            // Initialize an array to store counts for each day of the week
            var dayCounts = [0, 0, 0, 0, 0, 0, 0];

            // Loop through each day starting from the selected day until the end of the month
            for (var day = selectedDay; day <= lastDayOfMonth; day++) {
                // Increment the count for the corresponding day of the week
                dayCounts[day % 7]++;
            }

            // Create an array of day names
            var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Display the count of each day of the week in the result div
            for (var i = 0; i < daysOfWeek.length; i++) {
                resultDiv.innerHTML += daysOfWeek[i] + ': ' + dayCounts[i] + '<br>';
            }
        }
    });
</script> --}}
