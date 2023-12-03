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
 
  
</style>

@extends('home')

@section('content')
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
        <a href="{{ route('schedule.filter', ['year' => $filterYear, 'month' => $filterMonth, 'day' => $filterDay]) }}" class="btn btn-dark" id="prevButton"><</a>
        <a href="" class="btn btn-secondary"> <p>{{ $filterText}}</p></a>
        <a href="{{ route('schedule.filter', ['year' => $filterYear, 'month' => $filterMonth, 'day' => $filterDay,'week' => $currentWeek]) }}" class="btn btn-dark" id="nextButton">></a>
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

        </form>
    </div> <!-- row2 -->

</div> <!-- card-header -->

<div class="card-body">
    <table class="table table-striped table-light ">
        <thead>
            <tr>
            <th><input type="checkbox" id="checkAll" class="check-all-checkbox"></th>
            <th>ID</th>
            <th>Day</th>
            <th>Date</th>
            <th>Time</th>
            <th>Activity</th>
            <th>State</th>
            <th>Status</th>
            <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @if($scheduless->isEmpty())
                <tr>
                    <td colspan="8" style=" text-align: center;">No records found</td>
                </tr>
            @else
                @foreach($scheduless as $sched)
                <tr data-schedule-id="{{ $sched->id }}">
                    <td><input type="checkbox" class="schedule-checkbox" data-schedule-id="{{ $sched->id }}"></td>
                    <td>{{ $sched->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('l') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($sched->event_datetime_off)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sched->event_datetime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sched->event_datetime_off)->format('h:i A') }}</td>
                    <td>{{ $sched->description }}</td>
                    <td>{{ $sched->state }}</td>
                    <td class="status-column">{{ $sched->status }}</td>
                    <td>
                        <button class="btn btn-danger delete-btn" data-schedule-id="{{ $sched->id }}">🗑️</button>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
        <div class="card-footer text-right">
            <button class="btn btn-danger delete-selected-btn">Delete Selected</button>
        </div>
    </table>
</div> <!-- card-body -->
@endsection

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener to all delete buttons
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                // Get the schedule ID from the data attribute
                var scheduleId = button.dataset.scheduleId;

                // Ask for confirmation before deletion (optional)
                if (confirm('Are you sure you want to delete this schedule?')) {
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
                            } else {
                                alert('Failed to delete the schedule.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    });
</script><!-- delete and confirmation -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the "Check All" checkbox and the individual checkboxes
        var checkAllCheckbox = document.getElementById('checkAll');
        var scheduleCheckboxes = document.querySelectorAll('.schedule-checkbox');

        // Add event listener to the "Check All" checkbox
        checkAllCheckbox.addEventListener('change', function () {
            // Set the checked state of all individual checkboxes based on the "Check All" checkbox
            scheduleCheckboxes.forEach(function (checkbox) {
                checkbox.checked = checkAllCheckbox.checked;
            });
        });

        // Get the "Delete Selected" button
        var deleteSelectedButton = document.querySelector('.delete-selected-btn');

        // Add event listener to the "Delete Selected" button
        deleteSelectedButton.addEventListener('click', function () {
            // Get the IDs of the checked schedules
            var selectedIds = Array.from(scheduleCheckboxes)
                .filter(function (checkbox) {
                    return checkbox.checked;
                })
                .map(function (checkbox) {
                    return checkbox.dataset.scheduleId;
                });

            // Ask for confirmation before deletion (optional)
            if (confirm('Are you sure you want to delete the selected schedules?')) {
                // Use AJAX to send a delete request to the server for each selected schedule
                selectedIds.forEach(function (id) {
                    fetch('/schedule/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                console.error('Failed to delete schedule with ID ' + id);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });

                // Optionally, you can update the UI to remove the deleted rows
                scheduleCheckboxes.forEach(function (checkbox) {
                    if (checkbox.checked) {
                        checkbox.closest('tr').remove();
                    }
                });
            }
        });
    });
</script><!-- checkall and delete -->

<script>

    document.addEventListener('DOMContentLoaded', function () {
        function updateSchedulesStatus() {
            $.ajax({
                url: "{{ route('update-schedules-status') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (updatedSchedules) {
                    // Iterate through each updated schedule and update the status
                    updatedSchedules.forEach(function (schedule) {
                        var scheduleId = schedule.id;
                        var newStatus = schedule.status;

                        // Find the corresponding row in the table
                        var row = $('tr[data-schedule-id="' + scheduleId + '"]');

                        if (row.find('.status-column').text() !== newStatus) {
                            // Update the status column in the row
                            row.find('.status-column').text(newStatus);
                            console.log('Updated status for schedule ID ' + scheduleId + ' to ' + newStatus);
                        }
                    });

                    console.log('Schedules status updated successfully');
                },
                error: function (error) {
                    console.error('Error updating schedules status:', error);
                }
            });
        }

        updateSchedulesStatus();

        setInterval(updateSchedulesStatus, 5000);
    });

</script><!-- dynamicly udoate if a schedule is updated -->

