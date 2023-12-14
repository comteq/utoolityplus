<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</head>
<body>

<div class="custom-body">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="{{ asset('images/logo-black.png') }}" class="img-fluid custom-logo" alt="logo">
            </div>
            <div class="col-md-9 col-lg-6 col-xl-5  mobile-logo">
              <img src="{{ asset('images/logo-inline.png') }}" class="img-fluid" alt="logo">
          </div>
            <div class="col-md-9 col-lg-6 col-xl-4 offset-xl-1 mx-2 mx-md-5">
                @if(Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                @endif
                <form action="{{ route('login') }}" method="POST" class="border-box-form">
                    @csrf
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Email Address" required>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem; font-weight: bold;">Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="fixed-bottom bg-light text-center p-3">
    <p>&copy; <span id="currentYear"></span> Utoolity+. All rights reserved.</p>
</footer>

<script>
    // Automatically update the copyright year
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<style>
    body {
        background: url('images/bg.png') no-repeat center center fixed;
        background-size: cover;
        padding: 40px;
        margin-bottom: 70px;
    }
    

    .login-text {
        text-align: center;
        font-size: 1.2rem;
        margin: 10px 10px 20px 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .border-box-form {
        border: 1px solid #ced4da; /* Border color */
        border-radius: 10px; /* Border radius for rounded corners */
        padding: 20px; /* Adjust padding as needed */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow for floating effect */
        background-color: #FFFFFF;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }

    .custom-logo {
        max-width: 500px;
        height: auto;
    }

    .mobile-logo {
      display: none;
    }

    /* Hide the logo for screens 769 pixels and below */
@media (max-width: 769px) {
    .custom-logo {
        display: none;
    }

    .mobile-logo {
      display: block;
      margin-bottom: 10%;
    }

    body {
      padding: 10px;
    }
} 

    /* Styles for screens between 769px and 1200px width */
@media (min-width: 769px) and (max-width: 1200px) {
  .row {
    flex-wrap: nowrap;
  }
}

    footer {
        position: fixed;
        width: 100%;
        bottom: 0;
        color: #737373;
    }
</style>

</body>
</html>
