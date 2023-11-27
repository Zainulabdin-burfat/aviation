<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-login-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Auth::attempt(['id' => $user->id, 'password' => $request->password])) {
            if ($user->status == 1 && $user->email_verified_at !== null) {
                $request->session()->regenerate();
                Auth::login($user);
                return redirect()->route('dashboard.index');
            } else {
                Auth::logout();
                $errorMessage = '';
                if (!$user->email_verified_at) {
                    $errorMessage = 'Your email is not verified. Please verify or contact the admin.';
                } elseif (!$user->status) {
                    $errorMessage = 'Your account is not active. Please contact the admin.';
                }

                return redirect()->back()->with(["status" => false, "message" => $errorMessage]);
            }
        }

        return redirect()->back()->with(["status" => false, "message" => "Invalid Email/Password"]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginform');
    }

    public function verifyUserEmail($userId)
    {
        $users = User::find($userId);
        $users->email_verified_at = Carbon::now();
        $users->save();
        session()->forget('confirm');
        return redirect('auth/loginform')->with(["status" => true, "message" => "Email Verified"]);
    }

    public function showForgotPasswordForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-forgot-password-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function forgotPasswordLink()
    {
        //
    }
}
