<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view ('utama.dashboard.dashboard');
    }

    public function agribisnis(){
        return view ('agribisnis.dashboard.dashboard');
    }

    public function biologi(){
        return view ('biologi.dashboard.dashboard');
    }

    public function fisika(){
        return view ('fisika.dashboard.dashboard');
    }

    public function kimia(){
        return view ('kimia.dashboard.dashboard');
    }

    public function matematika(){
        return view ('matematika.dashboard.dashboard');
    }

    public function sistem_informasi(){
        return view ('sistem_informasi.dashboard.dashboard');
    }

    public function teknik_informatika(){
        return view ('teknik_informatika.dashboard.dashboard');
    }

    public function teknik_tambang(){
        return view ('teknik_tambang.dashboard.dashboard');
    }
}
