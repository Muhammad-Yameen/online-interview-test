<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    public UserRepositoryInterface $user;
    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $this->authorize('viewAny',User::class);

        $title = "Users";
        $users =  $this->user->getAllWithOrderCounts();
        return Inertia::render(
            'users/index',
            [
                'users' => $users,
                'title' => $title,
                'singular_title' => Str::singular($title)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create',User::class);

        $input  = $request->all();
        $input['password'] =  bcrypt($request->password);
        $user  =  User::create($input);
        $user->markEmailAsVerified();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update',$user);

        $user->update($request->except(['password', 'password_confirmation']));
        if ($request->__isset('password')) {
            $user->update([
                'password' => bcrypt($request->get('password'))
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete',$user);

        $this->user->delete($user->id);
    }
}
