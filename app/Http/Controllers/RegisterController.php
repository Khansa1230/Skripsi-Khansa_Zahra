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
            ],
            'nama' => 'required|string|max:200',
            'nim' => [
                'required',
                'string',
                'unique:users,nim', // Pastikan NIM unik di tabel users
            ],
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
            'email_uin.unique' => 'The Email has already been taken.',
            'nim.unique' => 'The NI has already been taken.',
            'password.regex' => 'The password must include at least one uppercase letter (A-Z), one lowercase letter (a-z), one number (0-9).', 'or The password must contain at least two different letters',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Validasi apakah NIM terdaftar di tabel mahasiswa
        $mahasiswa = DB::table('mahasiswa')->where('nim', $request->nim)->first();

        // Validasi apakah NIM terdaftar di tabel pekerja
        $pekerja = DB::table('pekerja')->where('ni', $request->nim)->first();

        if (!$mahasiswa && !$pekerja) {
            return redirect()->back()->withErrors([
                'nim' => 'The NI is not registered.',
            ])->withInput();
        }

        // Validasi email yang dimasukkan harus sesuai dengan email yang terdaftar di mahasiswa

        if (($mahasiswa && $mahasiswa->email_uin !== $request->email_uin) || ($pekerja && $pekerja->email !== $request->email_uin)) {
            return redirect()->back()->withErrors([
                'email_uin' => 'This email does not belong to the provided NI. Please provide the correct email associated with your NI.',
            ])->withInput();
        }

        // Membuat pengguna baru
        User::create([
            'email_uin' => $request->email_uin,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // Enkripsi password
        ]);

        return redirect()->route('login')->with('success', 'User created successfully, you can now log in.');
    }
   
       
    }





