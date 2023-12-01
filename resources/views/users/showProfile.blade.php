@include('nav')

    <div class="container">
        <h2>Your Profile</h2>

        @if(session('success_message'))
            <div class="alert alert-success">
                {{ session('success_message') }}
            </div>
        @endif

        @if($errors->has('update_error'))
            <div class="alert alert-danger">
                {{ $errors->first('update_error') }}
            </div>
        @endif
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep the same):</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <!-- Add other fields you want to display/edit -->

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

