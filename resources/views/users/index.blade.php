<div class="wrapper">
@include('nav')

<div class="container mt-2">
    <h2>Account List</h2>
    @if(session('flash_message'))
        <div class="alert alert-success">
            {{ session('flash_message') }}
        </div>
    @endif

    @if(session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-2">Create User</a>
    <table class="table table-striped" id="users-table">
        <thead>
            <tr class="text-light">
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date Created</th>
                <th class="not-export-column">Actions</th>
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
                        @if ($user->id !== 1) {{-- Check if the user is not the first admin and not the authenticated user --}}
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                        @endif
                        @if ($user->id !== 1 && $user->id !== Auth::id()) {{-- Check if the user is not the first admin and not the authenticated user --}}
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endif
                    </td>    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@include('footer')


<style>
    thead {
        background-color: #007BFF;
    }

    tr td {
        font-weight: bold;
    }

    body, html {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            /* Add other styles for your content */
            margin-top: 20px; /* Adjust as needed */
            margin-bottom: 20px; /* Adjust as needed */
        }

        .footer {
            /* Add styles for your footer */
            padding: 10px;
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
          dom: 'Bfrtip',
          buttons: [
             'copyHtml5',
             'excelHtml5',
             'csvHtml5',
             'pdfHtml5',
            {
                extend: 'print',
                title: "Accounts List",
                exportOptions: 
                {
                    columns: ":not(.not-export-column)"
                }
            }
          ],
          order: [[0, 'asc']], // Sort by the first column (Date & Time) in descending order
       });
    });
 </script>
