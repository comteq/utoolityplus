@include('nav')

<div class="container">
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
            <label for="role">Role:</label>
            <select name="role" class="form-control" required>
                <option value="{{ \App\Models\User::ROLE_USER }}">User</option>
                <option value="{{ \App\Models\User::ROLE_ADMIN }}">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    </div>
