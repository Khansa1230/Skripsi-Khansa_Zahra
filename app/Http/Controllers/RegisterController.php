<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\user;
use Illuminate\Support\Facades\DB;


class RegisterController extends Controller
{
    public function register(){
        return view ('auth.register');
    }
    public function store(Request $request)
{
    $request->validate([
        'email_uin' => [
            'required',
            'string',
            'unique:users,email_uin', // Pastikan email unik di tabel users
            // Hapus validasi 'exists:mahasiswa,email_uin'
        ],
        'nama' => 'required|string|max:200',
        'nim' => [
            'required',
            'string',
            'unique:users,nim', // Pastikan NIM unik di tabel users
            // Hapus validasi 'exists:mahasiswa,nim'
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[A-Z]/', // Setidaknya satu huruf kapital
            'regex:/[a-z]/', // Setidaknya satu huruf kecil
            'regex:/[0-9]/', // Setidaknya satu angka
            'confirmed', // Memeriksa password dan password_confirmation
        ],
    ], [
        'email_uin.unique' => 'The Email has already been taken.', // Pesan error jika email sudah digunakan di tabel users
        'nim.unique' => 'The NIM has already been taken.', // Pesan error jika NIM sudah digunakan di tabel users
        'password.regex' => 'The password must include at least one uppercase letter (A-Z), one lowercase letter (a-z), and one number (0-9).',
        'password.confirmed' => 'The password confirmation does not match.',
    ]);

    // Validasi kombinasi NIM dan email
    $userWithDifferentEmail = DB::table('users')
        ->where('nim', $request->nim)
        ->where('email_uin', '<>', $request->email_uin)
        ->first();

    if ($userWithDifferentEmail) {
        return redirect()->back()->withErrors([
            'email_uin' => 'You cannot use this email with the provided NIM. Please use the registered email associated with this NIM.',
        ])->withInput();
    }

    // Validasi email milik sendiri
    $nimAssociatedEmail = DB::table('mahasiswa')
        ->where('nim', $request->nim)
        ->value('email_uin'); // Dapatkan email yang terkait dengan NIM

    if ($nimAssociatedEmail && $nimAssociatedEmail !== $request->email_uin) {
        return redirect()->back()->withErrors([
            'email_uin' => 'This email does not belong to the provided NIM. Please provide the correct email associated with your NIM.',
        ])->withInput();
    }

    // Membuat pengguna baru
    User::create([
        'email_uin' => $request->email_uin,
        'nim' => $request->nim,
        'nama' => $request->nama,
        'password' => bcrypt($request->password) // Enkripsi password
    ]);

    return redirect()->route('login')->with('success', 'User created successfully, you can now log in.');
}



}

