<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice()
    {
        return redirect()->route('home')->with('warning', 'Mohon untuk melakukan verifikasi email terlebih dahulu.');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Akun Berhasil di verifikasi, Selamat datang di Laundry-In');
    }
}
