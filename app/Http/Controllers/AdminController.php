<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verify.role:ADMIN');
    }

    public function listUsers(Request $request)
    {
        return $request->wantsJson()
            ? [
                'roles' => Role::all(),
                'users' => User::with('roles')->get()
            ]
            : view('admin.UserList');
    }

    public function createUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    }

    public function deleteUsers (Request $request)
    {
        $this->validate($request,[
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        foreach ($request->users as $user_id) {
            if ($user_id != Auth::user()->id) {
                $user = User::where('id',$user_id)->first();
                $user->roles()->detach($user->roles()->get());
                $user->delete();
            }
        }

        return [ 'status' => 1 ];
    }

    public function changePassword (Request $request)
    {
        $this->validate($request,[
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'password' => 'required|confirmed|min:8'
        ]);

        foreach ($request->users as $user_id) {
            $user = User::find($user_id);
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return [ 'status' => 1 ];
    }

    public function updateUserRoles (Request $request) {
        $this->validate($request,[
            'users' => 'required|array',
            'users.*.id' => 'exists:users,id',
            'users.*.roles' => 'array',
            'users.*.roles.*' => 'exists:roles,id'
        ]);

        foreach ($request->users as $user) {
            $user = (object) $user;
            $userModel = User::find($user->id);

            // Delete removed roles
            foreach ($userModel->roles()->get() as $role) {
               if (!in_array($role->id,$user->roles)) {
                   $userModel->roles()->detach($role);
               }
            }

            // Save added roles
            foreach ($user->roles as $role_id) {
                $role = Role::find($role_id);
                $userModel->roles()->attach($role);
            }
        }
    }

    public function addRole (Request $request) {
        $this->validate($request, [
            'role' => 'required|string'
        ]);

        $role = Role::firstOrCreate([
            'role' => $request->role
        ]);

        return [ 'roles' => Role::all() ];
    }
}
