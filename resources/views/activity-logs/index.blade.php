@include('nav')


<div class="container">
<h2>Activity Logs</h2>
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
                <td>{{ $log->created_at->format('M d, Y h:ia') }}</td>
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
<!-- ... (your existing DataTables and other includes) -->

<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

<script>
$(document).ready(function () {
   $('#activity-table').DataTable({
      responsive: true, // Enable responsiveness
      dom: 'Bfrtip', // Add buttons for export (print, CSV, etc.)
      buttons: [
         'copyHtml5',
         'excelHtml5',
         'csvHtml5',
         'pdfHtml5'
      ],
      select: true // Enable Select extension
   });

   // Add a custom filter dropdown for the "Activity" column
   var activityColumn = table.column(2); // Assuming "Activity" is the third column (zero-based index)
   var activities = ['Login', 'Logout', 'Create User', 'Delete User', 'Update User'];

   var select = $('<select><option value=""></option></select>')
      .appendTo(activityColumn.footer())
      .on('change', function () {
         var val = $.fn.dataTable.util.escapeRegex($(this).val());
         activityColumn.search(val ? '^' + val + '$' : '', true, false).draw();
      });

   activities.forEach(function (activity) {
      select.append('<option value="' + activity + '">' + activity + '</option>');
   });
});
</script>
