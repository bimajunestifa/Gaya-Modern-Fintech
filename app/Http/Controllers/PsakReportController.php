<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsakCreditPortfolio;
use App\Models\BankAccount;
use App\Models\Investment;
use Carbon\Carbon;

class PsakReportController extends Controller
{
    public function index()
    {
        // 1. Aset (Neraca)
        $kasBank = BankAccount::sum('balance') * 0.15;
        $penempatanBi = BankAccount::sum('balance') * 0.25;
        $suratBerharga = Investment::sum('current_value');
        
        $kreditGross = PsakCreditPortfolio::sum('outstanding_balance');
        $ckpnKredit = PsakCreditPortfolio::sum('ecl_allowance');
        $kreditNetto = $kreditGross - $ckpnKredit;

        $asetTetap = 450000000.00;
        $asetLainnya = 120000000.00;

        $totalAset = $kasBank + $penempatanBi + $suratBerharga + $kreditNetto + $asetTetap + $asetLainnya;

        // 2. Liabilitas & Ekuitas
        $dpkGiro = BankAccount::where('account_type', 'like', '%Giro%')->sum('balance') ?: 145000000.00;
        $dpkTabungan = BankAccount::where('account_type', 'like', '%Tabungan%')->sum('balance') ?: 87500000.00;
        $dpkDeposito = BankAccount::where('account_type', 'like', '%Deposito%')->sum('balance') ?: 250000000.00;
        $totalDpk = $dpkGiro + $dpkTabungan + $dpkDeposito;

        $liabilitasLain = 180000000.00;
        $totalLiabilitas = $totalDpk + $liabilitasLain;

        $modalInti = 1850000000.00;
        $cadanganUmum = 210000000.00;
        $labaDitahan = $totalAset - $totalLiabilitas - $modalInti - $cadanganUmum;
        $totalEkuitas = $modalInti + $cadanganUmum + $labaDitahan;

        // 3. Laba Rugi Komprehensif
        $pendapatanBunga = $kreditGross * 0.0975;
        $bebanBunga = $totalDpk * 0.0350;
        $pendapatanBungaBersih = $pendapatanBunga - $bebanBunga;

        $bebanEclCkpn = $ckpnKredit * 0.25; // Alokasi kuartalan
        $pendapatanOperasionalLain = 95000000.00;
        $bebanOperasional = 185000000.00;

        $labaOperasional = $pendapatanBungaBersih - $bebanEclCkpn + $pendapatanOperasionalLain - $bebanOperasional;
        $pajakPenghasilan = $labaOperasional * 0.22;
        $labaBersihTahunBerjalan = $labaOperasional - $pajakPenghasilan;

        // 4. Rasio Finansial OJK & PSAK
        $stage3Ead = PsakCreditPortfolio::where('stage', 3)->sum('outstanding_balance');
        $stage3Ecl = PsakCreditPortfolio::where('stage', 3)->sum('ecl_allowance');
        
        $nplGross = $kreditGross > 0 ? ($stage3Ead / $kreditGross) * 100 : 0.0;
        $nplNet = $kreditGross > 0 ? max(0, ($stage3Ead - $stage3Ecl) / $kreditGross) * 100 : 0.0;
        
        $totalRwa = 9500000000.00;
        $car = ($modalInti / $totalRwa) * 100;
        $nim = $totalAset > 0 ? ($pendapatanBungaBersih / $totalAset) * 100 : 0.0;
        $ldr = $totalDpk > 0 ? ($kreditGross / $totalDpk) * 100 : 0.0;
        $bopo = ($pendapatanBunga + $pendapatanOperasionalLain) > 0 ? (($bebanBunga + $bebanOperasional + $bebanEclCkpn) / ($pendapatanBunga + $pendapatanOperasionalLain)) * 100 : 0.0;
        $roa = $totalAset > 0 ? ($labaBersihTahunBerjalan / $totalAset) * 100 : 0.0;
        $roe = $totalEkuitas > 0 ? ($labaBersihTahunBerjalan / $totalEkuitas) * 100 : 0.0;

        return view('psak.reports', compact(
            'kasBank',
            'penempatanBi',
            'suratBerharga',
            'kreditGross',
            'ckpnKredit',
            'kreditNetto',
            'asetTetap',
            'asetLainnya',
            'totalAset',
            'dpkGiro',
            'dpkTabungan',
            'dpkDeposito',
            'totalDpk',
            'liabilitasLain',
            'totalLiabilitas',
            'modalInti',
            'cadanganUmum',
            'labaDitahan',
            'totalEkuitas',
            'pendapatanBunga',
            'bebanBunga',
            'pendapatanBungaBersih',
            'bebanEclCkpn',
            'pendapatanOperasionalLain',
            'bebanOperasional',
            'labaOperasional',
            'pajakPenghasilan',
            'labaBersihTahunBerjalan',
            'car',
            'nplGross',
            'nplNet',
            'nim',
            'ldr',
            'bopo',
            'roa',
            'roe'
        ));
    }
}
