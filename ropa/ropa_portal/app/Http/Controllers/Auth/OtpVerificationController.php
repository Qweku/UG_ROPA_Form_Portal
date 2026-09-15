<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Log to Laravel log file
        Log::info('=========================================');
        Log::info('OTP VERIFICATION - DEMO MODE');
        Log::info('Email: '.$user->email);
        Log::info('OTP Code: '.$otp);
        Log::info('=========================================');

        // Also log to a dedicated OTP file for easy access
        Log::channel('single')->info("OTP for {$user->email}: {$otp}");

        // Store in session
        Session::put('demo_otp', $otp);

        // Delete old unused OTPs
        OtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->delete();

        // Create new OTP
        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'email' => $user->email,
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);

        // Store plain OTP in session temporarily (for testing/development)
        session(['pending_otp' => $otp]);

        // Send OTP via email
        $this->sendOtpEmail($user->email, $otp, $user->firstname ?? $user->name);

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
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpRecord) {
            return response()->json([
                'success' => false,
                'error' => 'OTP has expired or is invalid. Please request a new one.',
            ], 422);
        }

        if (! Hash::check($request->otp, $otpRecord->otp)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid OTP. Please try again.',
            ], 422);
        }

        $otpRecord->update(['is_used' => true]);

        session()->forget('pending_otp');

        session(['otp_verified' => true]);

        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        $redirectRoute = $user->role === 'admin' ? route('admin.dashboard') : route('ropa.index');

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully! Welcome to RoPA Portal.',
            'redirect' => $redirectRoute,
        ]);
    }

    private function sendOtpEmail($email, $otp, $name)
    {
        // You can use Mailtrap, SMTP, or any email service
        // For now, we'll use log for development
        // \Log::info("OTP for {$email}: {$otp}");

        // In production, uncomment and configure mail:
        /*
        Mail::send('emails.otp', ['otp' => $otp, 'name' => $name], function ($message) use ($email) {
            $message->to($email)
                    ->subject('RoPA Portal - Email Verification OTP')
                    ->from('noreply@ug.edu.gh', 'University of Ghana RoPA');
        });
        */
    }
}
