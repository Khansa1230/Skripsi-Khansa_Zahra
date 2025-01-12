<?php

namespace App\Http\Controllers;
Use App\Models\user;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class ForgotController extends Controller
{
    public function index()
    {
        return view('auth.forgot'); // Jika belum login, tampilkan halaman login
    }
    public function store(Request $request)
    {
        $request->validate([
            'email_uin' => 'required|string|exists:users,email_uin', // Pastikan email ada di tabel users
            'password' => [
    'required',
    'string',
    'min:8',
    'regex:/[A-Z]/', // Setidaknya satu huruf kapital
    'regex:/[a-z]/', // Setidaknya satu huruf kecil
    'regex:/[0-9]/', // Setidaknya satu angka
    'regex:/^(?=.*[a-zA-Z].*[a-zA-Z]).*$/',
    'confirmed', // Memeriksa password dan password_confirmation
],
        ], [
            'password.regex' => 'The password must include at least one uppercase letter (A-Z), one lowercase letter (a-z), and one number (0-9).',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.same' => 'The password confirmation does not match the password.', // Pesan error jika password_confirmation tidak sesuai
        ]);

        // Temukan pengguna berdasarkan email
        $user = User::where('email_uin', $request->email_uin)->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email_uin' => 'The provided email is not registered.',
            ])->withInput();
        }

        // Mengubah password pengguna
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password has been reset successfully.');
    }
}
