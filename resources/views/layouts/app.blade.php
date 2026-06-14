<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @vite(['resources/js/app.js'])

   
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.3s ease-out; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        aside {
            width: 256px !important;
            flex-shrink: 0 !important;
            transition: none !important;
        }
    </style>
</head>

<body class="flex bg-gray-100 min-h-screen">

    <!-- SIDEBAR -->
    @php
        $user = auth()->user();
    @endphp

    @if($user)
        @if($user->role->role_name === 'Admin')
            @include('layouts.sidebar')

        @elseif($user->role->role_name === 'KPHL')
            @include('kphl.sidebar_kphl')

        @elseif($user->role->role_name === 'Penyuluh')
            @include('penyuluh.sidebar_penyuluh')

        @elseif($user->role->role_name === 'Kelompok Binaan')

            @include('kelompok.sidebar_kelompok')
        @endif
    @endif


    <!-- MAIN -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- TOPBAR -->
        @include('layouts.topbar')


        <!-- CONTENT -->
        <div class="p-8 flex-1 fade-in">
            @yield('content')
        </div>

    </div>

    @stack('scripts')

</body>
</html>