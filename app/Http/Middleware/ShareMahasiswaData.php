<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $mahasiswa = null;

        if ($user) {
            $mahasiswa = DB::table('users')
                ->join('mahasiswa', 'users.nim', '=', 'mahasiswa.nim')
                ->where('users.nim', $user->nim)
                ->select('mahasiswa.nama', 'mahasiswa.nim')
                ->first();
            
            \Log::info('Mahasiswa: ' . json_encode($mahasiswa));
            //dd($mahasiswa); // Hentikan eksekusi dan tampilkan data
        }

        view()->share('mahasiswa', $mahasiswa);

        return $next($request);
    }
}
