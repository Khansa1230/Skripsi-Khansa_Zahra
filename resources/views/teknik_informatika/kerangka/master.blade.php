<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

@include('teknik_informatika.include.style')
</head>

<body>
    <div id="app">
        @include('teknik_informatika.include.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
<div class="page-heading">
    <h3>Profile Statistics</h3>
</div>
            @yield('content')


            @include('teknik_informatika.include.footer')
        </div>
    </div>
    @include('teknik_informatika.include.script')
</body>

</html>
