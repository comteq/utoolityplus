<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Room Controls</h3>
        </div>  

    <ul class="list-unstyled components">
        <li class="{{ request()->is('acu') ? 'active' : '' }}">
            <a href="{{ route('acu') }}" class="mobile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-lightning-charge mr-3" viewBox="0 0 16 16">
                    <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z"/>
                </svg>
                ACU
            </a>
        </li>
        
        <li id="lights-menu">
            <a href="#" class="mobile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-lightbulb mr-3" viewBox="0 0 16 16">
                    <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13a.5.5 0 0 1 0 1 .5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1 0-1 .5.5 0 0 1 0-1 .5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6m6-5a5 5 0 0 0-3.479 8.592c.263.254.514.564.676.941L5.83 12h4.342l.632-1.467c.162-.377.413-.687.676-.941A5 5 0 0 0 8 1"/>
                </svg>
                Lights
            </a>
        </li>

        <li class="{{ request()->is('schedule') ? 'active' : '' }}">
            <a href="{{ route('scheduleadmin.index') }}" class="mobile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-clock mr-3" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                    </svg>
                Set Schedule
            </a>
        </li>

        <li class="{{ request()->is('schedule-List') ? 'active' : '' }}">
            <a href="{{ route('schedule-admin.filter') }}" class="mobile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-calendar3 mr-3" viewBox="0 0 16 16">
                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                Schedule List
            </a>
        </li>
    </ul>
</nav>



<style>


    /*
    DEMO STYLE
*/


a,
a:hover,
a:focus {
    color: inherit;
    text-decoration: none;
    transition: all 0.3s;
}

.line {
    width: 100%;
    height: 1px;
    border-bottom: 1px dashed #ddd;
    margin: 40px 0;
}

/* ---------------------------------------------------
    SIDEBAR STYLE
----------------------------------------------------- */

.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#sidebar {
    min-width: 250px;
    max-width: 250px;
    background: #1b40e6;
    color: #fff;
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -250px;
}

#sidebar .sidebar-header {
    padding: 20px;
}

#sidebar ul.components {
    padding: 20px 0;
}

#sidebar ul p {
    color: #fff;
    padding: 10px;
}

#sidebar ul li a {
    padding: 30px;
    font-size: 1.1em;
    display: block;
}

#sidebar ul li a:hover {
    color: #000000;
    background: #fff;
}

#sidebar ul li.active>a,
a[aria-expanded="true"] {
    color: #fff;
    background: #004085;
}

a[data-toggle="collapse"] {
    position: relative;
}

.dropdown-toggle::after {
    display: block;
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
}

ul ul a {
    font-size: 0.9em !important;
    padding-left: 30px !important;
    background: #000000;
}

ul.CTAs {
    padding: 20px;
}

ul.CTAs a {
    text-align: center;
    font-size: 0.9em !important;
    display: block;
    border-radius: 5px;
    margin-bottom: 5px;
}

a.download {
    background: #fff;
    color: #000000;
}

a.article,
a.article:hover {
    background: #ffffff !important;
    color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

#content {
    width: 100%;
    padding: 20px;
    min-height: 100vh;
    transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@media (max-width: 768px) {
  #sidebar {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .list-unstyled {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: row;
    }

    .list-unstyled li {
        margin-right: 10px;
    }

    .mobile-icon {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }

    .mobile-icon svg {
        margin-right: 5px;
    }

    .active {
        /* Add your active styles here */
        background-color: #e0e0e0;
        /* Add other styles as needed */
    }
}



</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    // Function to log activity
    function logActivity(action, state) {
        // Get CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
        // Log the activity
        $.ajax({
            type: 'POST',
            url: '/log-activity',
            data: { action: action, state: state },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    
        // Lights click event
        $('#lights-menu').on('click', function () {
            var lightsMenuItem = $(this);
            if (lightsMenuItem.hasClass('active')) {
                // Lights are currently on, turn them off
                lightsMenuItem.removeClass('active');
                // Add code to turn off lights here
    
                // Log activity for turning off lights
                logActivity('Lights Turned Off', 'Off');
    
                // Show SweetAlert for turning off lights
                Swal.fire({
                    icon: 'success',
                    title: 'Lights Turned Off',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                // Lights are currently off, turn them on
                lightsMenuItem.addClass('active');
                // Add code to turn on lights here
    
                // Log activity for turning on lights
                logActivity('Lights Turned On', 'On');
    
                // Show SweetAlert for turning on lights
                Swal.fire({
                    icon: 'success',
                    title: 'Lights Turned On',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
    </script>