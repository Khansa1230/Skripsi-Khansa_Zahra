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
            'nim' => ['required', 'string', 'exists:mahasiswa,nim'], // 'exists' memastikan NIM terdaftar di tabel mahasiswa
            'password' => ['required'],
        ]);

        // Cek kredensial dengan Auth
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nim' => 'The provided credentials do not match our records.',
        ])->onlyInput('nim');
    }
        public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
    
}
