@include('nav')

<div class="container">
    <h2>Edit User</h2>
    @if(session('flash message'))
            <div class="alert alert-success">
                {{ session('flash message') }}
            </div>
        @endif
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" class="form-control" required>
                <option value="{{ \App\Models\User::ROLE_USER }}" {{ $user->role === \App\Models\User::ROLE_USER ? 'selected' : '' }}>User</option>
                <option value="{{ \App\Models\User::ROLE_ADMIN }}" {{ $user->role === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

