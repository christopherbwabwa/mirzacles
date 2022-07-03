<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserServices;
use App\Http\Requests\UserRequest;

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
        $this->users->store($request->except('_token', 'password_confirmation'));

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

       $this->users->update($user->id, $request->except('_token', 'password_confirmation'));
       
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
