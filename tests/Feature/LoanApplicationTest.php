<?php

namespace Tests\Feature;

use App\Models\Chama;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_loan_application_page(): void
    {
        $chama = Chama::create([
            'name' => 'Gold Chama',
            'min_credit_score' => 5.0,
        ]);
        $user = User::factory()->create([
            'role' => 'member',
            'chama_id' => $chama->id,
        ]);

        $response = $this->actingAs($user)->get('/member/loans');

        $response->assertStatus(200);
        $response->assertSee('Apply for a Loan');
    }

    public function test_member_can_apply_for_loan_successfully(): void
    {
        $chama = Chama::create([
            'name' => 'Gold Chama',
            'min_credit_score' => 1.0,
        ]);
        $user = User::factory()->create([
            'role' => 'member',
            'chama_id' => $chama->id,
        ]);

        $response = $this->actingAs($user)->post('/member/loans', [
            'amount' => 5000,
            'term_months' => 12,
            'reason' => 'Business setup',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'amount' => 5000.00,
            'status' => 'pending',
        ]);
    }
}
