<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Mail\RecoverPassword;
use App\Models\User;
use App\Models\LoginSession;
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
        $inviteCode = $request->query('invite_code', '0');
        return $this->checkLoggedInUser($request, 'auth.register', $inviteCode);
    }
    
    public function getForgotPassword(Request $request) {
        return $this->checkLoggedInUser($request, 'auth.forgotPassword');
    }

    public function checkLoggedInUser(Request $request, $view, $inviteCode=null) {
        if ($request->user()) {
            if (in_array($request->user()->userType, [UserType::SUPERADMIN->value, UserType::ADMIN->value])) {
                return redirect()->to(RouteServiceProvider::ADMIN_HOME);
            } 

            return redirect()->to(RouteServiceProvider::HOME);
        }

        return view($view, compact('inviteCode'));
    }

    public function login(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();
            if ($user && $user->google_id) {
                // has registered with google
                $provider = 'google';
                return view('auth.passwordRecovered', compact('provider'));
            }
            if ($user && $user->apple_id) {
                // has registered with apple
                $provider = 'apple';
                return view('auth.passwordRecovered', compact('provider'));
            }

     
            if (Auth::attempt([...$credentials, 'is_active' => 1], $request->remember == "on" ? true : false)) {
                
                $this->saveLoginSession($request->ip(), $request->userAgent());
    
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
        } catch (\Exception $exception) {
            return back()->withErrors([
                'email' => $exception->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'/*, Rules\Password::defaults()*/],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'invite_code' => generateInviteCode(),
        ]);

        if ($request->invite_code) {
            $invitee = User::where('invite_code', $request->invite_code)->first();
            if ($invitee) {
                $user->invitee = $invitee->id;
                $user->save();
            }
        }

        Auth::login($user);

        $this->saveLoginSession($request->ip(), $request->userAgent());

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

    public function callback(Request $request, $provider) {
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
            'invite_code' => generateInviteCode(),
        ]);
     
        Auth::login($user, $remember = true);

        $this->saveLoginSession($request->ip(), $request->userAgent());

        $request->session()->regenerate();
 
        $userType = Auth::user()->user_type;
        if (in_array($userType, [UserType::SUPERADMIN->value, UserType::ADMIN->value])) {
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function saveLoginSession($request_ip, $requset_user_agent) 
    {
        // save login session
        $first_pos = strpos($requset_user_agent, '(');
        $second_pos = strpos($requset_user_agent, ';');
        $device = substr($requset_user_agent, $first_pos + 1, $second_pos - $first_pos - 1);
        
        LoginSession::create([
            'user_id' => Auth::user()->id,
            'ip_address' => $request_ip,
            'device' => $device,
        ]);
    }

}
