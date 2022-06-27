<x-layout>
    <div class="container">
        <div class="main-body">

            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ $user->photo }} " alt="Photo" class="rounded-circle" width="150"
                                    height="150">
                                <div class="mt-3">
                                    <h4>{{ $user->firstname . ' ' . $user->lastname }}</h4>
                                    <p class="text-secondary mb-1">Back End Developer</p>
                                    <p class="text-muted font-size-sm">Dordrecht, Netherland</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->fullname }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    (239) 816-9029

                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Dordrecht, Netherland
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info " target="__blank"
                                        href="{{ route('users.edit', $user->username) }}">Edit</a>

                                    <form style="display: inline" action="{{ route('users.destroy', $user) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure ?')">Delete
                                            user</button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <x-_message />

            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Go Back</a>

        </div>
    </div>
</x-layout>
