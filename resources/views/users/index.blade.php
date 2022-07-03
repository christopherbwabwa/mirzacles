<x-layout>
    <x-_message />

    <a href="{{ route('users.archived') }}">View archived users </a>
    @if ($users->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"> ID </th>
                    <th scope="col"> Names </th>
                    <th scope="col"> UserName </th>
                    <th scope="col"> Email </th>
                    <th scope="col"> Join: </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row"> {{ $user->id }} </th>
                        <td> {{ "$user->prefixname " . $user->fullname }} </td>
                        <td> {{ $user->username }} </td>
                        <td> {{ $user->email }} </td>
                        <td> {{ $user->created_at->diffForHumans() }} </td>
                        <td> <a href="{{ route('users.show', $user->username) }}" class="btn btn-primary">Show</a> </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
    @else
        <h1>No records yet.</h1>
    @endif


    <div>
        
        <a href="{{ route('users.create') }}" class="btn btn-outline-secondary">Register & create a user</a>

    </div>

    <p>{{ $users->links('pagination::bootstrap-4') }}</p>

</x-layout>
