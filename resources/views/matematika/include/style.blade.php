<!-- CSS utama -->
<link rel="stylesheet" href="{{ asset('templete/assets/css/main/app.css')}}">
<link rel="stylesheet" href="{{ asset('templete/assets/css/main/app-dark.css')}}">
<link rel="shortcut icon" href="{{ asset('templete/assets/images/logo/favicon.svg')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('templete/assets/images/logo/favicon.png')}}" type="image/png">
<link rel="stylesheet" href="{{ asset('templete/assets/css/shared/iconly.css')}}">

<style>
    /* CSS for the sidebar component */

    .sidebar-bink {
    display: flex; 
    justify-content: space-between; /* Memastikan jarak antara elemen kiri dan kanan */
    align-items: flex-start; /* Menjaga ikon dan teks sejajar di atas */
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
    height: 60px; /* Tinggi tetap untuk menjaga posisi */
}

.sidebar-text {
    display: flex;
    flex-direction: column; /* Menyusun teks dalam kolom */
    justify-content: flex-end; /* Memastikan NIM berada di bawah nama */
    height: 100%; /* Mengisi tinggi dari sidebar-bink */
}

.sidebar-text span {
    font-size: 14px; /* Ukuran font untuk nama */
    font-weight: bold; 
    line-height: 1.2; /* Jarak antar-baris */
    text-transform: capitalize; /* Mengubah huruf pertama setiap kata menjadi kapital */
    margin-bottom: -2px; /* Mengurangi jarak antara nama dan NIM */
}

.sidebar-text span:nth-child(2) {
    font-size: 12px; /* Ukuran font untuk NIM */
    font-weight: normal; /* NIM tidak perlu bold */
    color: #666; /* Warna yang lebih lembut untuk NIM */
    margin-top: auto; /* Mengisi ruang yang tersisa untuk menempatkan NIM di posisi yang benar */
}

.icon-container {
    display: flex; /* Menyusun ikon dalam baris */
    align-items: center; /* Menjaga ikon sejajar di tengah */
    margin-left: 10px; /* Jarak antara NIM dan ikon */
}

</style>

