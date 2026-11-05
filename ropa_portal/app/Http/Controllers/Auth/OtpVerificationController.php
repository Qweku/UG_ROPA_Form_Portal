<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpVerificationController extends Controller
{
    public function showVerificationForm()
    {
        // Check if user has a valid OTP
        $user = Auth::user();
        if ($user) {
            $validOtp = OtpVerification::where('user_id', $user->id)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (! $validOtp) {
                // Generate and send new OTP
                $this->sendOtp($user);
            }
        }

        return view('auth.verify-otp');
    }

    public function sendOtp($user = null)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $otp = (string) random_int(100000, 999999);

        OtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->delete();

        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'email' => $user->email,
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);

        $this->sendOtpEmail($user->email, $otp, $user->firstname ?? $user->name ?? $user->email);

        return true;
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Rate limiting - prevent spam
        $lastOtp = OtpVerification::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(1))
            ->first();

        if ($lastOtp) {
            return response()->json([
                'error' => 'Please wait 1 minute before requesting another OTP',
            ], 429);
        }

        $this->sendOtp($user);

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $throttleKey = 'verify-otp:'.$user->id.'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many verification attempts. Please try again later.',
            ], 429);
        }

        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otpRecord) {
            RateLimiter::hit($throttleKey, 600);

            return response()->json([
                'success' => false,
                'error' => 'OTP has expired or is invalid. Please request a new one.',
            ], 422);
        }

        if (! Hash::check($request->otp, $otpRecord->otp)) {
            RateLimiter::hit($throttleKey, 600);

            return response()->json([
                'success' => false,
                'error' => 'Invalid OTP. Please try again.',
            ], 422);
        }

        RateLimiter::clear($throttleKey);
        $otpRecord->update(['is_used' => true]);
        $request->session()->regenerate();
        session(['otp_verified' => true]);

        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        $redirectRoute = $user->isAdmin()
            ? route('admin.dashboard')
            : route('ropa.index');

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully! Welcome to RoPA Portal.',
            'redirect' => $redirectRoute,
        ]);
    }

    private function sendOtpEmail($email, $otp, $name)
    {
        try {
            Mail::to($email)->send(new OtpMail($otp, $name));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email to '.$email.': '.$e->getMessage());
        }
    }
}
