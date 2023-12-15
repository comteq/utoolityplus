<div class="wrapper">

    @include('nav')

    <div class="container  border-box-form mt-5">
    
        <h2>Create User</h2>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password:</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control" required>
                    <option value="{{ \App\Models\User::ROLE_USER }}">User</option>
                    <option value="{{ \App\Models\User::ROLE_ADMIN }}">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        </div>
    
</div>

@include('footer')
    <style>
        .border-box-form {
        border: 1px solid #ced4da; /* Border color */
        border-radius: 10px; /* Border radius for rounded corners */
        padding: 20px; /* Adjust padding as needed */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow for floating effect */
        background-color: #FFFFFF;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px
    }

    body, html {
            height: 100%;
            margin: 0;
            background-color: #ecedee;
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