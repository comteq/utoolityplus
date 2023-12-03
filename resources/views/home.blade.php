<!-- resources/views/home.blade.php -->
@include('nav')
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content, including Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add your custom CSS if needed -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    @unless (isset($contentSection))
    @include('sidebar')
@endunless

    <!-- Page Content -->
    <div id="content">
        <!-- Add your page content here -->
        @yield('content')
    </div>
</div>

<!-- Bootstrap JS and other scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $('.sidebar-link').click(function(e) {
        e.preventDefault();

        // Fetch the route from the link
        var route = $(this).attr('href');
        console.log('Making AJAX request to:', route); // Add this line

        // Use AJAX to load the content without refreshing the page
        $.ajax({
            url: route,
            type: 'GET',
            success: function(data) {
                // Replace the content of the #content div
                $('#content').html(data);
            },
            error: function() {
                console.error('Error loading content.');
            }
        });
    });
});

</script>

