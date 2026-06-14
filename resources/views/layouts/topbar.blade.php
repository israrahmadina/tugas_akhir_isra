@php
    $user = auth()->user();
    $notifikasis = $user->notifikasis()->latest()->limit(10)->get();
    $unreadCount = $notifikasis->where('is_read', false)->count();
@endphp

<div class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-gray-100 px-8 py-4 flex justify-between items-center">
    
    <!-- LEFT: PAGE TITLE -->
    <h2 class="text-xl font-bold text-[#1A3636]">
        @yield('title')
    </h2>

    <!-- RIGHT: NOTIFICATIONS & PROFILE -->
    <div class="flex items-center gap-6">
        
        <!-- NOTIFICATIONS DROPDOWN -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            
            <!-- Bell Button -->
            <button @click="open = !open" class="relative p-2 rounded-2xl hover:bg-gray-50 transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500 group-hover:text-[#4F6F52] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                
                @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-black min-w-[18px] h-[18px] flex items-center justify-center rounded-full border-2 border-white leading-none px-1 animate-pulse">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Dropdown Panel -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-[-8px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-[-8px]"
                 x-cloak
                 class="absolute right-0 top-[calc(100%+12px)] w-96 bg-white rounded-[2rem] shadow-2xl shadow-black/10 border border-gray-100 overflow-hidden z-50">
                
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-50">
                    <div>
                        <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-wider">Notifikasi</h3>
                        @if($unreadCount > 0)
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">{{ $unreadCount }} belum dibaca</p>
                        @else
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">Semua sudah dibaca</p>
                        @endif
                    </div>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifikasi.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] font-black text-[#4F6F52] hover:text-[#1A3636] uppercase tracking-widest transition-colors">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Notifications List -->
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                    @forelse($notifikasis as $notif)
                        <div class="flex gap-4 px-6 py-4 hover:bg-gray-50/70 transition-all duration-200 cursor-pointer notif-item {{ $notif->is_read ? 'opacity-60' : '' }}"
                             data-id="{{ $notif->notifikasi_id }}"
                             onclick="markAsRead('{{ $notif->notifikasi_id }}', this)">
                            
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-8 h-8 rounded-xl {{ $notif->is_read ? 'bg-gray-100' : 'bg-[#4F6F52]/10' }} flex items-center justify-center">
                                    @if($notif->is_read)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6Z"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#4F6F52]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-{{ $notif->is_read ? 'medium' : 'bold' }} text-gray-700 leading-relaxed">
                                    {{ $notif->pesan }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-wider">
                                    {{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                                </p>
                            </div>

                            <!-- Unread dot -->
                            @if(!$notif->is_read)
                                <div class="flex-shrink-0 mt-1.5">
                                    <div class="w-2 h-2 rounded-full bg-[#4F6F52]"></div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 gap-3">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            </div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                @if($notifikasis->isNotEmpty())
                    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                        <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">Menampilkan 10 notifikasi terbaru</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- PROFILE -->
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 pl-6 border-l border-gray-100 group hover:opacity-80 transition-opacity">
            <div class="text-right">
                <p class="text-sm font-bold text-gray-800 group-hover:text-[#4F6F52] transition-colors">{{ $user->nama }}</p>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">{{ $user->role->role_name }}</p>
            </div>

            @if($user->foto_profil)
                <img src="{{ asset('storage/' . $user->foto_profil) }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-[#4F6F52]/30 shadow-sm group-hover:border-[#4F6F52] transition-all">
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#4F6F52] to-[#1A3636] text-white flex items-center justify-center font-bold text-sm shadow-sm group-hover:shadow-md transition-all">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
            @endif
        </a>

    </div>
</div>

<script>
function markAsRead(notifId, el) {
    if (el.dataset.read === 'true') return;
    
    fetch(`/notifikasi/${notifId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(res => {
        if (res.ok) {
            el.dataset.read = 'true';
            el.classList.add('opacity-60');
            
            // Update visual - remove green dot
            const dot = el.querySelector('[class*="bg-\\\\[\\\\#4F6F52\\\\]"]');
            if (dot) dot.remove();
            
            // Update badge count
            const badgeEl = document.querySelector('[class*="animate-pulse"]');
            if (badgeEl) {
                const currentText = badgeEl.textContent.trim();
                const current = currentText === '9+' ? 10 : parseInt(currentText) || 0;
                
                if (current <= 1) {
                    badgeEl.remove();
                } else {
                    badgeEl.textContent = (current - 1) > 9 ? '9+' : (current - 1);
                }
            }
        }
    }).catch(err => console.error('Error marking notification as read:', err));
}

// Reload notifikasi setiap 30 detik
setInterval(() => {
    const notifDropdown = document.querySelector('[class*="relative"]');
    if (notifDropdown && notifDropdown.querySelector('[class*="open"]')) {
        location.reload(); // Reload jika dropdown terbuka
    }
}, 30000);
</script>
