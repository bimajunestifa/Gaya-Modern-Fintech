<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bankdash') - Sistem Laporan Keuangan & Perbankan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Google Fonts: Inter, Plus Jakarta Sans, & Noto Sans Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                            amber: '#FFBB38',
                            rose: '#FE5C73',
                            purple: '#7F56D9'
                        }
                    },
                    fontFamily: {
                        sans: ['"Inter"', '"Noto Sans Arabic"', 'sans-serif'],
                        jakarta: ['"Plus Jakarta Sans"', '"Noto Sans Arabic"', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(52, 60, 106, 0.05)',
                        'card': '0 10px 30px -5px rgba(45, 96, 255, 0.12)',
                        'hover': '0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    },
                    borderRadius: {
                        '2xl': '20px',
                        '3xl': '25px',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F5F7FA;
            font-family: {{ app()->getLocale() == 'ar' ? "'Noto Sans Arabic', 'Inter', sans-serif" : "'Inter', sans-serif" }};
            color: #343C6A;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex bg-[#F5F7FA]">

    <!-- Mobile Backdrop Overlay -->
    <div id="mobileBackdrop" onclick="toggleMobileSidebar()" class="hidden fixed inset-0 bg-slate-900/40 z-30 md:hidden backdrop-blur-xs transition-opacity"></div>

    <!-- Sidebar -->
    <aside id="mainSidebar" class="no-print w-64 bg-white {{ app()->getLocale() == 'ar' ? 'border-l fixed inset-y-0 right-0 translate-x-full md:translate-x-0' : 'border-r fixed inset-y-0 left-0 -translate-x-full md:translate-x-0' }} border-slate-100 flex flex-col z-40 transition-transform duration-300">
        <!-- Brand / Logo -->
        <div class="h-20 flex items-center px-7 border-b border-slate-50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-blue flex items-center justify-center text-white shadow-md shadow-brand-blue/30">
                    <!-- Bank / Card SVG Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-brand-navy font-jakarta">Bankdash<span class="text-brand-blue">.</span></span>
                    <span class="text-[10px] font-semibold tracking-wider uppercase text-slate-400">{{ __('Core Banking & Report') }}</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebarNav" class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <!-- Laporan Transaksi -->
            <a href="{{ route('transactions.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('transactions.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('transactions.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>{{ __('Laporan Transaksi') }}</span>
            </a>

            <!-- Upload CSV Excel (Fitur Utama) -->
            <a href="{{ route('csv.index') }}" 
               class="flex items-center justify-between px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('csv.*') ? 'bg-emerald-50 text-emerald-600 font-bold shadow-sm ring-1 ring-emerald-200' : 'text-slate-600 hover:bg-emerald-50/50 hover:text-emerald-700' }}">
                <div class="flex items-center gap-4">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>{{ __('Import CSV / Excel') }}</span>
                </div>
                <span class="text-[10px] bg-emerald-600 text-white font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">CSV</span>
            </a>

            <!-- Rekening & Nasabah -->
            <a href="{{ route('accounts.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('accounts.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('accounts.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>{{ __('Rekening & Nasabah') }}</span>
            </a>

            <!-- Pinjaman & Kredit -->
            <a href="{{ route('loans.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('loans.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('loans.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ __('Pinjaman & Kredit') }}</span>
            </a>

            <!-- Investasi & Treasury -->
            <a href="{{ route('investments.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('investments.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('investments.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>{{ __('Investasi & Treasury') }}</span>
            </a>

            <!-- PSAK 71 & COMPLIANCE SECTION -->
            <div class="pt-4 pb-2 flex items-center justify-between px-4">
                <span class="text-[11px] font-bold text-brand-blue uppercase tracking-wider">{{ __('PSAK 71 & IFRS 9') }}</span>
                <span class="text-[9px] bg-blue-100 text-brand-blue font-bold px-1.5 py-0.5 rounded">{{ __('Super Canggih') }}</span>
            </div>

            <!-- PSAK 71: ECL & Staging -->
            <a href="{{ route('psak.dashboard') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('psak.dashboard') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('psak.dashboard') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>{{ __('PSAK 71: ECL & Staging') }}</span>
            </a>

            <!-- Stress Testing Makro -->
            <a href="{{ route('psak.stresstest') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('psak.stresstest') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('psak.stresstest') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>{{ __('Stress Test Makro') }}</span>
            </a>

            <!-- Neraca & Laba Rugi PSAK -->
            <a href="{{ route('psak.reports') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('psak.reports') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('psak.reports') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ __('Neraca & Laba Rugi PSAK') }}</span>
            </a>

            <!-- Batch Excel PSAK 71 -->
            <a href="{{ route('psak.import') }}" 
               class="flex items-center justify-between px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('psak.import') ? 'bg-emerald-50 text-emerald-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-emerald-50/50 hover:text-emerald-700' }}">
                <div class="flex items-center gap-4">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>{{ __('Batch Excel PSAK 71') }}</span>
                </div>
                <span class="text-[9px] bg-emerald-600 text-white font-bold px-1.5 py-0.5 rounded">ECL</span>
            </a>

            <!-- Jurnal Akuntansi PSAK -->
            <a href="{{ route('psak.journals') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('psak.journals') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('psak.journals') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>{{ __('Jurnal Otomatis PSAK') }}</span>
            </a>

            <div class="pt-4 pb-2">
                <span class="px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('CMS & Admin') }}</span>
            </div>

            <!-- CMS & Pengumuman -->
            <a href="{{ route('cms.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('cms.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('cms.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span>{{ __('CMS Pengumuman') }}</span>
            </a>

            <!-- Log Audit & Keamanan -->
            <a href="{{ route('audit.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('audit.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('audit.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>{{ __('Audit & Security Log') }}</span>
            </a>

            <div class="pt-4 pb-2">
                <span class="px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Account & Auth') }}</span>
            </div>

            <!-- Setting -->
            <a href="{{ route('settings.index') }}" 
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-blue-50/80 text-brand-blue font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-brand-blue' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ __('Setting') }}</span>
            </a>

            @auth
                <!-- Logout if logged in -->
                <form action="{{ route('logout') }}" method="POST" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-semibold text-sm text-rose-500 hover:bg-rose-50 transition-all">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>{{ __('Keluar (Logout)') }}</span>
                    </button>
                </form>
            @else
                <!-- Login -->
                <a href="{{ route('login') }}" 
                   class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>{{ __('Login') }}</span>
                </a>

                <!-- Sign-Up -->
                <a href="{{ route('register') }}" 
                   class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span>{{ __('Sign-Up') }}</span>
                </a>

                <!-- Forget-Password -->
                <a href="{{ route('password.request') }}" 
                   class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>{{ __('Forget-Password') }}</span>
                </a>
            @endauth
        </nav>

        <!-- Sidebar Footer Status -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ __('Core Banking Online') }}</p>
                    <p class="text-[10px] text-slate-400 truncate">MySQL • Latency 14ms</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 {{ app()->getLocale() == 'ar' ? 'md:pr-64 pl-0' : 'md:pl-64 pl-0' }}">
        <!-- Top Navbar -->
        <header class="no-print h-20 bg-white border-b border-slate-100 sticky top-0 z-20 flex items-center justify-between px-5 md:px-8 gap-3 md:gap-4">
            <!-- Hamburger & Page Title & Live Bank Clock -->
            <div class="flex items-center gap-3 md:gap-5 min-w-0">
                <!-- Mobile Hamburger Button -->
                <button type="button" onclick="toggleMobileSidebar()" 
                        class="md:hidden w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <h1 class="text-xl md:text-2xl font-extrabold text-brand-navy font-jakarta tracking-tight truncate">@yield('header_title', __('Overview'))</h1>
                
                <!-- Live Banking Clock Widget (Asia/Jakarta / WIB) -->
                <div class="hidden md:flex items-center gap-2.5 px-3.5 py-1.5 rounded-2xl bg-blue-50/80 border border-blue-100 shadow-xs">
                    <div class="relative flex items-center justify-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping absolute"></span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex items-center gap-2">
                        <span id="liveClock" class="font-mono font-bold text-xs text-brand-navy tracking-wider">--:--:-- WIB</span>
                        <span class="text-slate-300">|</span>
                        <span id="liveDate" class="text-[11px] font-semibold text-slate-500">--</span>
                    </div>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-4">
                <!-- Search Bar -->
                <form action="{{ route('transactions.index') }}" method="GET" class="relative hidden xl:block">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="{{ __('Cari transaksi, rekening, nominal...') }}" 
                           class="w-64 pl-11 pr-4 py-2 bg-[#F5F7FA] text-xs text-slate-800 placeholder-brand-muted rounded-full border border-transparent focus:border-brand-blue/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all duration-200">
                </form>

                <!-- Language Selector Dropdown (ID, EN, AR, ZH) -->
                <div class="relative" id="langDropdownContainer">
                    <button type="button" onclick="toggleLangDropdown(event)" 
                            class="h-10 px-3 rounded-full bg-[#F5F7FA] border border-slate-200/80 hover:border-brand-blue/40 flex items-center gap-2 text-xs font-bold text-brand-navy hover:text-brand-blue transition-all shadow-xs">
                        @if(app()->getLocale() == 'id')
                            <span class="text-sm">🇮🇩</span>
                            <span>ID</span>
                        @elseif(app()->getLocale() == 'en')
                            <span class="text-sm">🇬🇧</span>
                            <span>EN</span>
                        @elseif(app()->getLocale() == 'ar')
                            <span class="text-sm">🇸🇦</span>
                            <span>AR</span>
                        @elseif(app()->getLocale() == 'zh')
                            <span class="text-sm">🇨🇳</span>
                            <span>ZH</span>
                        @endif
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Language Dropdown Menu -->
                    <div id="langMenu" class="hidden absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50">
                        <div class="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            {{ __('Bahasa') }} / Language
                        </div>
                        <a href="{{ route('set_locale', 'id') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-brand-blue transition-colors {{ app()->getLocale() == 'id' ? 'bg-blue-50/80 text-brand-blue font-bold' : '' }}">
                            <span class="text-base">🇮🇩</span>
                            <span>Bahasa Indonesia</span>
                        </a>
                        <a href="{{ route('set_locale', 'en') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-brand-blue transition-colors {{ app()->getLocale() == 'en' ? 'bg-blue-50/80 text-brand-blue font-bold' : '' }}">
                            <span class="text-base">🇬🇧</span>
                            <span>English</span>
                        </a>
                        <a href="{{ route('set_locale', 'ar') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-brand-blue transition-colors {{ app()->getLocale() == 'ar' ? 'bg-blue-50/80 text-brand-blue font-bold' : '' }}">
                            <span class="text-base">🇸🇦</span>
                            <span>العربية (Arabic)</span>
                        </a>
                        <a href="{{ route('set_locale', 'zh') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-brand-blue transition-colors {{ app()->getLocale() == 'zh' ? 'bg-blue-50/80 text-brand-blue font-bold' : '' }}">
                            <span class="text-base">🇨🇳</span>
                            <span>中文 (Chinese)</span>
                        </a>
                    </div>
                </div>

                <!-- Settings Icon Button -->
                <a href="{{ route('settings.index') }}" title="{{ __('Setting') }}" class="w-10 h-10 rounded-full bg-[#F5F7FA] flex items-center justify-center text-brand-muted hover:text-brand-blue hover:bg-blue-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>

                <!-- Notification Bell Button -->
                <div class="relative" id="notificationContainer">
                    <button type="button" 
                            id="notificationBtn" 
                            onclick="toggleNotificationDropdown(event)" 
                            title="{{ __('Notifications') }}" 
                            class="w-10 h-10 rounded-full bg-[#F5F7FA] flex items-center justify-center text-brand-rose hover:bg-rose-50 transition-colors focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>
                    <span id="notificationBadge" class="absolute top-1 right-1 w-2.5 h-2.5 bg-brand-rose rounded-full ring-2 ring-white animate-pulse"></span>

                    <!-- Notification Dropdown Menu -->
                    <div id="notificationMenu" class="hidden absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 py-3 z-50 transform transition-all">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h4 class="text-xs font-bold text-brand-navy uppercase tracking-wider">{{ __('Notifications') }}</h4>
                                <span id="notificationCountBadge" class="px-2 py-0.5 text-[10px] font-extrabold bg-rose-100 text-brand-rose rounded-full">3 {{ __('Baru') }}</span>
                            </div>
                            <button type="button" onclick="markAllNotificationsAsRead()" class="text-[11px] font-semibold text-brand-blue hover:underline">
                                {{ __('Tandai dibaca') }}
                            </button>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-50" id="notificationList">
                            <div class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3 notification-item">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">BI-FAST Real-time Settlement</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Jaringan transfer dana instan 24/7 aktif & terhubung ke core banking ledger.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 inline-block">{{ __('Baru saja') }}</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3 notification-item">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ __('Kepatuhan PSAK 71 / IFRS 9') }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Parameter CKPN Stage 1, 2, dan 3 telah disinkronkan dengan data makroekonomi BI.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 inline-block">15 {{ __('menit lalu') }}</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3 notification-item">
                                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ __('Audit Keamanan & Ledger') }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Integritas checksum database MySQL stabil dengan enkripsi SHA-256 aktif.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 inline-block">1 {{ __('jam lalu') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pt-2.5 pb-1 border-t border-slate-100 text-center">
                            <a href="{{ route('audit.index') }}" class="text-xs font-bold text-brand-blue hover:text-blue-700 transition-colors flex items-center justify-center gap-1.5">
                                <span>{{ __('Lihat Log Audit Lengkap') }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile with Corporate Initials Avatar Badge -->
                <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 pl-1 group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brand-blue to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center shadow-md shadow-brand-blue/20 ring-2 ring-blue-100 group-hover:ring-brand-blue transition-all flex-shrink-0">
                        {{ strtoupper(substr(Auth::check() ? Auth::user()->name : 'Eddy Cusuma', 0, 2)) }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-bold text-brand-navy leading-tight group-hover:text-brand-blue transition-colors">
                            {{ Auth::check() ? Auth::user()->name : 'Eddy Cusuma' }}
                        </p>
                        <p class="text-[10px] font-medium text-brand-muted">
                            {{ Auth::check() ? Auth::user()->email : __('Senior Branch Manager') }}
                        </p>
                    </div>
                </a>
            </div>
        </header>

        <!-- Flash Alerts -->
        <main class="flex-1 p-8">
            @if(session('success'))
                <div class="no-print mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="no-print mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Main Injected Content -->
            @yield('content')
        </main>
    </div>
    <script>
        // Live Real-Time Bank Clock (WIB - Asia/Jakarta UTC+7)
        function updateLiveBankClock() {
            const now = new Date();
            const locale = '{{ app()->getLocale() }}';
            
            // Format time in Asia/Jakarta (24h)
            const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            
            // Format date according to active locale
            let dateLocale = 'id-ID';
            if (locale === 'en') dateLocale = 'en-US';
            else if (locale === 'ar') dateLocale = 'ar-SA';
            else if (locale === 'zh') dateLocale = 'zh-CN';

            const dateFormatter = new Intl.DateTimeFormat(dateLocale, {
                timeZone: 'Asia/Jakarta',
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });

            const clockEl = document.getElementById('liveClock');
            const dateEl = document.getElementById('liveDate');
            if (clockEl) clockEl.textContent = timeFormatter.format(now) + ' WIB';
            if (dateEl) dateEl.textContent = dateFormatter.format(now);
        }
        setInterval(updateLiveBankClock, 1000);
        updateLiveBankClock();

        // Language Dropdown Toggle
        function toggleLangDropdown(event) {
            if (event) event.stopPropagation();
            const notifMenu = document.getElementById('notificationMenu');
            if (notifMenu) notifMenu.classList.add('hidden');
            const menu = document.getElementById('langMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        // Notification Dropdown Toggle
        function toggleNotificationDropdown(event) {
            if (event) event.stopPropagation();
            const langMenu = document.getElementById('langMenu');
            if (langMenu) langMenu.classList.add('hidden');
            const notifMenu = document.getElementById('notificationMenu');
            if (notifMenu) notifMenu.classList.toggle('hidden');
        }

        // Mark Notifications As Read
        function markAllNotificationsAsRead() {
            const badge = document.getElementById('notificationBadge');
            const countBadge = document.getElementById('notificationCountBadge');
            if (badge) badge.classList.add('hidden');
            if (countBadge) {
                countBadge.textContent = '0 {{ __("Baru") }}';
                countBadge.className = 'px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-400 rounded-full';
            }
            document.querySelectorAll('.notification-item').forEach(el => {
                el.classList.add('opacity-50');
            });
        }

        // Global Outside-Click Handler to close dropdowns
        document.addEventListener('click', function(event) {
            const langContainer = document.getElementById('langDropdownContainer');
            const langMenu = document.getElementById('langMenu');
            if (langContainer && !langContainer.contains(event.target)) {
                if (langMenu) langMenu.classList.add('hidden');
            }

            const notifContainer = document.getElementById('notificationContainer');
            const notifMenu = document.getElementById('notificationMenu');
            if (notifContainer && !notifContainer.contains(event.target)) {
                if (notifMenu) notifMenu.classList.add('hidden');
            }
        });

        // Mobile Sidebar Drawer Toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('mobileBackdrop');
            const isRtl = document.documentElement.dir === 'rtl';

            if (sidebar) {
                if (isRtl) {
                    sidebar.classList.toggle('translate-x-full');
                } else {
                    sidebar.classList.toggle('-translate-x-full');
                }
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        // Sidebar Scroll Position Persistence across navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarNav = document.getElementById('sidebarNav');
            if (sidebarNav) {
                // 1. Restore exact scroll position if saved
                const savedScroll = sessionStorage.getItem('bankdash_sidebar_scroll');
                if (savedScroll !== null) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                }

                // 2. Ensure the active menu item is visible in viewport
                const activeItem = sidebarNav.querySelector('a.bg-blue-50\\/80, a.bg-emerald-50, a.bg-blue-50');
                if (activeItem) {
                    const navRect = sidebarNav.getBoundingClientRect();
                    const itemRect = activeItem.getBoundingClientRect();
                    if (itemRect.top < navRect.top || itemRect.bottom > navRect.bottom) {
                        activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                    }
                }

                // 3. Save scroll position on scroll
                let scrollTimeout;
                sidebarNav.addEventListener('scroll', function() {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function() {
                        sessionStorage.setItem('bankdash_sidebar_scroll', sidebarNav.scrollTop);
                    }, 50);
                });

                // 4. Save scroll position immediately when user clicks any link in sidebar
                sidebarNav.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        sessionStorage.setItem('bankdash_sidebar_scroll', sidebarNav.scrollTop);
                    });
                });
            }
        });

        // Prevent viewing cached authenticated pages after logout via Browser Back (<) button
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

