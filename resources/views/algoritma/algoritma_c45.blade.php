<!-- dshboard -->
@extends('kerangka.master')
@section('tittle' ,'dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<div class="page-content">
    <secti class="row">
    <div class="col-12 col-lg-9">
        <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_agribisnis') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center"> <!-- Tambahkan align-items-center -->
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 text-center"> <!-- Tambahkan text-center -->
                            <h6 class="text-muted font-semibold">Agribisnis</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_biologi') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center"> <!-- Tambahkan align-items-center -->
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 text-center"> <!-- Tambahkan text-center -->
                            <h6 class="text-muted font-semibold">Biologi</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_fisika') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center"> <!-- Tambahkan align-items-center -->
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 text-center"> <!-- Tambahkan text-center -->
                            <h6 class="text-muted font-semibold">Fisika</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_kimia') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center"> <!-- Tambahkan align-items-center -->
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 text-center"> <!-- Tambahkan text-center -->
                            <h6 class="text-muted font-semibold">Kimia</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_matematika') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center"> <!-- Tambahkan align-items-center -->
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7 text-center"> <!-- Tambahkan text-center -->
                            <h6 class="text-muted font-semibold">Matematik</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_sistem_informasi') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Sistem</h6>
                            <h6 class="text-muted font-semibold">Informasi</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

       

        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_teknik_informatika') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Teknik</h6>
                            <h6 class="text-muted font-semibold">Informatik</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('klasifikasi_c45_mahasiswa_teknik_tambang') }}" class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Teknik</h6>
                            <h6 class="text-muted font-semibold">Tambang</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>



           
    </section>
</div>

@endsection