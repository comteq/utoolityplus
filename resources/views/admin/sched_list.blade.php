<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    #year {
        width: auto;
    }
    #month {
        width: auto;
    }
    #day {
        width: auto;
    }

    .label .customlabel {
    margin-bottom: 0 !important;  
    }

    p{
        margin-bottom: 0 !important;
    }

    .custom-border a {
        display: inline-block;
        vertical-align: middle;
    }

    .custom-select{
        width: auto;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    
    body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .site-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;

        }

        .custom-card {
            flex: 1;
        }

        .card-footer {
            margin-top: auto;
        }
  
</style>

@include('nav')

<div class="site-container">

    <div class="card-header">

        @php
            $filterYear = request('year', '');
            $filterMonth = request('month', '');
            $filterDay = request('day', '');
            $filterHour = request('hour', '');
            $filterActivities = request('activities', '');
        @endphp
    
        <div class="row">
    
        <div class="col-md-auto custom-border text-center" style="margin-top: 10px;">
            <a href="{{ route('schedule-admin.filter', ['year' => $filterYear, 'month' => $filterMonth, 'day' => $filterDay]) }}" class="btn btn-dark" id="prevButton"><</a>
            <a href="" class="btn btn-secondary"> <p>{{ $filterText}}</p></a>
            <a href="{{ route('schedule-admin.filter', ['year' => $filterYear, 'month' => $filterMonth, 'day' => $filterDay,'week' => $currentWeek]) }}" class="btn btn-dark" id="nextButton">></a>
        </div>
    
        <div class="col custom-border text-center" style="margin-top: 10px;">
          <a href="" class="btn btn-light"><p>Current:</p></a>
          <a href="{{ route('schedule-admin.filter', ['year' => $currentYear]) }}" class="btn btn-dark">Year</a>
          <a href="{{ route('schedule-admin.filter', ['year' => $currentYear, 'month' => $currentMonth]) }}" class="btn btn-dark">Month</a>
          <a href="{{ route('schedule-admin.filter', ['year' => $currentYear, 'month' => $currentMonth, 'week' => $currentWeek]) }}" class="btn btn-dark">Week</a>
          <a href="{{ route('schedule-admin.filter', ['year' => $currentYear, 'month' => $currentMonth, 'day' => $currentDay]) }}" class="btn btn-dark">Day</a>
        </div>
    
        <div class="col-md-auto custom-border text-center" style="margin-top: 10px;">
            <div text-right>
                <button id="toggleFilter" class="btn btn-dark" onclick="toggleFilterForm()">
                    Advance Filter
                </button>
            </div>
        </div>
    
        </div> <!-- row1 -->
    
        <div class="row justify-content-center" style="margin-top: 15px;" >
            <form action="{{ route('schedule-admin.filter') }}" method="get" id="filterForm" style="display:none">
    
                <select name="year" id="year" class="btn btn-secondary dropdown-toggle" >
                    <option value=""{{ request('year') == '' ? ' selected' : '' }} disabled>Year</option>
                    <option value="">All</option>
                        @foreach ($uniqueYears as $year)
                            <option value="{{ $year }}"{{ request('year') == $year ? ' selected' : '' }}>{{ $year }}</option>
                        @endforeach
                </select>
    
                <select name="month" id="month" class="btn btn-secondary dropdown-toggle" placeholder="Month">
                    <option value=""{{ request('month') == '' ? ' selected' : '' }} disabled>Month</option>
                    <option value="">All</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"{{ request('month') == $i ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                </select>
    
                <select name="week" id="week" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('week') == '' ? ' selected' : '' }} disabled>Week</option>
                    <option value="">All</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}"{{ request('week') == $i ? ' selected' : '' }}>Week {{ $i }}</option>
                        @endfor
                </select>
    
                <select name="day" id="day" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('day') == '' ? ' selected' : '' }} disabled>Day</option>
                    <option value="">All</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}"{{ request('day') == $i ? ' selected' : '' }}>{{ $i }}</option>
                        @endfor
                </select>
    
                <select name="dayOfWeek" id="dayOfWeek" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('dayOfWeek') == '' ? ' selected' : '' }} disabled>Day of Week</option>
                    <option value="">All</option>
                    <option value="1"{{ request('dayOfWeek') == '1' ? ' selected' : '' }}>Monday</option>
                    <option value="2"{{ request('dayOfWeek') == '2' ? ' selected' : '' }}>Tuesday</option>
                    <option value="3"{{ request('dayOfWeek') == '3' ? ' selected' : '' }}>Wednesday</option>
                    <option value="4"{{ request('dayOfWeek') == '4' ? ' selected' : '' }}>Thursday</option>
                    <option value="5"{{ request('dayOfWeek') == '5' ? ' selected' : '' }}>Friday</option>
                    <option value="6"{{ request('dayOfWeek') == '6' ? ' selected' : '' }}>Saturday</option>
                    <option value="7"{{ request('dayOfWeek') == '7' ? ' selected' : '' }}>Sunday</option>
                </select>
    
                <select name="hour" id="hour" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('hour') == '' ? ' selected' : '' }} disabled>Hour</option> 
                    <option value="">All</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <optgroup label="{{ $i }}">
                                <option value="{{ $i }}:00 AM"{{ request('hour') == $i . ':00 AM' ? ' selected' : '' }}>{{ $i }} AM</option>
                                <option value="{{ $i }}:00 PM"{{ request('hour') == $i . ':00 PM' ? ' selected' : '' }}>{{ $i }} PM</option>
                            </optgroup>
                        @endfor
                </select>
    
                <select name="description" id="description" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('description') == '' ? ' selected' : '' }} disabled>Action</option>
                    <option value="">All</option>
                    <option value="ON"{{ request('description') == 'ON' ? ' selected' : '' }}>ON</option>
                    <option value="OFF"{{ request('description') == 'OFF' ? ' selected' : '' }}>OFF</option>
                </select>
    
                <select name="state" id="state" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('state') == '' ? ' selected' : '' }} disabled>State</option>
                    <option value="">All</option>
                    <option value="Active"{{ request('state') == 'Active' ? ' selected' : '' }}>Active</option>
                    <option value="In-Active"{{ request('state') == 'In-Active' ? ' selected' : '' }}>In-Active</option>
                </select>
    
                <select name="status" id="status" class="btn btn-secondary dropdown-toggle">
                    <option value=""{{ request('status') == '' ? ' selected' : '' }} disabled>Status</option>
                    <option value="">All</option>
                    <option value="In-Progress"{{ request('status') == 'In-Progress' ? ' selected' : '' }}>In-Progress</option>
                    <option value="Pending"{{ request('status') == 'Pending' ? ' selected' : '' }}>Pending</option>
                    <option value="Processing"{{ request('status') == 'Processing' ? ' selected' : '' }}>Processing</option>
                    <option value="Finished"{{ request('status') == 'Finished' ? ' selected' : '' }}>Finished</option>
                </select>
    
            </form>
        </div> <!-- row2 -->
    
    </div> <!-- card-header -->
    
    <div class="card-body" >
        <table class="table table-striped table-light" id="schedule-table">
            <thead>
                <tr>
                <th><input type="checkbox" id="checkAll" class="check-all-checkbox"></th>
                <th>Day</th>
                <th>Date</th>
                <th>Time</th>
                <th>Activity</th>
                <th>State</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
                </tr>
            </thead>
    
            <tbody>
                    @foreach($scheduless as $sched)
                    <tr data-schedule-id="{{ $sched->id }}">
                        <td><input type="checkbox" class="schedule-checkbox" data-schedule-id="{{ $sched->id }}"></td>
                        <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('l') }}</td>
                        <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($sched->event_datetime_off)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sched->event_datetime_off)->format('h:i A') }}</td>
                        <td>{{ $sched->description }}</td>
                        <td>{{ $sched->state }}</td>
                        <td class="status-column">{{ $sched->status }}</td>
                        <td class="status-column">{{ $sched->created_at }}</td>
                        <td>
                            <button class="btn btn-danger delete-btn" data-schedule-id="{{ $sched->id }}">🗑️</button>
                            <button class="btn btn-warning delete-btn2" data-schedule-id="{{ $sched->id }}">🗑️</button>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
            <div class="card-footer text-right">
                <button class="btn btn-danger asa" id="deleteSelected">Delete Selected</button>
            </div>
        </table>
    </div> <!-- card-body -->
</div>


@include('footer')

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('nextButton').addEventListener('click', function (event) {
            handleDirection(event, 'next');
        });

        document.getElementById('prevButton').addEventListener('click', function (event) {
            handleDirection(event, 'previous');
        });

        function handleDirection(event, direction) {
            // Prevent the default button behavior (avoid double triggering)
            event.preventDefault();

            // Get the current URL
            var currentUrl = window.location.href;

            // Extract parameters from the URL
            var url = new URL(currentUrl);

            // Get the query parameters
            var params = url.searchParams;

            // Update the last parameter in the URL
            var lastParam = null;
            var lastParamName = null;

            for (let pair of params.entries()) {
                lastParamName = pair[0];
                lastParam = pair[1];
            }

            // If there is no parameter, do nothing
            if (lastParamName === null || lastParam === null) {
                return;
            }

            // Increment or decrement the last parameter value based on the direction
            var updatedValue = direction === 'next' ? parseInt(lastParam) + 1 : parseInt(lastParam) - 1;

            // Limit month to 12
            if (lastParamName === 'month') {
                updatedValue = Math.min(Math.max(updatedValue, 1), 12);
            }

            // Limit day to 31
            if (lastParamName === 'day') {
                updatedValue = Math.min(Math.max(updatedValue, 1), 31);
            }

            if (lastParamName === 'week') {
                updatedValue = Math.min(Math.max(updatedValue, 1), 5);
            }

            // Update the last parameter in the URL
            params.set(lastParamName, updatedValue);

            // Build the updated URL
            var updatedUrl = url.origin + url.pathname + '?' + params.toString();

            // Redirect to the updated URL
            window.location.href = updatedUrl;
        }

        function getWeeksInMonth(year, month) {
            // Calculate the last day of the month
            var lastDay = new Date(year, month, 0).getDate();

            // Calculate the number of weeks in the month
            var weeks = Math.ceil(lastDay / 7);

            return weeks;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the form and select elements
        var filterForm = document.getElementById('filterForm');
        var filterSelects = filterForm.querySelectorAll('select');
        // Add event listener to each select element
        filterSelects.forEach(function (select) {
            select.addEventListener('change', function () {
                // Submit the form when a filter option changes
                filterForm.submit();
            });
        });
    });
</script>

<script>
    function toggleFilterForm() {
        var filterForm = document.getElementById("filterForm");
        filterForm.style.display = (filterForm.style.display === "none" || filterForm.style.display === "") ? "block" : "none";
    }
</script>

<!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener to all delete buttons
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                // Get the schedule ID from the data attribute
                var scheduleId = button.dataset.scheduleId;

                // Use SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX to send a delete request to the server
                        fetch('/schedule/' + scheduleId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => {
                            if (response.ok) {
                                // Optionally, you can remove the deleted row from the table
                                button.closest('tr').remove();
                                Swal.fire('Deleted!', 'Your schedule has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', 'Cannot delete schedule with status "Processing"', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            });
        });
    });
</script><!-- normal delete -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener to all delete buttons
        document.querySelectorAll('.delete-btn2').forEach(function (button) {
            button.addEventListener('click', function () {
                // Get the schedule ID from the data attribute
                var scheduleId = button.dataset.scheduleId;

                // Use SweetAlert for confirmation
                Swal.fire({
                    title: 'Force Delete: Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX to send a delete request to the server
                        fetch('/scheduleforce/' + scheduleId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => {
                            if (response.ok) {
                                // Optionally, you can remove the deleted row from the table
                                button.closest('tr').remove();
                                Swal.fire('Deleted!', 'Your schedule has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', 'Cannot delete schedule with status "Processing"', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            });
        });
    });
</script><!-- force delete -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- <script>
    $(document).ready(function () {
        $('.asa').on('click', function () {
            var scheduleIds = [];

            $('.schedule-checkbox:checked').each(function () {
                scheduleIds.push($(this).data('schedule-id'));
            });

            if (scheduleIds.length > 0) {
                $.ajax({
                    url: '{{ route("schedules.deleteSelected") }}',
                    type: 'POST',
                    data: {
                        schedule_ids: scheduleIds,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            // Display the error message using SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Display the AJAX request error using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Cannot delete schedule with status "Processing"',
                        });
                    }
                });
            } else {
                alert('No schedules selected for deletion');
            }
        });
    });
</script> -->

<script>
    $(document).ready(function () {
        $('.asa').on('click', function () {
            var scheduleIds = [];

            $('.schedule-checkbox:checked').each(function () {
                scheduleIds.push($(this).data('schedule-id'));
            });

            if (scheduleIds.length === 1) {
                // Display success notification if only one schedule is selected
                Swal.fire({
                    icon: 'success',
                    title: 'Schedule Deleted',
                    text: 'The selected schedule has been deleted successfully',
                });
            }

            if (scheduleIds.length > 0) {
                // Display confirmation dialog before proceeding with deletion
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User confirmed, proceed with deletion
                        $.ajax({
                            url: '{{ route("schedules.deleteSelected") }}',
                            type: 'POST',
                            data: {
                                schedule_ids: scheduleIds,
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (response) {
                                if (response.success) {
                                    // Display success notification after successful deletion
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    }).then(function () {
                                        location.reload();
                                    });
                                } else {
                                    // Display error message using SweetAlert2
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                // Display AJAX request error using SweetAlert2
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Cannot delete schedule with status "Processing"',
                                });
                            }
                        });
                    }
                });
            } else {
                // Replace alert with SweetAlert2 notification
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'No schedules selected for deletion',
                });
            }
        });
    });
</script>





<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.css" rel="stylesheet">
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#schedule-table').DataTable({
            responsive: true, // Enable responsiveness
            dom: 'Bfrtip', // Add buttons for export (print, CSV, etc.)
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                {
                    extend: 'print',
                    title: "Schedule List",
                    exportOptions: 
                    {
                        columns: ":not(.not-export-column)"
                    }
                }
            ],
            columnDefs: [
                { orderable: false, targets: 0 }, // Disable ordering for the first checkbox column
            ],
            order: [],

        });
    });

</script>