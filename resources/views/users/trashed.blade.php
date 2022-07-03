<x-layout>
    <x-_message />
    <h2>
        Deleted users</h2>
    @if ($users->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"> ID </th>
                    <th scope="col"> Names </th>
                    <th scope="col"> UserName </th>
                    <th scope="col"> Email </th>
                    <th scope="col"> Deleted: </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row"> {{ $user->id }} </th>
                        <td> {{ " $user->prefixname $user->firstname $user->lastname" }} </td>
                        <td> {{ $user->username }} </td>
                        <td> {{ $user->email }} </td>
                        <td> {{ $user->created_at->diffForHumans() }} </td>
                        <td>
                            @if ($user->trashed())
                                <form style="display: inline" action="{{ route('users.restore', $user->id) }}"
                                    method="POST">
                                    @csrf

                                    <button class="btn btn-dark" onclick="return confirm('Are you sure ?')">Restore
                                        user</button>
                                </form>

                                <form style="display: inline" action="{{ route('users.delete', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-dark" onclick="return confirm('Are you sure ?')">Delete
                                        user</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>
    @else
        <h1>No records yet.</h1>
    @endif

    <p> {{ $users->links('pagination::bootstrap-4') }} </p>

    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Go Back</a>


</x-layout>
