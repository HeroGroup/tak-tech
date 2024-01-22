<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserType;
use App\Mail\RecoverPassword;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

require_once __DIR__.'/../../Helpers/utils.php';

class AuthController extends Controller
{
    public function getLogin(Request $request) {
        return $this->checkLoggedInUser($request, 'auth.login');
    }

    public function getRegister(Request $request) {
        return $this->checkLoggedInUser($request, 'auth.register');
    }
    
    public function getForgotPassword(Request $request) {
        return $this->checkLoggedInUser($request, 'auth.forgotPassword');
    }

    public function checkLoggedInUser(Request $request, $view) {
        if ($request->user()) {
            if (in_array($request->user()->userType, [UserType::SUPERADMIN->value, UserType::ADMIN->value])) {
                return redirect()->to(RouteServiceProvider::ADMIN_HOME);
            } 

            return redirect()->to(RouteServiceProvider::HOME);
        }

        return view($view);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt([...$credentials, 'is_active' => 1], $request->remember == "on" ? true : false)) {
            $request->session()->regenerate();
 
            $userType = Auth::user()->user_type;
            if (in_array($userType, [UserType::SUPERADMIN->value, UserType::ADMIN->value])) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            } 

            return redirect()->intended(RouteServiceProvider::HOME);
        }
 
        return back()->withErrors([
            'email' => 'آدرس ایمیل یا رمز عبور نادرست است.',
        ])->onlyInput('email');
    }

    public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'/*, Rules\Password::defaults()*/],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function forgotPassword(Request $request) {
        $user = User::where('email', $request->email)->first();
        // check if user has registered with google or apple or email
        if ($user && $user->password) {
            // create temp password
            $tempPassword = rand_string(12);
            
            // update user passwrod
            $user->password = Hash::make($tempPassword);
            $user->save();
            
            // send corresponding email
            Mail::to($user->email)->send(new RecoverPassword($tempPassword));
            return view('auth.passwordRecovered');
        } else if ($user && $user->google_id) {
            $provider = 'google';
            return view('auth.passwordRecovered', compact('provider'));
        } else if ($user && $user->apple_id) {
            $provider = 'apple';
            return view('auth.passwordRecovered', compact('provider'));
        }


        return back()->withErrors([
            'email' => 'آدرس ایمیل در سیستم موجود نیست.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }

    public function redirect($provider) {
        switch ($provider) {
            case 'google':
                return Socialite::driver('google')->redirect();
                break;
            case 'apple':
                #code... 
                break;
            default:
                # code...
                break;
        }
    }

    public function callback($provider) {
        switch ($provider) {
            case 'google':
                $googleUser = Socialite::driver('google')->user();
                break;
            case 'apple':
                #code ...
                break;
            default:
                # code...
                break;
        }
        
    
        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ]);
     
        Auth::login($user, $remember = true);

        $request->session()->regenerate();
 
        $userType = Auth::user()->user_type;
        if (in_array($userType, [UserType::SUPERADMIN->value, UserType::ADMIN->value])) {
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
