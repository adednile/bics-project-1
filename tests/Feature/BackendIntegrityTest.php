<?php

namespace Tests\Feature;

use App\Models\Chama;
use App\Models\User;
use App\Models\Loan;
use App\Models\Contribution;
use App\Models\Repayment;
use App\Models\Fine;
use App\Models\Transaction;
use App\Services\LedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackendIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private Chama $chama;
    private User $member;
    private User $treasurer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chama = Chama::create([
            'name' => 'Test Chama',
            'min_credit_score' => 5.0,
            'interest_rate_pct' => 5.0,
            'savings_weight' => 0.40,
            'attendance_weight' => 0.20,
            'repayment_weight' => 0.40,
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'chama_id' => $this->chama->id,
            'account_status' => 'active',
        ]);

        $this->treasurer = User::factory()->create([
            'role' => 'treasurer',
            'chama_id' => $this->chama->id,
            'account_status' => 'active',
        ]);
    }

    public function test_treasurer_blocked_from_member_routes(): void
    {
        $response = $this->actingAs($this->treasurer)->get('/member/contributions');
        $response->assertStatus(403);

        $response = $this->actingAs($this->treasurer)->get('/member/loans');
        $response->assertStatus(403);
    }

    public function test_member_blocked_from_treasurer_routes(): void
    {
        $response = $this->actingAs($this->member)->get('/treasurer/sms-parser');
        $response->assertStatus(403);

        $response = $this->actingAs($this->member)->get('/treasurer/penalties');
        $response->assertStatus(403);

        $response = $this->actingAs($this->member)->get('/treasurer/chama/config');
        $response->assertStatus(403);
    }

    public function test_treasurer_can_update_group_configuration(): void
    {
        $response = $this->actingAs($this->treasurer)->post('/treasurer/chama/config', [
            'contribution_target' => 2000.00,
            'collection_cutoff' => '2026-07-10',
            'late_penalty_flat' => 100.00,
            'interest_rate_pct' => 8.50,
            'min_credit_score' => 6.0,
            'savings_weight' => 0.35,
            'attendance_weight' => 0.25,
            'repayment_weight' => 0.40,
        ]);

        $response->assertRedirect();
        $this->chama->refresh();

        $this->assertEquals(2000.00, $this->chama->contribution_target);
        $this->assertEquals('2026-07-10', $this->chama->collection_cutoff);
        $this->assertEquals(100.00, $this->chama->late_penalty_flat);
        $this->assertEquals(8.50, $this->chama->interest_rate_pct);
        $this->assertEquals(6.0, $this->chama->min_credit_score);
        $this->assertEquals(0.35, $this->chama->savings_weight);
    }

    public function test_loan_request_blocked_if_status_is_overdue(): void
    {
        $this->member->update(['account_status' => 'overdue']);

        $response = $this->actingAs($this->member)->post('/member/loans', [
            'amount' => 5000,
            'term_months' => 6,
            'reason' => 'School fees',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('loans', [
            'user_id' => $this->member->id,
            'amount' => 5000.00,
        ]);
    }

    public function test_ledger_verify_double_entry_balance(): void
    {
        $ledger = new LedgerService();

        // 1. Balanced Scenario: User makes KES 1000 contribution, recorded in ledger and contribution
        Contribution::create([
            'user_id' => $this->member->id,
            'chama_id' => $this->chama->id,
            'amount' => 1000.00,
            'contribution_date' => '2026-06-20',
            'source' => 'manual',
        ]);

        $ledger->record('contribution', $this->member->id, $this->chama->id, 1000.00);

        // Audit should pass (both computed and ledger shows +1000 net)
        $this->assertTrue($ledger->verifyLedgerIntegrity($this->chama->id));

        // 2. Unbalanced Scenario: Force mismatch by altering transaction record directly
        Transaction::where('chama_id', $this->chama->id)->first()->update(['amount' => 800.00]);

        // Audit should fail due to variance
        $this->assertFalse($ledger->verifyLedgerIntegrity($this->chama->id));
    }
}
