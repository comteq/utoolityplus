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
                <form method="post" action="{{ route('store.schedule') }}" id="form">
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
                                <option disabled value="">Select Action</option>
                                <option value="ON">ON</option>
                                <option value="OFF">OFF</option>
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
                        </div>

                        <button type="submit" name="custom_schedule" class="btn btn-primary w-100" id="sub">Set Schedule</button>
                    </div> <!-- date-time-group -->
            
                    <div class="other-form-elements">

                    <div class="form-group">
                            <label for="description">Action:</label>
                            <select name="description" id="description" class="custom-select w-100" required>
                                <option disabled value="">Select Action</option>
                                <option value="ON">ON</option>
                                <option value="OFF">OFF</option>
                            </select>
                            <!-- <span id="existingactionerror" class="text-danger"></span> -->
                    </div>

                        <div class="form-group">
                            <label for="yearmonth">Month & Year:</label>
                            <input type="text" class="form-control" name="yearmonth" id="yearmonth" placeholder="Select Month & Year" required/>
                            <div id="yearmonthError" class="text-danger"></div>
                        </div>

                        <div class="form-group">
                            <label for="day">Day:</label>
                                <select name="day" id="day" class="custom-select w-100" required>
                                    <option disabled selected value="">Select Day</option>
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

                        <input type="hidden" name="fromtime_hidden" id="fromtime_hidden" value=""/>
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
                <h1>All Schedule</h1>
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
            autoclose:true
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
                url: '/update-related-schedules',
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
                            content += '<div class="col-7">';
                            content += '<p>Schedule Details:</p>';
                            content += '</div>';
                            content += '<div class="col d-flex justify-content-center">';
                            content += '<p> State: </p>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                            content += '</div>';
                            content += '<div class="col d-flex justify-content-center">';
                            content += '<div class="schedule-details">';
                            content += '<label class="switch">';
                            content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                            content += '<span class="slider round"></span>';
                            content += '</label>';
                            content += '</div>';
                            content += '</div>';
                            content += '</div>'; // row end
                            

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>Action: ' + item.description + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>Action: ' + item.status + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '</div>';//container end
                            content += '</div>';//container fluid end
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
                url: '/get-other-related-data-user',
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
                if (data.hasOwnProperty('otherrelatedSchedules')) {
                    var content = '';
                    if (data.otherrelatedSchedules.length > 0) {
                        data.otherrelatedSchedules.forEach(function (item) {
                            console.log('Item:', item);
                            var modalId = 'myModal-' + item.id;
                            content += '<div class="container-fluid" id="schedule-' + item.id + '">';
                            content += '<div class="container">';

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>Schedule Details:</p>';
                            content += '</div>';
                            content += '<div class="col d-flex justify-content-center">';
                            content += '<p> State: </p>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                            content += '</div>';
                            content += '<div class="col d-flex justify-content-center">';
                            content += '<div class="schedule-details">';
                            content += '<label class="switch">';
                            content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                            content += '<span class="slider round"></span>';
                            content += '</label>';
                            content += '</div>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>'; // row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>Action: ' + item.description + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '<div class="row">';
                            content += '<div class="col-7">';
                            content += '<p>Action: ' + item.status + '</p>';
                            content += '</div>';
                            content += '<div class="col">';
                            content += '<p></p>';
                            content += '</div>';
                            content += '</div>';// row end

                            content += '</div>';//container end
                            content += '</div>';//container fluid end
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
            $('#prevBtn2, #nextBtn2').prop('disabled', true).hide();
            $('#prevBtn3, #nextBtn3').prop('disabled', false).show();
            $('#totalEntries5, #entryCount5').hide();
        }
    });
</script><!-- filter for to related-default datepicker -->

<script>
    $(document).ready(function () {
        
        $(document).on('change', '.toggleCheckbox', function () {
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
                url: '/get-existing-data-user',
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
                    content += '<div class="col-7">';
                    content += '<p>Schedule Details:</p>';
                    content += '</div>';
                    content += '<div class="col d-flex justify-content-center">';
                    content += '<p> State: </p>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-7">';
                    content += '<p>'+ item.event_datetime_time  + ' -- ' + item.event_datetime_off_time + '</p>';
                    content += '</div>';
                    content += '<div class="col d-flex justify-content-center">';
                    content += '<div class="schedule-details">';
                    content += '<label class="switch">';
                    content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                    content += '<span class="slider round"></span>';
                    content += '</label>';  
                    content += '</div>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-7">';
                    content += '<p>'+ item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                    content += '</div>';
                    content += '<div class="col">';
                    content += '<p></p>';
                    content += '</div>';
                    content += '</div>';//row end
                    
                    content += '<div class="row">';
                    content += '<div class="col-7">';
                    content += '<p>' +'Action: '+ item.description + '</p>';
                    content += '</div>';
                    content += '<div class="col">';
                    content += '<p></p>';
                    content += '</div>';
                    content += '</div>';//row end

                    content += '<div class="row">';
                    content += '<div class="col-6">';
                    content += '<p>Status: ' + item.status + '</p>';
                    content += '</div>';
                    content += '<div class="col-3" style="text-align: center;">';
                    content += '</div>';
                    content += '</div>';// row end         

                    content += '</div>';//container end
                    content += '</div>';//container fluid end
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
</script><!-- update content dynamicly -->

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
                url: '/get-related-data-user',
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
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<p>State:</p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_time + ' -- ' + item.event_datetime_off_time + '</p>';
                                content += '</div>';
                                content += '<div class="col d-flex justify-content-center">';
                                content += '<div class="schedule-details">';
                                content += '<label class="switch">';
                                content += '<input type="checkbox" ' + (item.state === 'Active' ? 'checked' : '') + ' disabled>';
                                content += '<span class="slider round"></span>';
                                content += '</label>';
                                content += '</div>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + item.event_datetime_date + ' -- ' + item.event_datetime_off_date + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '<p></p>';
                                content += '</div>';
                                content += '</div>';//row end

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>' + 'Action: ' + item.description + '</p>';
                                content += '</div>';
                                content += '<div class="col-3">';
                                content += '</div>';
                                content += '</div>';

                                content += '<div class="row">';
                                content += '<div class="col-6">';
                                content += '<p>Status: ' + item.status + '</p>';
                                content += '</div>';
                                content += '<div class="col-3" style="text-align: center;">';
                                content += '</div>';
                                content += '</div>';// row end     

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
        url: '/validate-date-user',
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
            url: '/validate-time-user',
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
        var lineBreak = $('<br>');

        // Perform an AJAX request to check for overlaps
        $.ajax({
            type: 'GET',
            url: '/check-overlap-user',
            data: {
                fromDateTime: fromDateTime,
                toDateTime: toDateTime,
            },
            success: function (response) {
                if (response.overlap) {
                    $('#existingSchedulesErrorTo').text('Schedule overlaps with an active schedule!').append(lineBreak);
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
        var lineBreak = $('<br>');
        $.ajax({
            url: '/check-existing-schedules-user',
            type: 'GET',
            data: {event_datetime: eventDateTime},
            success: function (response) {
                if (response.error) {
                    $('#existingSchedulesError').text(response.error).append(lineBreak);
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
            var lineBreak = $('<br>');
            // Make an AJAX request to check existing schedules
            $.ajax({
                url: '/check-existing-schedules-user',
                type: 'GET',
                data: {event_datetime: eventDateTime},
                success: function (response) {
                    if (response.error) {
                        $('#existingSchedulesError').text(response.error).append(lineBreak);
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
                url: '/check-overlap-user',
                data: {
                    fromDateTime: fromDateTime,
                    toDateTime: toDateTime,
                },
                success: function (response) {
                    if (response.overlap || response.error) {
                        $('#existingSchedulesErrorTo').text('NOTICE: The schedule overlaps with an active schedule. If created, it would have a pending status.').append(lineBreak);
                        
                    } else {
                        $('#existingSchedulesErrorTo').text('');
                        // $('#sub').prop('disabled', false);
                        
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
</script><!-- automatic detect for to: date & time  Schedule Error: Schedule overlaps with an active schedule!  -->

<script>
    $(document).ready(function () {
        function checkFordatetime() {
            var fromDateTime = $('#dateTimePicker3').val();
            var toDateTime = $('#dateTimePicker4').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var lineBreak = $('<br>');

            $.ajax({
                type: 'POST',
                url: '/validate-event-time-user',
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
                        $('#pasterror').text('Schedule Error: To date & time must be after From date & time.').append(lineBreak);
                        $('#sub').prop('disabled', true);
                    } else {
                        $('#pasterror').text('');
                        // $('#sub').prop('disabled', false);
                        
                    }
                },
                error: function (error) {
                    console.error('Error checking for Schedule Error:', error);
                }
            });
        }
        $('#dateTimePicker3, #dateTimePicker4').on('change', function () {
            checkFordatetime();
        });
    });
</script><!-- automatic detect for from and to: date & time custom Schedule Error: To date & time must be after From date & time-->

<script>
    $(document).ready(function() {
        $('#dateTimePicker3, #dateTimePicker4').on('change', function() {
            var dateTime = $(this).val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/validate-datetime-user',
                method: 'POST',
                data: { dateTime: dateTime,
                        _token: csrfToken },
                success: function(response) {
                    if (!response.isValid) {
                        $('#separateError').text('You cannot make a schedule in the past');
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
</script>

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
</script><!-- formatted time -->

<!-- <script>
    // Function to check for overlapping schedules based on action
    function checkForActionOverlap() {
        var fromDateTime = $('#dateTimePicker3').val();
        var toDateTime = $('#dateTimePicker4').val();
        var description = $('#description').val();

        $.ajax({
            type: 'GET',
            url: '/check-for-action-overlap', 
            data: {
                fromDateTime: fromDateTime,
                toDateTime: toDateTime,
                description: description,
            },
            success: function (response) {
                if (response.overlap) {
                    // There is an overlap, display the error message
                    $('#existingactionerror').text('Schedule action overlaps with an active schedule!');
                    $('#sub').prop('disabled', true);
                } else {
                    // No overlap, clear the error message
                    $('#existingactionerror').text('');
                    $('#sub').prop('disabled', false);
                }
            },
            error: function (error) {
                console.error('Error checking for overlap:', error);
            }
        });
    }
</script> -->

