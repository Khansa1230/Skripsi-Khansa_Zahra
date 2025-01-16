<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShareMahasiswaData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
        {
            $user = Auth::user();

            if ($user) {
                $mahasiswa = DB::table('users')
                    ->join('mahasiswa', 'users.nim', '=', 'mahasiswa.nim')
                    ->where('users.nim', $user->nim)
                    ->select('mahasiswa.nama', 'mahasiswa.nim')
                    ->first();

                $pekerja = $user->pekerja;

                \Log::info('Mahasiswa: ' . json_encode($mahasiswa));
                \Log::info('Pekerja: ' . json_encode($pekerja));

                view()->share([
                    'mahasiswa' => $mahasiswa,
                    'pekerja' => $pekerja,
                ]);
            }

            return $next($request);
        }

    }
