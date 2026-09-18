<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Bankdash Core Banking</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F7FA;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden flex items-center justify-center p-4 sm:p-6 md:p-10 bg-[#F5F7FA]">

    <!-- Background Organic Shapes (Themed to Bankdash Royal Blue & Soft Cyan) -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-blue-400/15 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="fixed top-0 right-10 w-[30rem] h-[30rem] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
    <div class="fixed bottom-0 right-10 w-96 h-96 bg-sky-400/15 rounded-full blur-3xl pointer-events-none translate-x-1/4 translate-y-1/3"></div>
    <div class="fixed -bottom-20 left-20 w-80 h-80 bg-blue-300/10 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Main Floating Two-Column Card (Matching Template Structure with Bankdash Theme) -->
    <div class="relative z-10 w-full max-w-4xl bg-white rounded-[32px] shadow-2xl border border-slate-100/90 overflow-hidden flex flex-col md:flex-row my-auto">
        
        <!-- Left Column: Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-between">
            <div>
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-brand-blue flex items-center justify-center text-white shadow-md shadow-brand-blue/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-brand-navy font-jakarta">Bankdash<span class="text-brand-blue">.</span></span>
                </div>

                <!-- Headline & Subtitle -->
                <div class="space-y-2 mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy font-jakarta tracking-tight">Welcome back</h2>
                    <p class="text-xs text-brand-muted leading-relaxed max-w-sm">
                        This site is for admin members to report every progress of information that has been obtained
                    </p>
                </div>

                <!-- Flash Alert Messages -->
                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <input type="email" name="email" id="emailField" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="admin@bankdash.com" 
                               class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 focus:border-brand-blue rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                    </div>

                    <!-- Password Input -->
                    <div>
                        <input type="password" name="password" id="passwordField" 
                               value="" 
                               required 
                               placeholder="Password" 
                               class="w-full h-12 px-4 text-xs sm:text-sm text-brand-navy bg-white border border-slate-200 focus:border-brand-blue rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-medium">
                    </div>

                    <!-- Remember Me & Forgot Password Row -->
                    <div class="flex items-center justify-between text-xs pt-1 text-brand-muted font-medium">
                        <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                            <input type="checkbox" name="remember" checked 
                                   class="w-4 h-4 rounded text-brand-blue focus:ring-brand-blue border-slate-300 accent-[#2D60FF]">
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="hover:text-brand-blue transition-colors">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- SIGN IN Button (Matching Template, In Bankdash Royal Blue) -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-44 py-3 bg-brand-blue hover:bg-blue-700 text-white font-extrabold text-xs tracking-wider rounded-full shadow-lg shadow-brand-blue/30 transition-all active:scale-95 uppercase font-jakarta">
                            SIGN IN
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Demo Credentials Pill -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-[11px] text-brand-muted">
                <span>Demo: <strong class="text-brand-navy">admin@bankdash.com</strong> / <strong class="text-brand-navy">admin123</strong></span>
                <button type="button" onclick="fillDemoCredentials()" class="text-brand-blue font-bold hover:underline">
                    Gunakan Akun
                </button>
            </div>
        </div>

        <!-- Right Column: Presentation Card & Financial Illustration -->
        <div class="w-full md:w-1/2 bg-[#F8FAFC] p-8 sm:p-12 flex flex-col items-center justify-center relative border-t md:border-t-0 md:border-l border-slate-100">
            
            <!-- Stacked Card Container -->
            <div class="relative w-full max-w-sm">
                <!-- Stack Shadow Layers -->
                <div class="absolute -bottom-3 inset-x-4 h-6 bg-slate-200/50 rounded-2xl -z-10 shadow-sm"></div>
                <div class="absolute -bottom-1.5 inset-x-2 h-6 bg-slate-100 rounded-2xl -z-10 shadow-sm"></div>

                <!-- Top Floating White Card -->
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center space-y-6">
                    
                    <!-- Magnific Finance Vector SVG Artwork (100% Crisp Vector, No Emojis) -->
                    <div class="w-full relative flex items-center justify-center p-1">
                        <svg viewBox="0 0 380 240" class="w-full h-52 drop-shadow-lg" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <!-- Gradients -->
                                <linearGradient id="cardGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#2D60FF" />
                                    <stop offset="55%" stop-color="#1814F3" />
                                    <stop offset="100%" stop-color="#4F46E5" />
                                </linearGradient>
                                <linearGradient id="cardGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#16DBCC" />
                                    <stop offset="100%" stop-color="#059669" />
                                </linearGradient>
                                <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2D60FF" stop-opacity="0.4"/>
                                    <stop offset="100%" stop-color="#2D60FF" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="coinGold" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FDE047" />
                                    <stop offset="50%" stop-color="#F59E0B" />
                                    <stop offset="100%" stop-color="#D97706" />
                                </linearGradient>
                                <linearGradient id="coinTop" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FEF08A" />
                                    <stop offset="100%" stop-color="#FBBF24" />
                                </linearGradient>
                                <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#10B981" />
                                    <stop offset="100%" stop-color="#047857" />
                                </linearGradient>
                                <filter id="softGlow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="8" result="blur" />
                                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                </filter>
                                <filter id="shadow3d" x="-10%" y="-10%" width="120%" height="130%">
                                    <feDropShadow dx="0" dy="6" stdDeviation="6" flood-color="#1E293B" flood-opacity="0.14"/>
                                </filter>
                            </defs>

                            <!-- Ambient Background Bokeh -->
                            <circle cx="190" cy="110" r="90" fill="#2D60FF" fill-opacity="0.08" filter="url(#softGlow)"/>
                            <circle cx="300" cy="80" r="50" fill="#16DBCC" fill-opacity="0.1" filter="url(#softGlow)"/>

                            <!-- Main Dashboard Screen (Isometric Stand) -->
                            <g filter="url(#shadow3d)">
                                <!-- Screen Stand Base -->
                                <path d="M150 185 L230 185 L240 210 L140 210 Z" fill="#E2E8F0"/>
                                <rect x="130" y="210" width="120" height="8" rx="4" fill="#CBD5E1"/>
                                
                                <!-- Screen Frame -->
                                <rect x="50" y="25" width="280" height="162" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2.5"/>
                                
                                <!-- Screen Top Window Bar -->
                                <rect x="52" y="27" width="276" height="24" rx="14" fill="#F8FAFC"/>
                                <circle cx="70" cy="39" r="3.5" fill="#FE5C73"/>
                                <circle cx="82" cy="39" r="3.5" fill="#FFBB38"/>
                                <circle cx="94" cy="39" r="3.5" fill="#16DBCC"/>
                                
                                <rect x="120" y="35" width="80" height="8" rx="4" fill="#E2E8F0"/>
                                <rect x="265" y="35" width="50" height="8" rx="4" fill="#DBEAFE"/>
                            </g>

                            <!-- Financial Chart Data on Screen -->
                            <g>
                                <!-- Gridlines -->
                                <line x1="70" y1="85" x2="310" y2="85" stroke="#F1F5F9" stroke-width="1.5" stroke-dasharray="4 4"/>
                                <line x1="70" y1="120" x2="310" y2="120" stroke="#F1F5F9" stroke-width="1.5" stroke-dasharray="4 4"/>
                                <line x1="70" y1="155" x2="310" y2="155" stroke="#F1F5F9" stroke-width="1.5"/>

                                <!-- Area Growth Curve -->
                                <path d="M70 150 C105 140, 125 105, 155 115 C185 125, 205 80, 240 90 C270 100, 285 65, 310 60 L310 155 L70 155 Z" fill="url(#chartGrad)"/>
                                <path d="M70 150 C105 140, 125 105, 155 115 C185 125, 205 80, 240 90 C270 100, 285 65, 310 60" stroke="#2D60FF" stroke-width="3.5" stroke-linecap="round"/>

                                <!-- Mini Bar Columns -->
                                <rect x="85" y="125" width="10" height="30" rx="3" fill="#E2E8F0"/>
                                <rect x="105" y="110" width="10" height="45" rx="3" fill="#CBD5E1"/>
                                <rect x="125" y="130" width="10" height="25" rx="3" fill="#E2E8F0"/>
                                
                                <!-- Glowing Key Data Points -->
                                <circle cx="155" cy="115" r="4" fill="#2D60FF" stroke="#FFFFFF" stroke-width="2"/>
                                <circle cx="240" cy="90" r="4" fill="#2D60FF" stroke="#FFFFFF" stroke-width="2"/>
                                <circle cx="310" cy="60" r="5" fill="#10B981" stroke="#FFFFFF" stroke-width="2.5"/>
                            </g>

                            <!-- Floating 3D Bank Cards (Layered in Front) -->
                            <!-- Card 2 (Cyan Teal - Angled Left) -->
                            <g transform="rotate(-12 110 135)" filter="url(#shadow3d)">
                                <rect x="35" y="100" width="115" height="70" rx="10" fill="url(#cardGrad2)"/>
                                <rect x="45" y="115" width="14" height="10" rx="2" fill="#FDE047" opacity="0.9"/>
                                <rect x="45" y="140" width="55" height="4" rx="2" fill="#FFFFFF" opacity="0.8"/>
                                <circle cx="130" cy="150" r="8" fill="#FFFFFF" opacity="0.3"/>
                                <circle cx="138" cy="150" r="8" fill="#FFFFFF" opacity="0.3"/>
                            </g>

                            <!-- Card 1 (Royal Blue Main - Floating Front) -->
                            <g transform="rotate(8 270 145)" filter="url(#shadow3d)">
                                <rect x="205" y="105" width="125" height="76" rx="12" fill="url(#cardGrad)"/>
                                <path d="M205 118 C225 106, 265 106, 330 125 L330 105 L205 105 Z" fill="#FFFFFF" opacity="0.15"/>
                                <!-- Gold Chip -->
                                <rect x="220" y="121" width="16" height="12" rx="3" fill="#FBBF24"/>
                                <path d="M220 127 L236 127 M228 121 L228 133" stroke="#D97706" stroke-width="0.75"/>
                                <!-- Contactless Waves -->
                                <path d="M245 123 C247 125, 247 129, 245 131 M249 121 C252 124, 252 130, 249 133" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" opacity="0.8"/>
                                <!-- Card Number -->
                                <rect x="220" y="147" width="70" height="5" rx="2.5" fill="#FFFFFF" opacity="0.9"/>
                                <!-- Overlapping Mastercard Rings -->
                                <circle cx="305" cy="160" r="9" fill="#FE5C73" opacity="0.9"/>
                                <circle cx="315" cy="160" r="9" fill="#FFBB38" opacity="0.9"/>
                            </g>

                            <!-- Floating Gold Coins Stack (Bottom Right) -->
                            <g filter="url(#shadow3d)">
                                <path d="M290 190 C290 183 320 183 320 190 L320 198 C320 205 290 205 290 198 Z" fill="url(#coinGold)"/>
                                <ellipse cx="305" cy="190" rx="15" ry="5.5" fill="url(#coinTop)"/>
                                <path d="M290 182 C290 175 320 175 320 182 L320 190 C320 197 290 197 290 190 Z" fill="url(#coinGold)"/>
                                <ellipse cx="305" cy="182" rx="15" ry="5.5" fill="url(#coinTop)"/>
                                <path d="M290 174 C290 167 320 167 320 174 L320 182 C320 189 290 189 290 182 Z" fill="url(#coinGold)"/>
                                <ellipse cx="305" cy="174" rx="15" ry="5.5" fill="url(#coinTop)"/>
                                <circle cx="305" cy="174" r="3" fill="#D97706" opacity="0.5"/>
                            </g>

                            <!-- Floating Verified Security Badge (Top Left) -->
                            <g filter="url(#shadow3d)">
                                <rect x="28" y="18" width="95" height="30" rx="15" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                                <circle cx="43" cy="33" r="9" fill="url(#shieldGrad)"/>
                                <path d="M39 33 L42 36 L47 30" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <text x="57" y="31" font-family="'Plus Jakarta Sans', sans-serif" font-size="8" font-weight="800" fill="#1E293B">SECURE CORE</text>
                                <text x="57" y="40" font-family="'Inter', sans-serif" font-size="7" font-weight="700" fill="#10B981">256-Bit SSL</text>
                            </g>

                            <!-- Floating Inflow Metric Badge (Top Right) -->
                            <g filter="url(#shadow3d)">
                                <rect x="250" y="8" width="105" height="32" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                                <circle cx="266" cy="24" r="9" fill="#10B981" fill-opacity="0.15"/>
                                <path d="M266 28 L266 20 M266 20 L263 23 M266 20 L269 23" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <text x="280" y="21" font-family="'Inter', sans-serif" font-size="7.5" font-weight="700" fill="#64748B">GROWTH INFLOW</text>
                                <text x="280" y="31" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="800" fill="#0F172A">+Rp 193.7M</text>
                            </g>

                            <!-- Floating Sparkles -->
                            <path d="M360 75 L363 81 L369 84 L363 87 L360 93 L357 87 L351 84 L357 81 Z" fill="#FBBF24" opacity="0.9"/>
                            <path d="M20 95 L22 99 L26 101 L22 103 L20 107 L18 103 L14 101 L18 99 Z" fill="#2D60FF" opacity="0.6"/>
                        </svg>
                    </div>

                    <!-- Text Presentation -->
                    <div class="space-y-2">
                        <h3 class="text-lg font-extrabold text-brand-navy font-jakarta tracking-tight">Smart Core Banking &amp; Analytics</h3>
                        <p class="text-xs text-brand-muted leading-relaxed px-2">
                            Integrasi real-time arus kas, manajemen mutasi rekening, dan pemantauan analitik terpusat.
                        </p>
                    </div>

                    <!-- Feature Pills -->
                    <div class="flex items-center justify-center gap-2 pt-1 flex-wrap">
                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-brand-blue font-bold text-[10px]">256-Bit AES</span>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">BI-FAST Active</span>
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px]">Audit Trail</span>
                    </div>

                    <!-- Blue Highlight Bottom Border -->
                    <div class="w-full h-1.5 bg-gradient-to-r from-brand-blue to-indigo-600 rounded-full mx-auto"></div>
                </div>
            </div>

            <!-- Bottom Right Expand Icon -->
            <div class="absolute bottom-6 right-6 text-slate-400 hover:text-brand-blue cursor-pointer transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
            </div>
        </div>

    </div>

    <script>
        function fillDemoCredentials() {
            document.getElementById('emailField').value = 'admin@bankdash.com';
            document.getElementById('passwordField').value = 'admin123';
        }

        // Prevent 419 Page Expired caused by browser back/forward cache (bfcache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
