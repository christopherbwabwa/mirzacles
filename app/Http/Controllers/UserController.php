<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserServices;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserServices $users)
    {
        $this->users = $users;
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->users->list();

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Display a listing of the soft deleted resource. 
     * @return \Illuminate\Http\Response
     */
    public function archived()
    {
        $users = $this->users->listTrashed();

        return view('users.trashed', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $attributes = $request->validate([
            'username' => [
                'string',
                'required',
                'min:3',
                'max:255',
                Rule::unique('users')
            ],

            'photo' => ['image'],
            'prefixname' => ['required', Rule::in(['Mr', 'Mrs', 'Ms'])],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')
            ],

            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        if (request('photo')) {

            $attributes['photo'] = Storage::putFile('photos', $request->file('photo'));
        }

        $attributes['password'] = bcrypt($attributes['password']);

        User::create($attributes);

        return redirect()->route('users.index')->with('success', 'Your profile has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return view('users.show', [

            'user' => $this->users->find($user->id)
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $this->users->find($user->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {

        $attributes = $request->validate([
            'username' => [
                'string',
                'required',
                'min:3',
                'max:255',
                Rule::unique('users')->ignore($user)
            ],

            'photo' => ['image'],
            'prefixname' => ['required', Rule::in(['Mr', 'Mrs', 'Ms'])],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user)
            ],

            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        if (request('photo')) {
            $attributes['photo'] = Storage::putFile('photos', $request->file('photo'));
        }

        $attributes['password'] = bcrypt($attributes['password']);

        $user->update($attributes);

        return redirect()->route('users.show', $user)->with('success', 'Your profile has been updated');
    }

    /**
     * Restore a soft deleted user.
     *
     * @return \Illuminate\Http\Response
     */

    public function restore($id)
    {
       $this->users->restore($id);

        return back();
    }
    /**
     * Soft delete the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
       $this->users->destroy($user);

        return redirect()->route('users.index')->with('success', 'The profile was delete');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */

    public function forceDelete(int $id)
    {
        $this->users->delete($id);

        return redirect()->route('users.archived')->with('success', 'User erased!!');
    }
}
