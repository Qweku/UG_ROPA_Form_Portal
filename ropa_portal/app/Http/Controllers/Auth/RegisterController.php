<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'personnel_id' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'personnel_id' => $validated['personnel_id'] ?? null,
            'is_verified' => false,
        ]);

        Auth::login($user);

        // Send OTP
        $otpController = new OtpVerificationController();
        $otpController->sendOtp($user);

        return redirect()->route('verify.otp');
    }
}
