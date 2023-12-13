@include('nav')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<link rel="stylesheet" href="{{ asset('css/external-styles.css') }}">

<div class="card">

    <div class="card-header" style="text-align:center">
        <h1>Room #</h1>
    </div>

    <div class="card-body">

        <div class="card-deck">

            {{-- AC Card --}}
            <div class="card border-0">
                @php
                    $acState = App\Models\Unit::find(1)->AC;
                    $acImage = $acState === '1' ? 'ac-on.png' : 'ac-off.png';
                @endphp

                <img class="card-img-top mx-auto img-fluid" src="{{ asset("images/$acImage") }}" alt="ac" style="height: auto; width: 550px;"> <!-- Add 'mx-auto' class for horizontal centering -->
                <div class="card-body" style="text-align:center">

                    <form id="powerForm" action="{{ route('update-ac', ['id' => 1]) }}" method="post">
                        @csrf
                        <button type="button" class="btn {{ $acState === '1' ? 'btn-success' : 'btn-danger' }} btn-lg rounded-circle" onclick="confirmPowerAction()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                <path d="M7.5 1v7h1V1z"/>
                                <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
                            </svg>
                        </button>
                    </form>

                </div>
            </div>

            {{-- Lights Card --}}
            <div class="card border-0">
                @php
                    $lightsState = App\Models\Unit::find(1)->Lights;
                    $lightsImage = $lightsState === '1' ? 'light-on.png' : 'light-off.png';
                @endphp

                <img class="card-img-top mx-auto img-fluid" src="{{ asset("images/$lightsImage") }}" alt="Lights" style="height: 250px; width: 250px;"> <!-- Add 'mx-auto' class for horizontal centering -->
                <div class="card-body" style="text-align:center">

                    <form id="lightsForm" action="{{ route('update-lights', ['id' => 1]) }}" method="post">
                        @csrf
                        <button type="button" class="btn {{ $lightsState === '1' ? 'btn-success' : 'btn-danger' }} btn-lg rounded-circle" onclick="confirmLightsAction()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                                <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6m3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5"/>
                            </svg>
                        </button>
                    </form>

                </div>
            </div>

        </div><!-- card deck -->

    </div>

</div>


<script>
    function confirmPowerAction() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to change the AC state.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('powerForm').submit();
            }
        });
    }

    function confirmLightsAction() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to change the lights state.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('lightsForm').submit();
            }
        });
    }
</script>
