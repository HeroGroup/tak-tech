<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Privileges;
use App\Enums\UserType;
use App\Models\User;
use App\Models\UserPrivilege;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $userTypes = UserType::forSelect();
        $users = User::all();
        return view('admin.users', compact('userTypes', 'users'));
    }
    
    public function update(Request $request, $id) {
        try {
            $user = User::find($request->id);
            $user->user_type = $request->user_type;
            $user->is_active = $request->is_active && $request->is_active == 'on' ? 1 : 0;
            $user->save();

            return back()->with('message', 'User updated successfully.')->with('type', 'success');
        } catch (\Exeption $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
    
    public function profile() {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }
    
    public function updateProfile(Request $request) {
        try {
            $user = User::find(auth()->user()->id);
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->save();

            return back()->with('message', 'updated successfully')->with('type', 'success');
        } catch (\Exeption $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
    
    public function changePassword() {
        return view('admin.changePassword');
    }
    
    public function updatePassword(Request $request) {
        try {
            $user = User::find(auth()->user()->id);
            
            if (Hash::check($request->current_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = Hash::make($request->password);
                    $user->save();

                    return back()->with('message', 'password updated successfully.')->with('type', 'success');
                } else {
                    return back()->with('message', 'password and confirm password does not match.')->with('type', 'danger');
                }
            } else {
                return back()->with('message', 'current password is incorrect.')->with('type', 'danger');
            }
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
        
    }

    public function impersonate(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        
        Auth::loginUsingId($request->id, $remember = false);

        return redirect()->to(RouteServiceProvider::HOME);
    }

    public function privileges($id)
    {
        try {
            $privileges = array_column(Privileges::cases(), 'name', 'value');
            $user_privileges = UserPrivilege::where('user_id', $id)->pluck('action')->toArray();
            $user = User::find($id);
            
            return view('admin.userPrivileges', compact('user', 'privileges', 'user_privileges'));
        } catch (\Exeption $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function updatePrivileges(Request $request)
    {
        try {
            $user_id = $request->user_id;
            UserPrivilege::where('user_id', $user_id)->delete();
            
            $privileges = $request->privileges ?? [];
            foreach ($privileges as $privilege)
            {
                UserPrivilege::create([
                    'user_id' => $user_id,
                    'action' => $privilege
                ]);
            }
            
            return back()->with('message', "updated successfully!")->with('type', 'success');
        } catch (\Exeption $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
        
    }
}
