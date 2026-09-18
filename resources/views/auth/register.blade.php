<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Registrasi - Bankdash</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#2D60FF',
                            indigo: '#1814F3',
                            navy: '#343C6A',
                            muted: '#718EBF',
                            lightBg: '#F5F7FA',
                            teal: '#16DBCC',
                        }
                    },
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F5F7FA] font-sans min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 relative overflow-x-hidden">

    <!-- Background Shapes -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-blue-400/15 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="fixed bottom-0 right-10 w-96 h-96 bg-sky-400/15 rounded-full blur-3xl pointer-events-none translate-x-1/4 translate-y-1/3"></div>

    <!-- Back Arrow Button -->
    <a href="{{ route('login') }}" 
       class="absolute top-6 left-6 md:top-10 md:left-10 w-12 h-12 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-brand-navy hover:text-brand-blue hover:shadow-lg transition-all z-20"
       title="Kembali ke Login">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </a>

    <div class="max-w-md w-full relative z-10 space-y-6">
        <!-- Logo -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-brand-blue flex items-center justify-center text-white mx-auto shadow-lg shadow-brand-blue/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-brand-navy font-jakarta">Bankdash<span class="text-brand-blue">.</span></h1>
            <p class="text-xs text-brand-muted">Pendaftaran Akun Baru Petugas / Nasabah Bank</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Eddy Cusuma" 
                           class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" 
                           class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                           class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Konfirmasi kata sandi" 
                           class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-brand-blue hover:bg-blue-700 text-white font-bold text-sm rounded-full shadow-lg shadow-brand-blue/30 transition-all active:scale-95 font-jakarta">
                        Daftar Akun Baru
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-brand-blue hover:underline">Masuk Sekarang (Login)</a>
            </div>
        </div>
    </div>
</body>
</html>
