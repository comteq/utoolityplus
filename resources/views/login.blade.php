 <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 10 Custom Login and Registration - Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</head>
<body>

  <div class="vh-100 custom-body">
    <div class="container-fluid h-custom">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <img src="{{ asset('images/logo-black.png') }}"
            class="img-fluid custom-logo" alt="logo">
        </div>
        <div class="col-md-5 col-lg-6 col-xl-4 offset-xl-1 mx-5">
          @if(Session::has('error'))
          <div class="alert alert-danger" role="alert">
              {{ Session::get('error') }}
          </div>
          @endif
            <form action="{{ route('login') }}" method="POST" class="border-box-form">
                @csrf
            <div class="login-text">Log Into Utoolity+</div>   
            <!-- Email input -->
            <div class="form-outline mb-4">
                <input type="email" name="email" class="form-control-lg form-control" id="email" placeholder="Email Address" required>
            </div>
  
            <!-- Password input -->
            <div class="form-outline mb-4">
                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
            </div>
  
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem; font-weight: bold;">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div
      class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
      <!-- Copyright -->
      <div class="text-white mb-3 mb-md-0">
        © 2023 Utoolity+. All Rights Reserved.
      </div>
      <!-- Copyright -->
    </div>
  </div>





  <style>
    

.custom-body {
  background-color: #F0F2F5;
}
.login-text {
  text-align: center;
  font-size: 1.2rem;
  margin: 10px 10px 20px 10px;
  font-family: Arial, Helvetica, sans-serif;
}

/* Your CSS file */
.border-box-form {
  border: 1px solid #ced4da; /* Border color */
  border-radius: 10px; /* Border radius for rounded corners */
  padding: 20px; /* Adjust padding as needed */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow for floating effect */
  background-color: #FFFFFF;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);

} 

.custom-logo {
  max-width: 500px; /* Adjust as needed */
  height: auto;
}

.h-custom {
  height: calc(100% - 73px);
}
  @media (max-width: 450px) {
  .h-custom {
  height: 100%;
  }
}
  </style>




</body>




