<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::paginate(5)
        ]);
    }

    /**
     * Display a listing of the soft deleted resource. 
     * @return \Illuminate\Http\Response
     */
    public function archived()
    {
        $users = User::onlyTrashed()->get();

        return view('users.trashed', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()) {
            return view('auth.register');
        }
        abort(403, 'Please log out first!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

            'user' => $user
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
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
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
        $user = User::onlyTrashed()->findOrFail($id);

        $user->restore();

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
        $user->delete();

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
        $user = User::onlyTrashed()->findOrFail($id);

        $user->forceDelete();

        return redirect()->route('users.archived')->with('success', 'User erased!!');
    }
}
