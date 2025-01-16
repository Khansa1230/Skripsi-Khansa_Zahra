<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

       
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'agribisnis':
                        return redirect()->route('dashboard_agribisnis');
                    case 'biologi':
                        return redirect()->route('dashboard_biologi');
                    case 'fisika':
                        return redirect()->route('dashboard_fisika');
                    case 'kimia':
                        return redirect()->route('dashboard_kimia');
                    case 'matematika':
                        return redirect()->route('dashboard_matematika');
                    case 'sistem_informasi':
                        return redirect()->route('dashboard_sistem_informasi');
                    case 'teknik_informatika':
                        return redirect()->route('dashboard_teknik_informatika');
                    case 'teknik_tambang':
                        return redirect()->route('dashboard_teknik_tambang');
                    case 'penanggung_jawab':
                        return redirect()->route('dashboard');
                    
                }
            }
        }
        
        return $next($request);
    }        
}