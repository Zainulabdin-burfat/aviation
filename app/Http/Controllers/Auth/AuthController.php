<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function forgotPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {

            DB::beginTransaction();

            $token = Str::random(60);

            DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->input('email'),
                'token' => $token,
                'created_at' => now(),
            ]);

            $user = User::where('email', $request->input('email'))->first();

            $resetUrl = route('reset.token', ['token' => $token]);

            $message = 'You are receiving this email because we received a password reset request for your account. <br>
                 Reset your password by clicking the following link: <a href="' . $resetUrl . '">Click here</a> <br>
                 If you did not request a password reset, no further action is required.';

            $user_details = [
                'name' => $user->first_name,
                'subject' => "Password Reset",
                'email' => $user->email,
                'message' => $message,
            ];

            Mail::send(new GeneralMail($user_details));

            DB::commit();
            return redirect()->back()->with(['message' => 'Reset link sent to your email.', 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return redirect()->back()->withErrors(['email' => 'Failed to send reset email. Please try again.', 'status' => false]);
        }
    }

    public function forgotPassword($token)
    {
        $resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$resetToken) {
            abort(419);
            return redirect()->back()->withErrors(['token' => 'Invalid reset token.', 'status' => true]);
        }

        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-reset-password-cover', ['email' => $resetToken->email, 'token' => $token, 'pageConfigs' => $pageConfigs]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8',
            'confirm-password' => 'required|same:password',
        ]);

        try {

            DB::beginTransaction();

            $resetToken = DB::table('password_reset_tokens')->where('token', $request->token)->first();

            if (!$resetToken || $resetToken->email !== $request->email) {
                return redirect()->back()->withErrors(['token' => 'Invalid reset token or email.']);
            }

            $user = User::where('email', $request->email)->first();

            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('token', $request->token)->delete();

            DB::commit();

            return redirect()->route('loginform')->with(['message' => 'Password reset successfully. You can now log in with your new password.', 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return redirect()->back()->withErrors(['message' => 'Error!, something went wrong.']);
        }
    }
}
