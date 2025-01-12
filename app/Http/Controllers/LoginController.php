<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\support\facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard'; // Redirect setelah login

    // Menampilkan halaman login
    public function index()
    {
        return view('auth.login'); // Jika belum login, tampilkan halaman login
    }
    public function login(Request $request)
{
    // Validasi input
    $credentials = $request->validate([
        'nim' => ['required', 'string'],
        'password' => ['required'],
    ]);

    // Periksa apakah NIM ada di tabel users
    $nimExists = \DB::table('users')->where('nim', $credentials['nim'])->exists();

    if (!$nimExists) {
        // Jika NIM tidak ada di tabel users
        return back()->withErrors([
            'nim' => 'The provided NIM is not registered.',
        ])->onlyInput('nim');
    }

    // Jika NIM ada, cek kredensial dengan Auth
    if (Auth::attempt($credentials)) {
        // Jika login berhasil
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    // Jika password salah
    return back()->withErrors([
        'password' => 'The provided Password is incorrect.',
    ])->onlyInput('nim');
}


    public function logout(Request $request)
    {
        \Log::info('User logout initiated.');
    
        Auth::logout();
    
        \Log::info('User successfully logged out.');
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        \Log::info('Session invalidated and token regenerated.');
    
        return redirect('/');
    }
    
}
