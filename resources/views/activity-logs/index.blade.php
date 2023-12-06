@include('nav')

<div class="container">
    <h2>Activity Logs</h2>
    <label for="activity-filter">Filter by Activity:</label>
        <select id="activity-filter" class="form-control mb-2">
            <option value="">All Activities</option>
            <option value="Register">Register</option>
            <option value="Login">Login</option>
            <option value="Logout">Logout</option>
            <option value="Create User">Create User</option>
            <option value="Update User">Update User</option>
            <option value="Delete User">Delete User</option>
            <option value="Update Profile">Update Profile</option>
            <option value="Power Off ACU">Power Off ACU</option>
            <option value="Power On ACU">Power On ACU</option>
            <option value="Lights Turned On">Lights Turned On</option>
            <option value="Lights Turned Off">Lights Turned Off</option>
            <option value="Power On ACU">Power On ACU</option>
            <option value="Power Off ACU">Power Off ACU</option>
        </select>
        <table class="table table-striped" id="activity-table">
    
            <thead>
                <tr class="text-light">
                    <th>Date & Time</th>
                    <th>Name</th>
                    <th>Activity</th>
                    <th>Message</th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($activityLogs as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d h:i A') }}</td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->message }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    
    <style>
    thead {
        background-color: #007BFF;
    }
    
    tr td {
    font-weight: bold;
    }
    
    </style>
    
    <link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/date-1.5.1/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.js"></script>
    
    
    <script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('#activity-table').DataTable({
            responsive: true, // Enable responsiveness
            dom: 'Bfrtip', // Add buttons for export (print, CSV, etc.)
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columnDefs: [
                { type: 'date', targets: 0 } // Set the first column as a date type
            ],
            order: [[0, 'desc']], // Sort by the first column (Date & Time) in descending order
        });
    
        // Add event listener for the activity filter dropdown
        $('#activity-filter').on('change', function () {
            var selectedActivity = $(this).val();
    
            // Clear previous search and column-specific searches
            table.search('').columns().search('').draw();
    
            // Apply new search based on the selected activity
            if (selectedActivity !== '') {
                table.column(2).search(selectedActivity).draw();
            }
        });
    });
    
        </script>
    