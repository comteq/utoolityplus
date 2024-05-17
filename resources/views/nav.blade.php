<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="{{ asset('/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
  <script src="{{ asset('js/pdfmake.min.js') }}"></script>
  <script src="{{ asset('js/vfs_fonts.js') }}"></script>
  <script src="{{ asset('js/datatables.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap452.min.js') }}"></script>

  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8i04FZ5LE3r5M46P7u6UdJOmea1BAsjFuCZuUO5/lM6ZIezn3uPOEm1Y6L" crossorigin="anonymous"> --}}
  <title>uTOOLity+</title>

  <style>
    .customcontainer{
      margin-right: 10px;
    }

    #openNotificationBtn{
      color:red;
      font-weight: bold;
    }
    #openNotificationBtns{
      color:red;
      font-weight: bold;
    }

    .navbar {
      background-color: #242424; /* Navbar background color */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding-left: 15px !important;
      padding-right: 15px;
    }

    .navbar-brand,
    .navbar-nav .nav-link {
      color: #ffffff !important; /* Navbar text color */
    }

    .navbar-toggler-icon {
      background-color: #ffffff; /* Navbar toggler icon color */
    }

    .navbar-toggler:focus,
    .navbar-toggler:active {
      outline: none;
    }

    .navbar-nav .nav-link {
      padding: 8px 15px;
      transition: 0.3s;
      border-radius: 5px;
    }

    .navbar-nav .nav-link:hover {
      background-color: #343434; /* Hover background color */
    }

    .navbar-nav .nav-link i {
      margin-right: 5px;
    }

    a.nav-link {
      font-size: 1.2rem;
    }

    .notification-window {
      display: none;
      position: fixed;
      background: white;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      z-index: 999;
    }
    .notification-windows {
      display: none;
      position: fixed;
      background: white;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      z-index: 999;
    }

    .navbar-nav .nav-item.active .nav-link,
    .navbar-nav .nav-link.active {
      background-color: #343434;
      color: #ffffff !important;
    }

    .navbar-nav .nav-item .nav-link {
      padding: 8px 15px;
      transition: 0.3s;
      border-radius: 5px;
      color: #ffffff;
    }

    .navbar-nav .nav-item .nav-link:hover {
      background-color: #343434;
    }

    .navbar-nav.left-nav {
      margin-right: auto; /* Push the left-aligned items to the left */
    }

    .notification-container2 {
        display: none;
    }
    /* Media query for screens 992px and below */
    @media (max-width: 992px) {
        /* Remove or override active state styles for elements */
        .navbar-toggler-icon  {
          background-color: transparent;
        }

        .notification-container {
            display: none;
        }
        .notification-container2 {
            display: block;
        }
        .navbar-header {
        display: flex;
        align-items: center;
    }

      .customcontainer {
          margin-right: 10px;
      }
    }

    .navbar-nav .dropdown-menu {
      right: 0 !important; /* Align dropdown to the right */
      left: auto; /* Override left positioning */
      position: absolute; /* Position dropdown absolutely */
    }

  </style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-4">
  <img src="{{ asset('images/logo-inline.png') }}" height="60px" class="navbar-brand">

  <div class="navbar-header">

  @if(auth()->user() && auth()->user()->role !== 'user')
    <!-- Notification bell -->
    <div class="customcontainer notification-container2">
    <!-- Notification bell -->
 
    <!-- <div id="openNotificationBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-bell" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
        </svg>
        <span id="notification-count">0</span>
    </div>
    <div class="notification-window" id="notificationWindow"></div> -->
  </div>
  @endif

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  </div>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav left-nav">

      <li class="nav-item @if(request()->url() == route('room-controls')) active @endif">
        <a class="nav-link" href="{{ route('room-controls') }}">Room Controls</a>
      </li>
    
      
      @if(auth()->user()->role == 'user')
      <!-- <li class="nav-item @if(request()->url() == route('schedule.index')) active @endif">
        <a class="nav-link" href="{{ route('schedule.index') }}">Set Schedule</a>
      </li> -->
      <li class="nav-item @if(request()->url() == route('schedule.filter')) active @endif">
        <a class="nav-link" href="{{ route('schedule.filter') }}">Schedule List</a>
      </li>
      @else
      <li class="nav-item @if(request()->url() == route('scheduleadmin.index')) active @endif">
        <a class="nav-link" href="{{ route('scheduleadmin.index') }}">Set Schedule</a>
      </li>
      <li class="nav-item @if(request()->url() == route('schedule-admin.filter')) active @endif">
        <a class="nav-link" href="{{ route('schedule-admin.filter') }}">Schedule List</a>
      </li>
      @endif
      <li class="nav-item @if(request()->url() == route('activity-logs.index')) active @endif">
        <a class="nav-link" href="{{ route('activity-logs.index') }}">Activity Logs</a>
      </li>

    </ul>

    @if(auth()->user() && auth()->user()->role !== 'user')
    <!-- Notification bell -->
    <!-- <div class="customcontainer notification-container">
    <div id="openNotificationBtns">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-bell" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
        </svg>
        <span id="notification-counts">0</span>
    </div>
    <div class="notification-window" id="notificationWindows"></div>
</div> -->
<!-- End notification bell -->
    @endif

    <!-- User dropdown -->
    <ul class="navbar-nav">
      <li class="nav-item dropdown @if(request()->routeIs(['profile.show', 'users.index'])) active @endif">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ auth()->user()->name }}
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <!-- Add dropdown items as needed -->
          <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
          {{-- Check if the user's role is not 'user' before displaying the 'Accounts' link --}}
          @if(auth()->user()->role != 'user')
          <a class="dropdown-item" href="{{ route('users.index') }}">Accounts</a>
          <a class="dropdown-item" href="{{ route('device') }}">Device</a>
          @endif
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
          </form>
        </div>
      </li>
    </ul>
    <!-- End user dropdown -->
  </div>
</nav>

<!-- <script>
  function toggleNotificationWindow() {
    var notificationWindow = $('#notificationWindow');
    notificationWindow.slideToggle();

    // Fetch pending schedules count and details and update the content
    $.ajax({
      url: '/get-pending-schedule-count',
      method: 'GET',
      success: function (data) {
        updateNotificationContent(data);
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  // Function to update the content of the notification window
  function updateNotificationContent(data) {
    var content = '';

    // Display the count
    $('#notification-count').text(data.count);

    if (data.schedules.length > 0) {
      content += '<ul>';
      data.schedules.forEach(function (schedule) {
        content += '<li><strong>ID:</strong> ' + schedule.id + '<br>';
        content += '<strong>From:</strong> ' + formatDateTimenotif(schedule.event_datetime) + '<br>';
        content += '<strong>To:</strong> ' + formatDateTimenotif(schedule.event_datetime_off) + '<br>';
        content += '<strong>Action:</strong> ' + schedule.description + '</li>';
        content += '<hr>';
      });
      content += '</ul>';
    } else {
      content += '<p>No pending schedules</p>';
    }

    $('#notificationWindow').html(content);
  }

  function formatDateTimenotif(dateTime) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    return new Date(dateTime).toLocaleTimeString('en-US', options);
  }

  // Add a click event listener to the button
  $('#openNotificationBtn').on('click', toggleNotificationWindow);
</script> -->
<!-- notification pending -->


<script>
  $(document).ready(function () {
  // Update the notification contents initially and then at regular intervals
  updateNotificationContents();
  setInterval(updateNotificationContents, 60000);

  // Add a click event listener to the button to toggle the notification window
  $('#openNotificationBtns').on('click', function() {
    $('#notificationWindows').slideToggle();
  });

  // Function to update the content of the notification window
  function updateNotificationContents() {
    // Fetch pending schedules count and details and update the content
    $.ajax({
      url: '/get-pending-schedule-count',
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        console.log("Received data:", data); 
        updateNotificationUI(data);
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  // Function to update the UI with the new data
  function updateNotificationUI(data) {
    console.log("Updating contents with data:", data);
    var content = '';

    // Display the count
    $('#notification-counts').text(data.count);

    if (data.schedules.length > 0) {
      content += '<ul>';
      data.schedules.forEach(function (schedule) {
        content += '<li><strong>ID:</strong> ' + schedule.id + '<br>';
        content += '<strong>From:</strong> ' + formatDateTimenotifs(schedule.event_datetime) + '<br>';
        content += '<strong>To:</strong> ' + formatDateTimenotifs(schedule.event_datetime_off) + '<br>';
        content += '<strong>Action:</strong> ' + schedule.description + '</li>';
        content += '<hr>';
      });
      content += '</ul>';
    } else {
      content += '<p>No pending schedules</p>';
    }
    $('#notificationWindows').html(content);
  }

  function formatDateTimenotifs(dateTime) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    return new Date(dateTime).toLocaleTimeString('en-US', options);
  }
});
</script>
<!-- desktop -->

<script>
  $(document).ready(function () {
  // Update the notification contents initially and then at regular intervals
  updateNotificationContents();
  setInterval(updateNotificationContents, 60000);

  // Add a click event listener to the button to toggle the notification window
  $('#openNotificationBtn').on('click', function() {
    $('#notificationWindow').slideToggle();
  });

  // Function to update the content of the notification window
  function updateNotificationContents() {
    // Fetch pending schedules count and details and update the content
    $.ajax({
      url: '/get-pending-schedule-count',
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        console.log("Received data:", data); 
        updateNotificationUI(data);
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  // Function to update the UI with the new data
  function updateNotificationUI(data) {
    console.log("Updating contents with data:", data);
    var content = '';

    // Display the count
    $('#notification-count').text(data.count);

    if (data.schedules.length > 0) {
      content += '<ul>';
      data.schedules.forEach(function (schedule) {
        content += '<li><strong>ID:</strong> ' + schedule.id + '<br>';
        content += '<strong>From:</strong> ' + formatDateTimenotifs(schedule.event_datetime) + '<br>';
        content += '<strong>To:</strong> ' + formatDateTimenotifs(schedule.event_datetime_off) + '<br>';
        content += '<strong>Action:</strong> ' + schedule.description + '</li>';
        content += '<hr>';
      });
      content += '</ul>';
    } else {
      content += '<p>No pending schedules</p>';
    }
    $('#notificationWindow').html(content);
  }

  function formatDateTimenotifs(dateTime) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    return new Date(dateTime).toLocaleTimeString('en-US', options);
  }
});
</script>
<!-- phone -->

</body>
</html>
