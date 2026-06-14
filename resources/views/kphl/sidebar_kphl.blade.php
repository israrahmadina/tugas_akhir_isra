@php
    $user = Auth::user();
@endphp

<aside class="w-56 h-screen bg-[#1C3A2A] text-white flex flex-col justify-between">

    <!-- TOP -->
    <div>
        <!-- Logo -->
        <div class="px-6 pt-7 pb-6">
            <h1 class="text-base font-bold tracking-wide">SIPROK</h1>
            <p class="text-xs text-white/40 uppercase tracking-widest mt-0.5">KPHL Dashboard</p>
        </div>

        <!-- MENU -->
        <nav class="px-3 space-y-1">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
               {{ request()->is('dashboard') ? 'bg-[#3B6D11] text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Jadwal -->
            <a href="{{ route('kphl.jadwal.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
               {{ request()->is('kphl/jadwal*') ? 'bg-[#3B6D11] text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Jadwal</span>
            </a>

            <!-- Validasi -->
            <a href="{{ route('kphl.validasi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors
               {{ request()->is('kphl/validasi*') ? 'bg-[#3B6D11] text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Validasi</span>
            </a>

        </nav>
    </div>

    <!-- BOTTOM -->
    <div class="px-3 pb-6">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/60 hover:bg-white/10 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>