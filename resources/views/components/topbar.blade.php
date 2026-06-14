<div class="bg-white shadow px-8 py-4 flex justify-between items-center">

    <!-- Judul -->
    <h2 class="text-2xl font-semibold">
        @yield('title')
    </h2>

    <!-- Right Section -->
    <div class="flex items-center gap-6 relative">

        <!-- 🔔 NOTIFIKASI -->
        <button onclick="toggleNotif()" class="relative">
            🔔
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full">
                3
            </span>
        </button>

        <!-- DROPDOWN NOTIF -->
        <div id="notifDropdown" class="hidden absolute right-24 top-10 bg-white shadow-lg rounded-lg w-60 p-3">
            <p class="text-sm font-semibold mb-2">Notifikasi</p>
            <ul class="text-sm space-y-2">
                <li>Data baru masuk</li>
                <li>User ditambahkan</li>
                <li>Update laporan</li>
            </ul>
        </div>

        <!-- 👤 PROFILE -->
        <div class="relative">
            <button onclick="toggleProfile()" class="flex items-center gap-2">
                <img src="https://i.pravatar.cc/40" class="rounded-full">
                <span class="text-sm">{{ Auth::user()->nama }}</span>
            </button>

            <!-- DROPDOWN PROFILE -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-40">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">Logout</button>
                </form>
            </div>
        </div>

    </div>

</div>

<!-- SCRIPT -->
<script>
function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('hidden');
}

function toggleProfile() {
    document.getElementById('profileDropdown').classList.toggle('hidden');
}
</script>