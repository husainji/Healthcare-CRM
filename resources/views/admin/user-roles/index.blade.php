<x-app-layout>


<div class="container">
    <h1>Manage User Roles</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr><th>Name</th><th>Email</th><th>Roles</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <form method="POST" action="{{ route('admin.users.roles.update', $user) }}">
                    @csrf
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($roles as $role)
                            <label>
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                {{ $role->name }}
                            </label>
                        @endforeach
                    </td>
                    <td><button type="submit">Save</button></td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
