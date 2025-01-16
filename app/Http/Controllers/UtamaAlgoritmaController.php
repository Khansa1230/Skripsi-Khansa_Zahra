<?php

namespace App\Http\Controllers;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaAlgoritmaController extends Controller
{
    public function index(){
        return view ('utama.algoritma.algoritma_c45');
    }
    
}
