<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Matakuliah;
use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeknikTambang
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

        if (!$user) {
            abort(401, 'Anda belum login.');
        }

        $pekerja = Pekerja::where('ni', $user->nim)->first();

        if ($pekerja) {
            if ($pekerja->role !== 'Dosen' && $pekerja->role !== 'Prodi') {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            $userJurusan = $pekerja->jurusan;
        } else {
            $matakuliah = Matakuliah::where('nim', $user->nim)->first();
            $userJurusan = $matakuliah->jurusan;
        }

        Log::info('Jurusan pengguna: ' . $userJurusan);

        if ($userJurusan !== 'Teknik Pertambangan') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request); // Pastikan return $next($request)
    }
}
