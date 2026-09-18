@extends('layouts.app')

@section('title', 'PSAK 71: Stress Testing Makroekonomi')
@section('header_title', 'PSAK 71: Forward-Looking Macro Stress Testing Simulator')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Hero Header with Magnific Finance Vector Artwork -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-3 max-w-xl">
            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-brand-blue uppercase tracking-wider">
                Simulasi Sensitivitas Makroekonomi Dinamis
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy font-jakarta tracking-tight">
                Stress Testing Ketahanan Modal Bank Terhadap Krisis
            </h2>
            <p class="text-xs text-brand-muted leading-relaxed">
                Geser parameter makroekonomi (Suku Bunga BI, Inflasi, dan Pertumbuhan PDB) untuk menguji skenario penurunan nilai kredit, lonjakan migrasi ke Stage 2/3, dan dampaknya terhadap rasio kecukupan modal bank (<strong class="text-brand-navy">CAR</strong>).
            </p>
        </div>

        <!-- Magnific Vector Illustration: Risk Analyst sitting on gold coin stacks (SVG) -->
        <div class="flex-shrink-0 w-64 h-44 relative flex items-center justify-center">
            <svg viewBox="0 0 350 250" class="w-full h-full drop-shadow-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Stack of Gold Coins -->
                <g id="coinStack">
                    <!-- Layer 1 -->
                    <rect x="90" y="180" width="160" height="20" rx="10" fill="#F59E0B"/>
                    <rect x="90" y="174" width="160" height="12" rx="6" fill="#FBBF24"/>
                    <!-- Layer 2 -->
                    <rect x="105" y="155" width="130" height="20" rx="10" fill="#F59E0B"/>
                    <rect x="105" y="149" width="130" height="12" rx="6" fill="#FCD34D"/>
                    <!-- Layer 3 -->
                    <rect x="120" y="130" width="100" height="20" rx="10" fill="#F59E0B"/>
                    <rect x="120" y="124" width="100" height="12" rx="6" fill="#FDE68A"/>
                </g>

                <!-- Professional Analyst Figure on Coins with Laptop (Magnific Style) -->
                <circle cx="170" cy="55" r="14" fill="#1814F3"/>
                <!-- Body / Shirt -->
                <path d="M150 78 C150 70 190 70 190 78 L195 118 L145 118 Z" fill="#2D60FF"/>
                <!-- Arms holding laptop -->
                <path d="M152 86 L138 108 L170 108" stroke="#1814F3" stroke-width="4" stroke-linecap="round"/>
                <!-- Laptop device -->
                <rect x="125" y="98" width="36" height="22" rx="3" fill="#0F172A"/>
                <rect x="128" y="101" width="30" height="16" rx="2" fill="#38BDF8"/>
                <rect x="118" y="120" width="50" height="4" rx="2" fill="#94A3B8"/>
                
                <!-- Floating Growth Curve & Currency Vector -->
                <path d="M220 70 Q 250 50 280 85 T 320 60" stroke="#10B981" stroke-width="4" stroke-linecap="round" fill="none"/>
                <circle cx="280" cy="85" r="10" fill="#10B981" fill-opacity="0.2"/>
                <text x="277" y="89" font-family="Arial" font-size="11" font-weight="bold" fill="#10B981">$</text>
            </svg>
        </div>
    </div>

    <!-- Quick Scenario Presets -->
    <div class="flex flex-wrap items-center gap-4">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Preset Skenario OJK:</span>
        <button type="button" onclick="setPreset(6.25, 2.80, 5.10)" 
                class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-brand-navy rounded-xl text-xs font-bold shadow-sm transition-all">
            Baseline (Normal BI 6.25%)
        </button>
        <button type="button" onclick="setPreset(5.50, 2.00, 5.80)" 
                class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold shadow-sm transition-all">
            Optimis (Ekspansi PDB 5.8%)
        </button>
        <button type="button" onclick="setPreset(7.75, 4.80, 2.80)" 
                class="px-4 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold shadow-sm transition-all">
            Downturn / Krisis Berat (BI 7.75%)
        </button>
    </div>

    <!-- Main Grid: Sliders & Live Results -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Controls Column (6 cols) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft space-y-7">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Parameter Skenario Makroekonomi</h3>
                <p class="text-xs text-brand-muted mt-0.5">Ubah slider di bawah untuk melihat respons kalkulasi seketika</p>
            </div>

            <!-- Slider 1: BI Rate -->
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-brand-navy">BI 7-Day Reverse Repo Rate</span>
                    <span class="font-mono font-bold text-sm text-brand-blue" id="valBiRate">6.25%</span>
                </div>
                <input type="range" id="sliderBiRate" min="4.50" max="9.00" step="0.25" value="6.25"
                       oninput="runSimulation()"
                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#2D60FF]">
                <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                    <span>4.50% (Longgar)</span>
                    <span>6.25% (Acuan)</span>
                    <span>9.00% (Ketat Krisis)</span>
                </div>
            </div>

            <!-- Slider 2: Inflasi -->
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-brand-navy">Tingkat Inflasi Tahunan (YoY)</span>
                    <span class="font-mono font-bold text-sm text-amber-600" id="valInflation">2.80%</span>
                </div>
                <input type="range" id="sliderInflation" min="1.50" max="8.00" step="0.10" value="2.80"
                       oninput="runSimulation()"
                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#FFBB38]">
                <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                    <span>1.50% (Rendah)</span>
                    <span>2.80% (Stabil)</span>
                    <span>8.00% (Hiperinflasi)</span>
                </div>
            </div>

            <!-- Slider 3: PDB Growth -->
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-brand-navy">Pertumbuhan Ekonomi (PDB / GDP)</span>
                    <span class="font-mono font-bold text-sm text-emerald-600" id="valGdp">5.10%</span>
                </div>
                <input type="range" id="sliderGdp" min="-2.00" max="6.50" step="0.10" value="5.10"
                       oninput="runSimulation()"
                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#16DBCC]">
                <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                    <span>-2.00% (Resesi)</span>
                    <span>5.10% (Normal)</span>
                    <span>6.50% (Booming)</span>
                </div>
            </div>

            <!-- Stress Factor Indicator -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                <div>
                    <p class="font-bold text-brand-navy">Indeks Tekanan Makro (Stress Multiplier)</p>
                    <p class="text-[10px] text-slate-400">Dihitung dari kombinasi suku bunga, inflasi, dan defisit PDB</p>
                </div>
                <span class="font-mono font-extrabold text-base text-brand-blue" id="dispStressDelta">0.00x</span>
            </div>
        </div>

        <!-- Simulation Results Output (6 cols) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft flex flex-col justify-between space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-brand-navy font-jakarta">Hasil Simulasi Dampak ke Modal</h3>
                    <p class="text-xs text-brand-muted mt-0.5">Uji ketahanan solvabilitas bank dan kecukupan modal CAR</p>
                </div>
                <span id="dispStatus" class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Aman (Regulasi OJK > 12%)
                </span>
            </div>

            <!-- CAR Result Card -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-slate-900 to-brand-navy text-white shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-blue-200 font-semibold">Rasio Kecukupan Modal (CAR)</p>
                    <h2 class="text-3xl font-black font-jakarta mt-1 tracking-tight" id="dispCar">19.47%</h2>
                    <p class="text-[11px] text-slate-300 mt-1">Batas aman modal minimum OJK: <strong class="text-white">12.00%</strong></p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-emerald-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>

            <!-- Credit Migration Shift Stats -->
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
                    <p class="text-[10px] uppercase font-bold text-amber-800">Migrasi Kredit ke Stage 2</p>
                    <p class="text-sm font-bold text-amber-900 mt-1 font-mono" id="dispMigrate1to2">Rp 0</p>
                    <p class="text-[10px] text-amber-600 mt-0.5">Debitur mengalami SICR</p>
                </div>

                <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-100">
                    <p class="text-[10px] uppercase font-bold text-brand-rose">Migrasi Kredit ke Stage 3</p>
                    <p class="text-sm font-bold text-rose-900 mt-1 font-mono" id="dispMigrate2to3">Rp 0</p>
                    <p class="text-[10px] text-rose-600 mt-0.5">Debitur jatuh default / macet</p>
                </div>
            </div>

            <!-- Additional CKPN Needed -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                <div>
                    <span class="text-slate-500 font-semibold">Tambahan Beban CKPN Baru:</span>
                    <p class="text-xs font-mono font-bold text-brand-rose" id="dispEclIncrease">+Rp 0</p>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 font-semibold">Total CKPN Stresstest:</span>
                    <p class="text-sm font-mono font-bold text-brand-navy" id="dispTotalEcl">Rp {{ number_format($baseEcl, 0, ',', '.') }}</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function setPreset(bi, inf, gdp) {
        document.getElementById('sliderBiRate').value = bi;
        document.getElementById('sliderInflation').value = inf;
        document.getElementById('sliderGdp').value = gdp;
        runSimulation();
    }

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function runSimulation() {
        const bi = parseFloat(document.getElementById('sliderBiRate').value);
        const inf = parseFloat(document.getElementById('sliderInflation').value);
        const gdp = parseFloat(document.getElementById('sliderGdp').value);

        document.getElementById('valBiRate').innerText = bi.toFixed(2) + '%';
        document.getElementById('valInflation').innerText = inf.toFixed(2) + '%';
        document.getElementById('valGdp').innerText = gdp.toFixed(2) + '%';

        fetch("{{ route('psak.stresstest.simulate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                bi_rate: bi,
                inflation: inf,
                gdp: gdp
            })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('dispStressDelta').innerText = data.stressDelta + 'x';
            document.getElementById('dispMigrate1to2').innerText = formatRupiah(data.migratedFrom1to2);
            document.getElementById('dispMigrate2to3').innerText = formatRupiah(data.migratedFrom2to3);
            document.getElementById('dispEclIncrease').innerText = '+' + formatRupiah(data.eclIncrease);
            document.getElementById('dispTotalEcl').innerText = formatRupiah(data.newTotalEcl);
            document.getElementById('dispCar').innerText = data.newCar.toFixed(2) + '%';
            
            const statusBadge = document.getElementById('dispStatus');
            statusBadge.innerText = data.status;
            if (data.newCar >= 12.0) {
                statusBadge.className = 'px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else if (data.newCar >= 8.0) {
                statusBadge.className = 'px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200';
            } else {
                statusBadge.className = 'px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-brand-rose border border-rose-200';
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        runSimulation();
    });
</script>
@endpush

