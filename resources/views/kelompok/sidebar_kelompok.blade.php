@php
    $user = auth()->user();
@endphp

<aside class="w-64 min-h-screen bg-[#1F3D2B] text-white flex flex-col justify-between shadow-lg">

    <!-- TOP -->
    <div>
        <!-- LOGO -->
        <div class="p-6 border-b border-white/10">
            <h1 class="text-xl font-semibold tracking-wide">SIPROK</h1>
            <p class="text-xs text-white/50">Kelompok Binaan</p>
        </div>

        <!-- MENU -->
        <nav class="px-4 py-6 space-y-2 text-sm">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('dashboard') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 0h8" />
                </svg>

                <span>Dashboard</span>
            </a>

            <!-- PRODUK -->
<a href="{{ route('kelompok.produk') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-lg transition
   {{ request()->routeIs('kelompok.produk') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">

    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" />
    </svg>

    <span>Produk</span>
</a>

            <!-- PELAPORAN -->
            <a href="{{ route('pelaporan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('pelaporan.*') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6h13M9 17l-4-4m4 4l-4 4" />
                </svg>

                <span>Pelaporan</span>
            </a>

            <!-- NOTIFIKASI -->
            <a href="{{ route('kelompok.notifikasi') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition
               {{ request()->routeIs('kelompok.notifikasi*') ? 'bg-[#4F7942]' : 'hover:bg-white/10' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span>Notifikasi</span>
            </a>
        </nav>
    </div>

    <!-- BOTTOM -->
    <div class="p-4 border-t border-white/10 text-center">

        <!-- USER -->
        @if($user)
            <div class="mb-3">
                <p class="text-sm font-medium">{{ $user->nama }}</p>
                <p class="text-xs text-white/50">{{ $user->role->role_name }}</p>
            </div>
        @endif

        <!-- LOGOUT -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="text-sm text-white hover:text-gray-300 transition">
                Keluar
            </button>
        </form>

    </div>

</aside>