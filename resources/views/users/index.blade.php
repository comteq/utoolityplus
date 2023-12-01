@include('nav')

<div class="container">
        <h2>Account List</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-2">Create User</a>
    <table class="table table-striped" id="users-table">
        <thead>
            <tr class="text-light">
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at->format('M d, Y h:ia') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
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
       $('#users-table').DataTable({
          responsive: true, // Enable responsiveness
          dom: 'Bfrtip', // Add buttons for export (print, CSV, etc.)
          buttons: [
             'copyHtml5',
             'excelHtml5',
             'csvHtml5',
             'pdfHtml5'
          ]
       });
    });
 </script>