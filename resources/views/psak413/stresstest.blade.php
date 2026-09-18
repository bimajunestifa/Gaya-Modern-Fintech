@extends('layouts.app')

@section('title', 'PSAK 413: Stress Testing Makroekonomi Syariah')
@section('header_title', 'PSAK 413: Simulator Stress Test Makroekonomi Syariah')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-8 text-white shadow-soft flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-white/20 text-emerald-100 text-xs font-bold">
                <span>Forward-Looking Macro Shocks • PSAK 413</span>
            </div>
            <h2 class="text-2xl font-extrabold font-jakarta">Simulasi Ketahanan Portofolio Syariah</h2>
            <p class="text-xs text-emerald-100 leading-relaxed">
                Uji sensitivitas daya tahan portofolio pembiayaan syariah terhadap gejolak makroekonomi (Penurunan PDB, Lonjakan Inflasi, Volatilitas Indeks ISSI &amp; Yield Sukuk SBIS).
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('psak413.dashboard') }}" class="px-5 py-2.5 bg-white text-emerald-800 font-bold rounded-xl text-xs hover:bg-emerald-50 transition-all shadow-sm">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Interactive Stress Test Simulator Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Scenario & Parameters Control -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-soft space-y-6">
            <div>
                <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Parameter Skenario Makro</h3>
                <p class="text-xs text-brand-muted mt-0.5">Pilih skenario standar atau sesuaikan variabel</p>
            </div>

            <!-- Scenario Selection Pills -->
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="setScenario('baseline')" id="btn-baseline" 
                        class="py-2.5 px-3 rounded-2xl text-xs font-bold border transition-all text-center bg-slate-50 text-slate-700 border-slate-200 hover:border-emerald-500">
                    Baseline
                </button>
                <button type="button" onclick="setScenario('moderate')" id="btn-moderate" 
                        class="py-2.5 px-3 rounded-2xl text-xs font-bold border transition-all text-center bg-emerald-50 text-emerald-700 border-emerald-500 shadow-sm">
                    Moderat
                </button>
                <button type="button" onclick="setScenario('severe')" id="btn-severe" 
                        class="py-2.5 px-3 rounded-2xl text-xs font-bold border transition-all text-center bg-slate-50 text-slate-700 border-slate-200 hover:border-rose-500">
                    Severe Shock
                </button>
            </div>

            <!-- Macro Inputs Form -->
            <form id="stressTestForm" class="space-y-4 pt-2">
                @csrf
                <input type="hidden" name="scenario" id="input_scenario" value="moderate">

                <!-- GDP Growth -->
                <div>
                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Pertumbuhan PDB Riil (%)</span>
                        <span id="label_gdp" class="text-emerald-700 font-mono">3.80%</span>
                    </label>
                    <input type="range" name="gdp_growth" id="input_gdp" min="-2.0" max="7.0" step="0.1" value="3.8" 
                           oninput="document.getElementById('label_gdp').innerText = parseFloat(this.value).toFixed(2) + '%'"
                           class="w-full accent-emerald-600">
                </div>

                <!-- Inflation Rate -->
                <div>
                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Inflasi Tahunan (%)</span>
                        <span id="label_inflation" class="text-amber-600 font-mono">4.90%</span>
                    </label>
                    <input type="range" name="inflation_rate" id="input_inflation" min="1.0" max="15.0" step="0.1" value="4.9" 
                           oninput="document.getElementById('label_inflation').innerText = parseFloat(this.value).toFixed(2) + '%'"
                           class="w-full accent-amber-500">
                </div>

                <!-- ISSI Index Change -->
                <div>
                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Perubahan Indeks Saham Syariah (ISSI)</span>
                        <span id="label_issi" class="text-indigo-600 font-mono">-4.20%</span>
                    </label>
                    <input type="range" name="issi_index_change" id="input_issi" min="-30.0" max="20.0" step="0.5" value="-4.2" 
                           oninput="document.getElementById('label_issi').innerText = parseFloat(this.value).toFixed(2) + '%'"
                           class="w-full accent-indigo-500">
                </div>

                <!-- SBIS / Sukuk BI Yield -->
                <div>
                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Yield Sukuk / SBIS BI (%)</span>
                        <span id="label_sbis" class="text-teal-600 font-mono">7.50%</span>
                    </label>
                    <input type="range" name="sbis_yield_rate" id="input_sbis" min="3.0" max="15.0" step="0.25" value="7.5" 
                           oninput="document.getElementById('label_sbis').innerText = parseFloat(this.value).toFixed(2) + '%'"
                           class="w-full accent-teal-600">
                </div>

                <!-- USD/IDR -->
                <div>
                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Kurs USD / IDR</span>
                        <span id="label_usd" class="text-slate-700 font-mono">Rp 16.400</span>
                    </label>
                    <input type="number" name="usd_idr_rate" id="input_usd" value="16400" 
                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Run Simulation Button -->
                <div class="pt-4">
                    <button type="button" onclick="runSimulation()" id="simulateBtn" 
                            class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Jalankan Simulasi PSAK 413</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Simulation Results & Impact Visualizer -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Result Cards Comparison -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Simulated ECL -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estimasi CKPN Syariah</span>
                    <h3 id="res_sim_ecl" class="text-xl font-extrabold text-rose-600 font-jakarta mt-1">Rp {{ number_format($baselineEcl * 1.35, 0, ',', '.') }}</h3>
                    <p id="res_ecl_delta" class="text-xs text-rose-500 font-semibold mt-1">+Rp {{ number_format($baselineEcl * 0.35, 0, ',', '.') }} (+35%)</p>
                    <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex justify-between">
                        <span>Baseline:</span>
                        <span class="font-bold">Rp {{ number_format($baselineEcl, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Simulated NPF -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NPF Syariah Pasca Shock</span>
                    <h3 id="res_sim_npf" class="text-xl font-extrabold text-amber-600 font-jakarta mt-1">{{ number_format($baselineNpf * 1.35, 2) }}%</h3>
                    <p id="res_npf_delta" class="text-xs text-amber-500 font-semibold mt-1">+{{ number_format($baselineNpf * 0.35, 2) }}% pts</p>
                    <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex justify-between">
                        <span>Baseline NPF:</span>
                        <span class="font-bold">{{ number_format($baselineNpf, 2) }}%</span>
                    </div>
                </div>

                <!-- Provisi Kafalah Impact -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Provisi Kafalah Terbentuk</span>
                    <h3 id="res_sim_kafalah" class="text-xl font-extrabold text-purple-600 font-jakarta mt-1">Rp {{ number_format($baselineKafalah * 1.25, 0, ',', '.') }}</h3>
                    <p class="text-xs text-purple-500 font-semibold mt-1">Kewajiban Kontinjensi</p>
                    <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex justify-between">
                        <span>Baseline:</span>
                        <span class="font-bold">Rp {{ number_format($baselineKafalah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Staging Migration Visualizer Chart -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-soft space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Dampak Migrasi Staging Pembiayaan</h3>
                        <p class="text-xs text-brand-muted mt-0.5">Pergeseran portofolio dari Stage 1 ke Stage 2 (SICR) &amp; Stage 3 (NPF)</p>
                    </div>
                    <span id="stressIndexBadge" class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-extrabold border border-amber-200">
                        Stress Index: 1.35x
                    </span>
                </div>
                <div class="relative h-64">
                    <canvas id="shariaStressBarChart"></canvas>
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let stressChart = null;

    const scenarioPresets = {
        baseline: { gdp: 5.15, inf: 2.75, issi: 6.5, sbis: 6.25, usd: 15650 },
        moderate: { gdp: 3.80, inf: 4.90, issi: -4.2, sbis: 7.50, usd: 16400 },
        severe:   { gdp: 1.50, inf: 7.80, issi: -15.5, sbis: 9.25, usd: 17250 }
    };

    function setScenario(sc) {
        document.getElementById('input_scenario').value = sc;
        const p = scenarioPresets[sc];

        ['baseline', 'moderate', 'severe'].forEach(s => {
            const btn = document.getElementById('btn-' + s);
            if (s === sc) {
                btn.className = 'py-2.5 px-3 rounded-2xl text-xs font-bold border transition-all text-center bg-emerald-50 text-emerald-700 border-emerald-500 shadow-sm';
            } else {
                btn.className = 'py-2.5 px-3 rounded-2xl text-xs font-bold border transition-all text-center bg-slate-50 text-slate-700 border-slate-200 hover:border-emerald-500';
            }
        });

        document.getElementById('input_gdp').value = p.gdp;
        document.getElementById('label_gdp').innerText = p.gdp.toFixed(2) + '%';

        document.getElementById('input_inflation').value = p.inf;
        document.getElementById('label_inflation').innerText = p.inf.toFixed(2) + '%';

        document.getElementById('input_issi').value = p.issi;
        document.getElementById('label_issi').innerText = p.issi.toFixed(2) + '%';

        document.getElementById('input_sbis').value = p.sbis;
        document.getElementById('label_sbis').innerText = p.sbis.toFixed(2) + '%';

        document.getElementById('input_usd').value = p.usd;

        runSimulation();
    }

    function runSimulation() {
        const btn = document.getElementById('simulateBtn');
        btn.disabled = true;
        btn.innerText = 'Memproses Simulasi...';

        const formData = new FormData(document.getElementById('stressTestForm'));

        fetch("{{ route('psak413.stresstest.simulate') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> <span>Jalankan Simulasi PSAK 413</span>';

            if (data.success) {
                document.getElementById('res_sim_ecl').innerText = 'Rp ' + Number(data.simulated_ecl).toLocaleString('id-ID');
                document.getElementById('res_ecl_delta').innerText = '+Rp ' + Number(data.ecl_delta).toLocaleString('id-ID') + ' (+' + ((data.ecl_delta / data.baseline_ecl) * 100).toFixed(1) + '%)';
                document.getElementById('res_sim_npf').innerText = Number(data.simulated_npf).toFixed(2) + '%';
                document.getElementById('res_npf_delta').innerText = (data.simulated_npf >= data.baseline_npf ? '+' : '') + (data.simulated_npf - data.baseline_npf).toFixed(2) + '% pts';
                document.getElementById('res_sim_kafalah').innerText = 'Rp ' + Number(data.simulated_kafalah).toLocaleString('id-ID');
                document.getElementById('stressIndexBadge').innerText = 'Stress Index: ' + data.macro_stress_index + 'x';

                updateChart(data);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Jalankan Simulasi PSAK 413';
            console.error(err);
        });
    }

    function updateChart(data) {
        if (!stressChart) return;
        stressChart.data.datasets[1].data = [
            data.simulated_stage1,
            data.simulated_stage2,
            data.simulated_stage3
        ];
        stressChart.update();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('shariaStressBarChart');
        if (ctx) {
            stressChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Stage 1 (Performing)', 'Stage 2 (SICR)', 'Stage 3 (NPF Macet)'],
                    datasets: [
                        {
                            label: 'Baseline (Posisi Awal)',
                            data: [50000000000, 7200000000, 5400000000],
                            backgroundColor: '#CBD5E1',
                            borderRadius: 6
                        },
                        {
                            label: 'Pasca Shock Makro',
                            data: [45000000000, 11000000000, 6600000000],
                            backgroundColor: '#10B981',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            ticks: {
                                callback: val => (val / 1000000000).toFixed(1) + ' M'
                            }
                        }
                    }
                }
            });
        }
        runSimulation();
    });
</script>
@endpush
@endsection

