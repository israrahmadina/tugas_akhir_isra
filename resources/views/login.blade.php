<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Pelaporan</title>
 @vite(['resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-100 to-green-200">

    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden grid md:grid-cols-2 grid-cols-2">

            <!-- Left Side - Form Login -->
            <div class="p-10 md:p-14 flex flex-col justify-center">
                <h2 class="text-4xl font-bold text-[#859F3D] mb-2">
                    Login
                </h2>
                <p class="text-gray-600 mb-8">
    Selamat datang di Sistem Pelaporan Kelompok Binaan
</p>

@if ($errors->any())
    <div class="mb-4 text-red-500">
        {{ $errors->first() }}
    </div>
@endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required placeholder="Masukkan email anda" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#859F3D] focus:border-transparent transition-all outline-none"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

               <div>
    <div class="flex justify-between items-center mb-1">
        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>

        <!-- Tambahan Reset Password -->
       <a href="/forgot-password" class="text-xs text-[#859F3D] hover:underline whitespace-nowrap">
    Lupa Password?
    </a>
    </div>

    <input type="password" name="password" id="password" required placeholder="Masukkan password anda" 
        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#859F3D] focus:border-transparent transition-all outline-none">

    @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-[#859F3D] border-gray-300 rounded focus:ring-[#859F3D]">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit" class="w-full bg-[#859F3D] hover:bg-[#6c8231] text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    Masuk Sekarang
                </button>
            </form>

<!-- Tambahan Registrasi -->
<p class="text-sm text-gray-600 text-center mt-6">
    Belum punya akun?
    <a href="/register" class="text-[#859F3D] font-semibold hover:underline">
        Daftar di sini
    </a>
</p>
                <p class="text-sm text-gray-500 text-center mt-8">
                    © 2026 Sistem Pelaporan
                </p>
            </div>

            <!-- Right Side - Forest Image -->
            <div class="relative bg-green-800">
                <img
                    src="https://images.unsplash.com/photo-1448375240586-882707db888b"
                    alt="Hutan"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-[#859F3D]/40 flex items-center justify-center">
                    <div class="text-center text-white px-8">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Sistem Pelaporan
                        </h1>
                        <p class="text-lg">
                            Monitoring dan pelaporan hasil kelompok binaan
                            secara cepat, rapi, dan terstruktur.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
