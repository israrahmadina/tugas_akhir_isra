@php
    $user = Auth::user();
@endphp

<aside class="w-64 bg-[#1A3636] text-white flex flex-col justify-between shadow-2xl z-50 self-stretch">
    <div class="overflow-y-auto overflow-x-hidden">
        <!-- Logo -->
        <div class="p-6 mb-4">
            <h1 class="text-2xl font-bold tracking-wider text-[#D6BD98]">SIPDHL</h1>
            <p class="text-[10px] text-white/50 uppercase tracking-[0.2em]">Dashboard Panel</p>
        </div>

        <nav class="px-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('dashboard') ? 'bg-[#4F6F52] text-white shadow-lg' : 'hover:bg-white/5 text-white/70 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <!-- PELAPORAN -->
            <div x-data="{ open: {{ request()->is('pelaporan*') || request()->is('admin/atribut*') ? 'true' : 'false' }} }" class="space-y-1">
                @if(Auth::user()->role->role_name === 'Admin')
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-white/5 text-white/70 hover:text-white transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13M9 17l-4-4m4 4l-4 4"/>
                            </svg>
                            <span class="text-sm font-medium">Pelaporan</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 {{ request()->is('pelaporan*') || request()->is('admin/atribut*') ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div x-show="open" 
                         x-cloak
                         @if(request()->is('pelaporan*') || request()->is('admin/atribut*')) style="display: block;" @endif
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-12 space-y-1">
                        
                        <!-- Daftar Pelaporan -->
                        <a href="{{ route('pelaporan.index') }}" 
                           class="block py-2 text-sm transition-colors {{ request()->is('pelaporan*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                            Daftar Pelaporan
                        </a>

                        <!-- Atribut Pelaporan -->
                        <a href="{{ route('atribut.index') }}" 
                           class="block py-2 text-sm transition-colors {{ request()->is('admin/atribut*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                            Atribut Pelaporan
                        </a>

                        <!-- Kelola Komoditas -->
                        <a href="{{ route('komoditas.index') }}" 
                           class="block py-2 text-sm transition-colors {{ request()->is('admin/komoditas*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                            Kelola Komoditas
                        </a>
                    </div>
                @else
                    <a href="{{ route('pelaporan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('pelaporan*') ? 'bg-[#4F6F52] text-white shadow-lg' : 'hover:bg-white/5 text-white/70 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13M9 17l-4-4m4 4l-4 4"/>
                        </svg>
                        <span class="text-sm font-medium">Pelaporan</span>
                    </a>
                @endif
            </div>

            <!-- User Management Group -->
            <div x-data="{ open: {{ request()->is('admin/*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-white/5 text-white/70 hover:text-white transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="text-sm font-medium">Manajemen User</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 {{ request()->is('admin/*') ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="open" 
                     x-cloak
                     @if(request()->is('admin/*')) style="display: block;" @endif
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="pl-12 space-y-1">
                    
                    <!-- KPHL -->
                    <a href="{{ route('kphl.index') }}" 
                       class="block py-2 text-sm transition-colors {{ request()->is('admin/kphl*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                        KPHL
                    </a>

                    <!-- Penyuluh -->
                    <a href="{{ route('penyuluh.index') }}" 
                       class="block py-2 text-sm transition-colors {{ request()->is('admin/penyuluh*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                        Penyuluh
                    </a>

                    <!-- Kelompok Usaha -->
                    <a href="{{ route('kelompok.index') }}" 
                       class="block py-2 text-sm transition-colors {{ request()->is('admin/kelompok*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                        Kelompok Usaha
                    </a>

                    <!-- Kelola Skema -->
                    <a href="{{ route('skema.index') }}" 
                       class="block py-2 text-sm transition-colors {{ request()->is('admin/skema*') ? 'text-[#D6BD98] font-bold' : 'text-white/60 hover:text-white' }}">
                        Kelola Skema
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Footer / Logout -->
    <div class="p-4 border-t border-white/5">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

@push('scripts')
@endpush