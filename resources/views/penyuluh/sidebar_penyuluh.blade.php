@php
    $user = Auth::user();
@endphp

<aside class="w-64 min-h-screen bg-[#1F3D2B] text-white flex flex-col justify-between shadow-lg">

    <!-- TOP -->
    <div>

        <!-- LOGO -->
        <div class="p-6 border-b border-white/10">
            <h1 class="text-xl font-semibold tracking-wide">SIPROK</h1>
            <p class="text-xs text-white/50">Dashboard Penyuluh</p>
        </div>

        <!-- PROFILE -->
       <!-- PROFILE -->
<a href="{{ route('profile.edit') }}"
   class="px-6 py-5 border-b border-white/10 flex items-center gap-3 hover:bg-white/5 transition duration-200 cursor-pointer">

    <div class="w-10 h-10 overflow-hidden rounded-full border border-white/20">

        @if(optional($user->penyuluh)->foto_profil)
            <img
                src="{{ asset('storage/' . $user->penyuluh->foto_profil) }}"
                class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-white/10 flex items-center justify-center font-bold text-[#4F7942]">
                {{ strtoupper(substr($user->nama, 0, 2)) }}
            </div>
        @endif

    </div>

    <div class="flex-1">
        <p class="text-xs text-white/50">Selamat datang,</p>

        <h2 class="font-semibold text-sm leading-tight">
            {{ $user->nama }}
        </h2>

        <div class="flex items-center justify-between mt-1">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] text-white/40 font-medium">
                    Penyuluh
                </span>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 text-white/40"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </div>

</a>

        <!-- MENU -->
        <nav class="px-4 py-6 space-y-2 text-sm">

            <!-- Dashboard -->
            <a href="{{ route('penyuluh.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('penyuluh.dashboard') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 0h8" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Verifikasi -->
            <a href="{{ route('penyuluh.verifikasi') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('penyuluh.verifikasi') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Verifikasi</span>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('penyuluh.riwayat') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('penyuluh.riwayat') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Riwayat</span>
            </a>

         
        </nav>
    </div>

    <!-- BOTTOM -->
    <div class="p-4 border-t border-white/10">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600/80 hover:bg-red-600 rounded-lg text-white font-medium transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>

    </div>

</aside>