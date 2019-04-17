<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\Users\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.users.index', [
            'users' => auth()->user()->tenant->users()
                                             ->with([
                                                 'roles',
                                             ])
                                             ->orderBy('deactivated_at', 'asc')
                                             ->orderBy('last_name', 'asc')
                                             ->paginate(25),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = auth()->user()->tenant->users()->create(
            $request->only([
                'first_name',
                'last_name',
                'username',
                'email',
                'password',
                'active',
            ])
        );

        $user->roles()->attach(
            $request->only('role_id')
        );

        flash('User Created!')->success();

        return redirect()->route('settings.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('settings.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User              $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update(
            $request->only([
                'first_name',
                'last_name',
                'username',
                'email',
                'password',
                'active',
            ])
        );

        $user->roles()->sync(
            $request->only('role_id')
        );

        flash('User Updated!')->success();

        return redirect()->route('settings.users.index');
    }
}
