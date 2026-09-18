<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Psak413CreditPortfolio;
use App\Models\Psak413JournalEntry;

class Psak413Test extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Psak413DataSeeder::class);
        $this->user = User::factory()->create();
    }

    /**
     * Unauthenticated guest is redirected to login.
     */
    public function test_guest_is_redirected_to_login_on_psak413_routes(): void
    {
        $response = $this->get('/psak413/dashboard');
        $response->assertRedirect('/login');

        $response2 = $this->get('/psak413/stresstest');
        $response2->assertRedirect('/login');
    }

    /**
     * Authenticated user can render PSAK 413 Dashboard.
     */
    public function test_authenticated_user_can_access_psak413_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/psak413/dashboard');
        $response->assertStatus(200);
        $response->assertSee('PSAK 413: Penurunan Nilai Aset Syariah');
    }

    /**
     * Authenticated user can render PSAK 413 Stress Test page.
     */
    public function test_authenticated_user_can_access_psak413_stresstest(): void
    {
        $response = $this->actingAs($this->user)->get('/psak413/stresstest');
        $response->assertStatus(200);
        $response->assertSee('Simulasi Ketahanan Portofolio Syariah');
    }

    /**
     * Authenticated user can run PSAK 413 stress test simulation via AJAX.
     */
    public function test_stress_test_simulation_returns_accurate_results(): void
    {
        $response = $this->actingAs($this->user)->postJson('/psak413/stresstest/simulate', [
            'scenario' => 'moderate',
            'gdp_growth' => 3.8,
            'inflation_rate' => 4.9,
            'issi_index_change' => -4.2,
            'sbis_yield_rate' => 7.5,
            'usd_idr_rate' => 16400,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'scenario',
            'macro_stress_index',
            'baseline_ecl',
            'simulated_ecl',
            'ecl_delta',
            'simulated_kafalah',
            'simulated_npf',
        ]);
        $this->assertTrue($response->json('success'));
    }

    /**
     * Authenticated user can render PSAK 413 Financial Reports & CALK.
     */
    public function test_authenticated_user_can_access_psak413_reports(): void
    {
        $response = $this->actingAs($this->user)->get('/psak413/reports');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN KEUANGAN PSAK 413');
    }

    /**
     * Authenticated user can render PSAK 413 Batch Import page and download template.
     */
    public function test_authenticated_user_can_access_import_and_download_template(): void
    {
        $response = $this->actingAs($this->user)->get('/psak413/import');
        $response->assertStatus(200);

        $download = $this->actingAs($this->user)->get('/psak413/download-template');
        $download->assertStatus(200);
        $download->assertHeader('Content-Type', 'text/csv; charset=utf-8');
    }

    /**
     * Authenticated user can render journals and generate automatic balanced batch.
     */
    public function test_authenticated_user_can_generate_sharia_journals(): void
    {
        $response = $this->actingAs($this->user)->get('/psak413/journals');
        $response->assertStatus(200);

        $genResponse = $this->actingAs($this->user)->post('/psak413/journals/generate');
        $genResponse->assertRedirect();
        $genResponse->assertSessionHas('success');

        $totalDebit = Psak413JournalEntry::sum('debit');
        $totalCredit = Psak413JournalEntry::sum('credit');
        $this->assertEquals($totalDebit, $totalCredit);
    }

    /**
     * Test Sharia ECL calculation mathematical precision across different contracts.
     */
    public function test_sharia_ecl_calculation_formula(): void
    {
        // Murabahah: Gross 10M, Margin Suspended 1M => Net 9M, DPD 0 => Stage 1
        $calcMurabahah = Psak413CreditPortfolio::calculateEclSyariah('MURABAHAH', 10000000000, 1000000000, 0, false, 12000000000, 0, 10.0);
        $this->assertEquals(1, $calcMurabahah['stage']);
        $this->assertEquals(9000000000, $calcMurabahah['net_carrying_amount']);
        $this->assertGreaterThan(0, $calcMurabahah['ecl_allowance']);

        // Musyarakah: DPD 100 => Stage 3 (Impaired/NPF), PD 100%
        $calcMusyarakah = Psak413CreditPortfolio::calculateEclSyariah('MUSYARAKAH', 5000000000, 0, 100, false, 4000000000, 0, 11.0);
        $this->assertEquals(3, $calcMusyarakah['stage']);
        $this->assertEquals(100.00, $calcMusyarakah['pd_rate']);

        // Kafalah: Guarantee 20M => Generates Kafalah Provision
        $calcKafalah = Psak413CreditPortfolio::calculateEclSyariah('KAFALAH', 0, 0, 0, false, 15000000000, 20000000000, 2.5);
        $this->assertGreaterThan(0, $calcKafalah['kafalah_provision_amount']);
    }

    /**
     * Test Language Switcher translates PSAK 413 and UI across all 4 locales (ID, EN, AR, ZH).
     */
    public function test_language_switcher_translates_psak413_menu(): void
    {
        // 1. English Locale
        $this->get('/lang/en')->assertRedirect();
        $responseEn = $this->actingAs($this->user)->withSession(['locale' => 'en'])->get('/psak413/dashboard');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('PSAK 413 Islamic');
        $responseEn->assertSee('Islamic ECL');

        // 2. Arabic Locale (RTL)
        $this->get('/lang/ar')->assertRedirect();
        $responseAr = $this->actingAs($this->user)->withSession(['locale' => 'ar'])->get('/psak413/dashboard');
        $responseAr->assertStatus(200);
        $responseAr->assertSee('معيار المحاسبة الشرعي PSAK 413');

        // 3. Chinese Locale
        $this->get('/lang/zh')->assertRedirect();
        $responseZh = $this->actingAs($this->user)->withSession(['locale' => 'zh'])->get('/psak413/dashboard');
        $responseZh->assertStatus(200);
        $responseZh->assertSee('PSAK 413 伊斯兰金融准则');
        $responseZh->assertSee('伊斯兰融资减值与担保拨备');

        // 4. Indonesian Locale
        $this->get('/lang/id')->assertRedirect();
        $responseId = $this->actingAs($this->user)->withSession(['locale' => 'id'])->get('/psak413/dashboard');
        $responseId->assertStatus(200);
        $responseId->assertSee('PSAK 413 Syariah');
    }
}
