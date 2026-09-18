@extends('layouts.app')

@section('title', __('Executive Banking Dashboard'))
@section('header_title', __('Overview'))

@section('content')
<div class="space-y-8">

    <!-- Quick Stats Bar (Executive Bank Metrics) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Likuiditas Saldo -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">{{ __('Total Kas & Likuiditas') }}</p>
                <h3 id="totalLiquidityDisplay" class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    <span>{{ __('+8.4% bulan ini') }}</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-brand-blue">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Inflow (Uang Masuk) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">{{ __('Inflow / Uang Masuk') }}</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Rp {{ number_format($totalInflowMonth, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <span>{{ __('Setoran & transfer masuk') }}</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
            </div>
        </div>

        <!-- Total Outflow (Uang Keluar) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">{{ __('Outflow / Uang Keluar') }}</p>
                <h3 class="text-2xl font-extrabold text-brand-rose font-jakarta mt-1">Rp {{ number_format($totalOutflowMonth, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-brand-rose">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <span>{{ __('Penarikan & beban tagihan') }}</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-brand-rose">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                </svg>
            </div>
        </div>

        <!-- Total Rekening Terdaftar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">{{ __('Nasabah & Rekening') }}</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">{{ $totalAccounts }} <span class="text-sm font-semibold text-slate-400">{{ __('Akun') }}</span></h3>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('100% Terverifikasi KYC') }}</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-brand-amber">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Section 1: My Cards & Recent Transactions (Matching Bankdash Mockup) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: My Cards (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('My Cards') }}</h2>
                <a href="{{ route('accounts.index') }}" class="text-sm font-bold text-brand-blue hover:underline">{{ __('See All') }}</a>
            </div>

            @php
                $card1 = $cards->get(0);
                $card2 = $cards->get(1);

                $card1Balance = $card1 ? $card1->balance : 57560000;
                $card1Holder = $card1 ? $card1->card_holder : (Auth::check() ? Auth::user()->name : 'Eddy Cusuma');
                $card1Valid = $card1 ? $card1->valid_thru : '12/28';
                $card1Number = $card1 ? (substr($card1->card_number, 0, 4) . ' **** **** ' . substr($card1->card_number, -4)) : '3778 **** **** 1234';

                $card2Balance = $card2 ? $card2->balance : 32000000;
                $card2Holder = $card2 ? $card2->card_holder : 'Treasury Operating';
                $card2Valid = $card2 ? $card2->valid_thru : '01/29';
                $card2Number = $card2 ? (substr($card2->card_number, 0, 4) . ' **** **** ' . substr($card2->card_number, -4)) : '1234 **** **** 5678';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Blue Gradient Card (Card 1) -->
                <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-card flex flex-col justify-between h-56 bg-gradient-to-tr from-[#0A06F4] via-[#2D60FF] to-[#4C49ED]">
                    <!-- Subtle background watermark circle -->
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/10 pointer-events-none"></div>

                    <!-- Top Row: Balance & Chip -->
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-medium text-blue-100 uppercase tracking-wider">{{ __('Balance') }}</span>
                            <h4 id="card1BalanceDisplay" class="text-2xl font-extrabold tracking-tight mt-1 font-jakarta">Rp {{ number_format($card1Balance, 0, ',', '.') }}</h4>
                        </div>
                        <!-- Chip SVG -->
                        <div class="w-10 h-8 rounded-md bg-gradient-to-br from-amber-200 to-amber-400 p-1 flex items-center justify-center shadow-inner opacity-90">
                            <svg class="w-6 h-6 text-amber-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 4h16v16H4V4zm2 2v3h4V6H6zm6 0v3h4V6h-4zm6 0v3h2V6h-2zM6 11v3h4v-3H6zm6 0v3h4v-3h-4zm6 0v3h2v-3h-2zm-12 5v3h4v-3H6zm6 0v3h4v-3h-4zm6 0v3h2v-3h-2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Middle Row: Cardholder & Expiry -->
                    <div class="flex items-center gap-10 text-xs">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-blue-200 font-medium">{{ __('CARD HOLDER') }}</p>
                            <p class="font-bold tracking-wide mt-0.5">{{ $card1Holder }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-blue-200 font-medium">{{ __('VALID THRU') }}</p>
                            <p class="font-bold tracking-wide mt-0.5">{{ $card1Valid }}</p>
                        </div>
                    </div>

                    <!-- Bottom Bar: Card Number & Mastercard Logo -->
                    <div class="pt-4 border-t border-white/20 flex items-center justify-between">
                        <span class="text-lg font-mono font-bold tracking-widest">{{ $card1Number }}</span>
                        <!-- Mastercard Intersecting Circles SVG -->
                        <div class="flex -space-x-3">
                            <div class="w-7 h-7 rounded-full bg-white/50 backdrop-blur-sm"></div>
                            <div class="w-7 h-7 rounded-full bg-white/70 backdrop-blur-sm"></div>
                        </div>
                    </div>
                </div>

                <!-- White Minimalist Card (Card 2) -->
                <div class="relative overflow-hidden rounded-3xl p-6 text-brand-navy shadow-soft border border-slate-100 bg-white flex flex-col justify-between h-56">
                    <!-- Top Row: Balance & Chip -->
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-semibold text-brand-muted uppercase tracking-wider">{{ __('Balance') }}</span>
                            <h4 class="text-2xl font-extrabold tracking-tight mt-1 font-jakarta text-brand-navy">Rp {{ number_format($card2Balance, 0, ',', '.') }}</h4>
                        </div>
                        <!-- Chip SVG (Dark) -->
                        <div class="w-10 h-8 rounded-md bg-slate-100 p-1 flex items-center justify-center border border-slate-200">
                            <svg class="w-6 h-6 text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 4h16v16H4V4zm2 2v3h4V6H6zm6 0v3h4V6h-4zm6 0v3h2V6h-2zM6 11v3h4v-3H6zm6 0v3h4v-3h-4zm6 0v3h2v-3h-2zm-12 5v3h4v-3H6zm6 0v3h4v-3h-4zm6 0v3h2v-3h-2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Middle Row: Cardholder & Expiry -->
                    <div class="flex items-center gap-10 text-xs">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-brand-muted font-semibold">{{ __('CARD HOLDER') }}</p>
                            <p class="font-bold text-slate-800 tracking-wide mt-0.5">{{ $card2Holder }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-brand-muted font-semibold">{{ __('VALID THRU') }}</p>
                            <p class="font-bold text-slate-800 tracking-wide mt-0.5">{{ $card2Valid }}</p>
                        </div>
                    </div>

                    <!-- Bottom Bar: Card Number & Mastercard Logo -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-lg font-mono font-bold tracking-widest text-slate-700">{{ $card2Number }}</span>
                        <!-- Mastercard Intersecting Circles SVG -->
                        <div class="flex -space-x-3">
                            <div class="w-7 h-7 rounded-full bg-slate-300"></div>
                            <div class="w-7 h-7 rounded-full bg-slate-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Recent Transactions (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('Recent Transactions') }}</h2>
                <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-brand-blue hover:underline">{{ __('See All') }}</a>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft h-[224px] flex flex-col justify-between overflow-y-auto">
                <div class="space-y-4" id="recentTransactionsList">
                    @forelse($recentTransactions->take(3) as $tx)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                @if($tx->type == 'credit')
                                    <div class="w-11 h-11 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-11 h-11 rounded-full bg-rose-50 text-brand-rose flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-brand-navy truncate">{{ $tx->description }}</p>
                                    <p class="text-xs text-brand-muted truncate">{{ $tx->transaction_date->format('d F Y') }} • {{ $tx->category }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-sm font-bold {{ $tx->type == 'credit' ? 'text-emerald-600' : 'text-brand-rose' }}">
                                    {{ $tx->type == 'credit' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-brand-muted py-6">{{ __('Belum ada transaksi') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Weekly Activity & Expense Statistics (Matching Bankdash Mockup) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Weekly Activity (Bar Chart) - 7 cols -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('Weekly Activity') }}</h2>
                <!-- Legend -->
                <div class="flex items-center gap-5 text-xs font-semibold">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brand-indigo"></span>
                        <span class="text-brand-muted">{{ __('Deposit') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brand-teal"></span>
                        <span class="text-brand-muted">{{ __('Withdraw') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft">
                <div class="h-64 relative">
                    <canvas id="weeklyActivityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Expense Statistics (Donut/Polar Chart) - 5 cols -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('Expense Statistics') }}</h2>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft">
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="expenseStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Quick Transfer & Balance History (Matching Bankdash Mockup) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Quick Transfer (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('Quick Transfer') }}</h2>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">{{ __('BI-FAST 24/7') }}</span>
            </div>

            <div class="bg-white rounded-3xl p-7 border border-slate-100/80 shadow-soft flex flex-col justify-between h-72">
                <!-- Avatars Carousel -->
                <div class="flex items-center justify-between gap-3 overflow-x-auto pb-1" id="recipientList">
                    @foreach($quickContacts as $index => $contact)
                        <button type="button" 
                                onclick="selectRecipient('{{ $contact['name'] }}', '{{ $contact['role'] }}', '{{ $contact['account'] }}', '{{ $contact['avatar'] }}', this)"
                                class="quick-contact-btn flex flex-col items-center group focus:outline-none transition-all duration-200 active:scale-95 flex-shrink-0 {{ $index === 0 ? 'scale-105' : 'opacity-75 hover:opacity-100' }}">
                            <div class="relative">
                                <img src="{{ $contact['avatar'] }}" 
                                     alt="{{ $contact['name'] }}" 
                                     class="w-13 h-13 sm:w-14 sm:h-14 rounded-full object-cover ring-2 {{ $index === 0 ? 'ring-brand-blue ring-offset-2' : 'ring-transparent' }} group-hover:ring-brand-blue transition-all shadow-sm">
                                <span class="active-badge absolute -top-1 -right-1 w-4 h-4 bg-brand-blue text-white rounded-full flex items-center justify-center text-[9px] font-bold shadow {{ $index === 0 ? '' : 'hidden' }}">✓</span>
                            </div>
                            <p class="text-xs font-bold text-brand-navy mt-1.5 text-center group-hover:text-brand-blue truncate w-16">{{ $contact['name'] }}</p>
                            <p class="text-[10px] font-semibold text-brand-muted text-center truncate w-16">{{ $contact['role'] }}</p>
                        </button>
                    @endforeach

                    <!-- Arrow Next Button -->
                    <button type="button" onclick="scrollRecipients()" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 hover:text-brand-blue hover:shadow transition-all flex-shrink-0" title="Geser Daftar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Recipient Info & Transfer Input Form -->
                <div class="mt-2 space-y-2">
                    <div class="flex items-center justify-between text-[11px] px-1">
                        <span class="text-brand-muted">{{ __('Penerima terpilih:') }}</span>
                        <div class="flex items-center gap-1.5 text-brand-navy font-bold">
                            <span id="labelRecipientName">{{ $quickContacts[0]['name'] ?? 'Charleen' }}</span>
                            <span class="text-slate-400 font-normal text-[10px]">(<span id="labelRecipientRole">{{ $quickContacts[0]['role'] ?? 'CTO' }}</span> &bull; <span id="labelRecipientAcc" class="font-mono">{{ $quickContacts[0]['account'] ?? '1029-3847-5612' }}</span>)</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-semibold text-brand-muted whitespace-nowrap hidden sm:inline">{{ __('Write Amount') }}</span>
                        <div class="relative flex-1 flex items-center">
                            <div class="absolute left-4 pointer-events-none text-slate-400 font-bold text-xs">Rp</div>
                            <input type="text" id="transferAmount" value="5.250.000" 
                                   oninput="formatCurrencyInput(this)"
                                   placeholder="0"
                                   class="w-full bg-[#EDF1F7] text-slate-800 text-sm font-bold pl-11 pr-28 py-3 rounded-full border border-transparent focus:outline-none focus:ring-2 focus:ring-brand-blue/30 transition-all">
                            
                            <button type="button" 
                                    onclick="openTransferModal()"
                                    class="absolute right-1 inset-y-1 px-6 bg-brand-blue hover:bg-blue-700 text-white rounded-full text-xs font-bold flex items-center gap-2 shadow-md shadow-brand-blue/30 transition-all active:scale-95">
                                <span>{{ __('Send') }}</span>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance History (Spline Wave Area Chart) - 7 cols -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-brand-navy font-jakarta">{{ __('Balance History') }}</h2>
                <span class="text-xs font-semibold text-brand-muted">{{ __('Tren 12 Bulan Terakhir') }}</span>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft h-72">
                <div class="h-60 relative">
                    <canvas id="balanceHistoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Bank Announcements (CMS Integration) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-brand-purple flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-brand-navy font-jakarta">{{ __('Warta & Pengumuman Bank (CMS)') }}</h3>
                    <p class="text-xs text-brand-muted">{{ __('Informasi regulasi, pembaruan bunga, dan prosedur perbankan terbaru') }}</p>
                </div>
            </div>
            <a href="{{ route('cms.index') }}" class="text-xs font-bold text-brand-blue hover:underline">{{ __('Kelola Konten CMS') }} &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            @foreach($latestAnnouncements as $post)
                <div class="p-5 rounded-2xl bg-[#F5F7FA] border border-slate-200/60 flex flex-col justify-between hover:border-brand-blue/40 transition-colors">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-blue bg-blue-100/60 px-2.5 py-1 rounded-full">{{ $post->category }}</span>
                        <h4 class="text-sm font-bold text-brand-navy mt-3 line-clamp-2">{{ $post->title }}</h4>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $post->content }}</p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-brand-muted">
                        <span>{{ $post->author }}</span>
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Corporate Banking Quick Transfer Modal -->
<div id="transferModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
        
        <!-- View 1: Transfer Input & Confirmation Form -->
        <div id="transferFormView" class="p-7 space-y-5">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-brand-navy font-jakarta">{{ __('Konfirmasi Transfer Dana') }}</h3>
                        <p class="text-xs text-brand-muted">{{ __('Layanan BI-FAST Perbankan Instan Terpadu') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeTransferModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Error Alert -->
            <div id="transferErrorAlert" class="hidden p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="transferErrorText" class="font-medium">{{ __('Pesan kesalahan transfer') }}</span>
            </div>

            <!-- Source Account Card -->
            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-slate-200/70 space-y-1">
                <span class="text-[10px] uppercase font-bold text-brand-muted tracking-wider">{{ __('Rekening Sumber (Pengirim)') }}</span>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-brand-navy">{{ $card1Holder }}</p>
                        <p class="text-xs font-mono text-slate-500">{{ $card1Number }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400">{{ __('Saldo Tersedia') }}</span>
                        <p class="text-xs font-bold text-brand-blue" id="modalAvailableBalance">Rp {{ number_format($card1Balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Beneficiary (Tujuan) -->
            <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 flex items-center gap-4">
                <img id="modalRecipientAvatar" src="{{ $quickContacts[0]['avatar'] ?? '' }}" alt="Recipient" class="w-12 h-12 rounded-full object-cover ring-2 ring-brand-blue shadow-sm">
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] uppercase font-bold text-brand-blue tracking-wider">{{ __('Penerima Dana') }}</span>
                    <h4 id="modalRecipientName" class="text-sm font-bold text-brand-navy truncate">{{ $quickContacts[0]['name'] ?? 'Charleen' }}</h4>
                    <p class="text-xs text-slate-500 flex items-center gap-1.5 font-mono">
                        <span id="modalRecipientRole" class="font-sans font-medium text-slate-600">{{ $quickContacts[0]['role'] ?? 'CTO' }}</span> &bull; 
                        <span id="modalRecipientAcc">{{ $quickContacts[0]['account'] ?? '1029-3847-5612' }}</span>
                    </p>
                </div>
                <span class="text-[10px] bg-white text-brand-navy font-bold px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">{{ __('Bankdash Core') }}</span>
            </div>

            <!-- Nominal Transfer -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider">{{ __('Jumlah Nominal Transfer (IDR)') }}</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 font-extrabold text-brand-navy text-base">Rp</span>
                    <input type="text" id="modalInputAmount" value="5.250.000" 
                           oninput="formatCurrencyInput(this)"
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-lg font-extrabold text-brand-navy pl-12 pr-4 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition-all">
                </div>
                <!-- Quick Amount Chips -->
                <div class="flex items-center gap-2 pt-1 overflow-x-auto">
                    <button type="button" onclick="setQuickAmount(500000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-brand-blue text-[11px] font-semibold text-slate-600 transition-colors">+500 Rb</button>
                    <button type="button" onclick="setQuickAmount(1000000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-brand-blue text-[11px] font-semibold text-slate-600 transition-colors">+1 Jt</button>
                    <button type="button" onclick="setQuickAmount(2500000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-brand-blue text-[11px] font-semibold text-slate-600 transition-colors">+2.5 Jt</button>
                    <button type="button" onclick="setQuickAmount(5000000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-brand-blue text-[11px] font-semibold text-slate-600 transition-colors">+5 Jt</button>
                </div>
            </div>

            <!-- Notes / Remarks -->
            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">{{ __('Catatan / Berita Transfer') }}</label>
                <input type="text" id="modalTransferNotes" value="{{ __('Operasional Disposisi Bisnis') }}" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs font-medium text-slate-800 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <!-- Fee & Total Summary -->
            <div class="space-y-1.5 pt-2 border-t border-slate-100 text-xs">
                <div class="flex items-center justify-between text-slate-500">
                    <span>{{ __('Biaya Administrasi (BI-FAST)') }}</span>
                    <span class="font-bold text-emerald-600">{{ __('Gratis (Rp 0)') }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-500">
                    <span>{{ __('Kecepatan Proses') }}</span>
                    <span class="font-semibold text-brand-navy">{{ __('Real-time Online Instant') }}</span>
                </div>
            </div>

            <!-- Security Auth Notice -->
            <div class="flex items-center gap-2 p-3 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-800">
                <svg class="w-4 h-4 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>{{ __('Transaksi diotentikasi dengan standar enkripsi SSL 256-bit & Audit Trail Perbankan.') }}</span>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeTransferModal()" class="px-5 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                    {{ __('Batal') }}
                </button>
                <button type="button" onclick="executeTransfer()" class="px-7 py-3 bg-brand-blue hover:bg-blue-700 text-white rounded-2xl text-xs font-bold shadow-lg shadow-brand-blue/30 flex items-center gap-2 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('Kirim Transfer Sekarang') }}</span>
                </button>
            </div>
        </div>

        <!-- View 2: Processing Spinner View -->
        <div id="transferProcessingView" class="hidden p-12 text-center space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full border-4 border-blue-100 border-t-brand-blue animate-spin"></div>
            <h4 class="text-base font-bold text-brand-navy font-jakarta">{{ __('Memproses Transfer Dana...') }}</h4>
            <p class="text-xs text-brand-muted">{{ __('Menghubungi clearing network BI-FAST & mendebet saldo rekening') }}</p>
        </div>

        <!-- View 3: Official Electronic Receipt -->
        <div id="transferReceiptView" class="hidden p-8 space-y-6">
            <div class="text-center space-y-2">
                <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200">{{ __('Transfer Berhasil') }}</span>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1" id="receiptAmountDisplay">Rp 5.250.000</h3>
                <p class="text-xs text-brand-muted" id="receiptTimeDisplay">18 Sep 2026, 11:25 WIB</p>
            </div>

            <!-- Receipt Breakdown Table -->
            <div class="rounded-2xl bg-[#F8FAFC] border border-slate-200/80 p-4 space-y-2.5 text-xs">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                    <span class="text-slate-400">{{ __('Nomor Referensi') }}</span>
                    <span class="font-mono font-bold text-brand-blue" id="receiptRefNo">TRX-TRF-20260918-8491</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">{{ __('Pengirim') }}</span>
                    <span class="font-bold text-brand-navy">{{ $card1Holder }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">{{ __('Penerima') }}</span>
                    <span class="font-bold text-brand-navy" id="receiptRecipientName">Charleen (CTO)</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">{{ __('No. Rekening Tujuan') }}</span>
                    <span class="font-mono text-slate-700" id="receiptRecipientAcc">1029-3847-5612</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">{{ __('Bank Penerima') }}</span>
                    <span class="font-semibold text-slate-700">{{ __('Bankdash Core (Internal)') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">{{ __('Berita Acara') }}</span>
                    <span class="font-medium text-slate-700" id="receiptNotes">{{ __('Operasional Disposisi Bisnis') }}</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                    <span class="text-slate-400">{{ __('Biaya Administrasi') }}</span>
                    <span class="font-bold text-emerald-600">{{ __('Gratis (Rp 0)') }}</span>
                </div>
                <div class="flex items-center justify-between pt-1 font-bold text-brand-navy">
                    <span>{{ __('Sisa Saldo Baru') }}</span>
                    <span class="font-jakarta text-brand-blue" id="receiptNewBalance">Rp 52.310.000</span>
                </div>
            </div>

            <!-- Receipt Actions -->
            <div class="flex items-center justify-between gap-3 pt-2">
                <button type="button" onclick="window.print()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-xs transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zM9 9h6m-6 4h6"></path>
                    </svg>
                    <span>{{ __('Cetak / Simpan PDF') }}</span>
                </button>
                <button type="button" onclick="closeTransferModal()" class="flex-1 py-3 bg-brand-blue hover:bg-blue-700 text-white font-bold rounded-2xl text-xs transition-all shadow-md shadow-brand-blue/30 flex items-center justify-center">
                    <span>{{ __('Selesai') }}</span>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // State management for Quick Transfer
    let currentCardBalance = {{ (float)$card1Balance }};
    let totalLiquidity = {{ (float)$totalBalance }};
    let activeRecipient = {
        name: "{{ $quickContacts[0]['name'] ?? 'Charleen' }}",
        role: "{{ $quickContacts[0]['role'] ?? 'CTO' }}",
        account: "{{ $quickContacts[0]['account'] ?? '1029-3847-5612' }}",
        avatar: "{{ $quickContacts[0]['avatar'] ?? '' }}"
    };

    function selectRecipient(name, role, account, avatar, btnElement) {
        activeRecipient = { name, role, account, avatar };

        // Update indicators in dashboard widget
        document.getElementById('labelRecipientName').innerText = name;
        document.getElementById('labelRecipientRole').innerText = role;
        document.getElementById('labelRecipientAcc').innerText = account;

        // Visual ring highlight on carousel buttons
        document.querySelectorAll('.quick-contact-btn').forEach(btn => {
            btn.classList.remove('scale-105');
            btn.classList.add('opacity-75');
            const img = btn.querySelector('img');
            if (img) {
                img.classList.remove('ring-brand-blue', 'ring-offset-2');
                img.classList.add('ring-transparent');
            }
            const badge = btn.querySelector('.active-badge');
            if (badge) badge.classList.add('hidden');
        });

        if (btnElement) {
            btnElement.classList.remove('opacity-75');
            btnElement.classList.add('scale-105');
            const img = btnElement.querySelector('img');
            if (img) {
                img.classList.remove('ring-transparent');
                img.classList.add('ring-brand-blue', 'ring-offset-2');
            }
            const badge = btnElement.querySelector('.active-badge');
            if (badge) badge.classList.remove('hidden');
        }
    }

    function scrollRecipients() {
        const container = document.getElementById('recipientList');
        if (container) {
            container.scrollBy({ left: 140, behavior: 'smooth' });
        }
    }

    function formatIDR(num) {
        return new Intl.NumberFormat('id-ID').format(Math.max(0, Math.floor(num)));
    }

    function parseCurrency(str) {
        if (!str) return 0;
        const clean = str.toString().replace(/[^0-9]/g, '');
        return clean ? parseInt(clean, 10) : 0;
    }

    function formatCurrencyInput(input) {
        const value = parseCurrency(input.value);
        if (value === 0 && input.value.trim() === '') {
            input.value = '';
            return;
        }
        input.value = formatIDR(value);
    }

    function openTransferModal() {
        const rawAmount = document.getElementById('transferAmount').value;
        const parsedAmount = parseCurrency(rawAmount) || 5250000;

        // Populate modal data
        document.getElementById('modalRecipientName').innerText = activeRecipient.name;
        document.getElementById('modalRecipientRole').innerText = activeRecipient.role;
        document.getElementById('modalRecipientAcc').innerText = activeRecipient.account;
        if (activeRecipient.avatar) {
            document.getElementById('modalRecipientAvatar').src = activeRecipient.avatar;
        }

        document.getElementById('modalAvailableBalance').innerText = 'Rp ' + formatIDR(currentCardBalance);
        document.getElementById('modalInputAmount').value = formatIDR(parsedAmount);

        // Reset views
        const errAlert = document.getElementById('transferErrorAlert');
        if (errAlert) errAlert.classList.add('hidden');
        document.getElementById('transferFormView').classList.remove('hidden');
        document.getElementById('transferProcessingView').classList.add('hidden');
        document.getElementById('transferReceiptView').classList.add('hidden');

        document.getElementById('transferModal').classList.remove('hidden');
    }

    function closeTransferModal() {
        document.getElementById('transferModal').classList.add('hidden');
    }

    function setQuickAmount(amount) {
        document.getElementById('modalInputAmount').value = formatIDR(amount);
    }

    function executeTransfer() {
        const transferAmount = parseCurrency(document.getElementById('modalInputAmount').value);
        const errAlert = document.getElementById('transferErrorAlert');
        const errText = document.getElementById('transferErrorText');
        if (errAlert) errAlert.classList.add('hidden');

        if (transferAmount <= 0) {
            if (errText) errText.innerText = "{{ __('Silakan masukkan nominal transfer yang valid (minimal Rp 10.000).') }}";
            if (errAlert) errAlert.classList.remove('hidden');
            return;
        }

        if (transferAmount > currentCardBalance) {
            if (errText) errText.innerText = "{{ __('Saldo rekening Anda tidak mencukupi untuk transfer ini.') }}";
            if (errAlert) errAlert.classList.remove('hidden');
            return;
        }

        // Switch to Processing View
        document.getElementById('transferFormView').classList.add('hidden');
        document.getElementById('transferProcessingView').classList.remove('hidden');

        fetch("{{ route('transactions.quick_transfer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: transferAmount,
                recipient_name: activeRecipient.name,
                recipient_role: activeRecipient.role,
                recipient_account: activeRecipient.account,
                notes: document.getElementById('modalTransferNotes').value
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status !== 200 || !body.success) {
                // Show error
                document.getElementById('transferProcessingView').classList.add('hidden');
                document.getElementById('transferFormView').classList.remove('hidden');
                if (errText) errText.innerText = body.message || "{{ __('Terjadi kesalahan saat memproses transfer.') }}";
                if (errAlert) errAlert.classList.remove('hidden');
                return;
            }

            // Real persistence success from DB!
            currentCardBalance = body.new_card_balance;
            totalLiquidity = body.total_liquidity;

            // Update live dashboard displays
            const card1Display = document.getElementById('card1BalanceDisplay');
            if (card1Display) card1Display.innerText = body.formatted_new_card_balance;

            const liquidityDisplay = document.getElementById('totalLiquidityDisplay');
            if (liquidityDisplay) liquidityDisplay.innerText = body.formatted_total_liquidity;

            // Synchronize input in dashboard
            document.getElementById('transferAmount').value = formatIDR(body.amount);

            // Populate Receipt View with real database data
            document.getElementById('receiptAmountDisplay').innerText = body.formatted_amount;
            document.getElementById('receiptTimeDisplay').innerText = body.date_str;
            document.getElementById('receiptRefNo').innerText = body.ref_no;
            document.getElementById('receiptRecipientName').innerText = body.recipient_name + (body.recipient_role ? ' (' + body.recipient_role + ')' : '');
            document.getElementById('receiptRecipientAcc').innerText = body.recipient_account;
            document.getElementById('receiptNotes').innerText = body.notes;
            document.getElementById('receiptNewBalance').innerText = body.formatted_new_card_balance;

            // Dynamically prepend new transaction to Recent Transactions widget!
            const list = document.getElementById('recentTransactionsList');
            if (list && body.transaction) {
                const newTrxHtml = `
                    <div class="flex items-center justify-between gap-3 p-2 rounded-2xl bg-rose-50/40 border border-rose-100/60 transition-all duration-300">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-full bg-rose-50 text-brand-rose flex items-center justify-center flex-shrink-0 ring-2 ring-rose-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-brand-navy truncate">${body.transaction.description}</p>
                                <p class="text-xs text-brand-muted truncate">${body.transaction.date} &bull; ${body.transaction.category}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-bold text-brand-rose">${body.transaction.amount_formatted}</span>
                        </div>
                    </div>
                `;
                const emptyMsg = list.querySelector('p.text-center');
                if (emptyMsg) emptyMsg.remove();
                list.insertAdjacentHTML('afterbegin', newTrxHtml);
            }

            // Show Receipt
            document.getElementById('transferProcessingView').classList.add('hidden');
            document.getElementById('transferReceiptView').classList.remove('hidden');
        })
        .catch(err => {
            document.getElementById('transferProcessingView').classList.add('hidden');
            document.getElementById('transferFormView').classList.remove('hidden');
            if (errText) errText.innerText = "{{ __('Koneksi ke server perbankan terputus. Silakan coba lagi.') }}";
            if (errAlert) errAlert.classList.remove('hidden');
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        // 1. Weekly Activity Chart (Bar Chart: Sat -> Fri)
        const weeklyCtx = document.getElementById('weeklyActivityChart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: @json($days),
                datasets: [
                    {
                        label: 'Deposit',
                        data: @json($depositWeekly),
                        backgroundColor: '#1814F3',
                        borderRadius: 20,
                        borderSkipped: false,
                        barPercentage: 0.45,
                        categoryPercentage: 0.6
                    },
                    {
                        label: 'Withdraw',
                        data: @json($withdrawWeekly),
                        backgroundColor: '#16DBCC',
                        borderRadius: 20,
                        borderSkipped: false,
                        barPercentage: 0.45,
                        categoryPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 12, weight: 500 } }
                    },
                    y: {
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 12 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Expense Statistics Chart (Polar / Pie Chart)
        const expenseCtx = document.getElementById('expenseStatsChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'polarArea',
            data: {
                labels: @json($expenseCategories['labels']),
                datasets: [{
                    data: @json($expenseCategories['data']),
                    backgroundColor: [
                        '#396AFF', // Entertainment
                        '#FF1493', // Bill Expense
                        '#232360', // Investment
                        '#FC7900'  // Others
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#343C6A',
                            boxWidth: 12,
                            font: { family: 'Inter', size: 11, weight: 600 }
                        }
                    }
                },
                scales: {
                    r: {
                        grid: { color: '#E2E8F0' },
                        ticks: { display: false }
                    }
                }
            }
        });

        // 3. Balance History Chart (Spline Smooth Wave)
        const balanceCtx = document.getElementById('balanceHistoryChart').getContext('2d');
        
        // Gradient fill for line chart
        const gradient = balanceCtx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(45, 96, 255, 0.25)');
        gradient.addColorStop(1, 'rgba(45, 96, 255, 0.0)');

        new Chart(balanceCtx, {
            type: 'line',
            data: {
                labels: @json($balanceHistory['labels']),
                datasets: [{
                    label: 'Balance History',
                    data: @json($balanceHistory['data']),
                    borderColor: '#1814F3',
                    borderWidth: 3,
                    tension: 0.45, // Smooth cubic spline wave
                    fill: true,
                    backgroundColor: gradient,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#1814F3',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 12 } }
                    },
                    y: {
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 12 } },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

