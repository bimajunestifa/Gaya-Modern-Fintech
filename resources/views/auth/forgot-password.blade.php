<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - Bankdash</title>
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
                            navy: '#343C6A',
                            muted: '#718EBF',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F5F7FA] font-sans min-h-screen flex items-center justify-center p-6 text-slate-800">

    <div class="max-w-md w-full space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-brand-blue flex items-center justify-center text-white mx-auto shadow-lg shadow-brand-blue/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-brand-navy font-['Plus_Jakarta_Sans']">Pemulihan Akun</h1>
            <p class="text-xs text-brand-muted">Masukkan email akun perbankan Anda untuk mereset kata sandi</p>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Alamat Email Terdaftar</label>
                    <input type="email" name="email" required placeholder="admin@bankdash.com" 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>

                <button type="submit" class="w-full py-3.5 bg-brand-blue hover:bg-blue-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-brand-blue/30 transition-all active:scale-95">
                    Kirim Tautan Reset Sandi
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Sudah ingat sandi Anda? 
                <a href="{{ route('login') }}" class="font-bold text-brand-blue hover:underline">Kembali ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>

