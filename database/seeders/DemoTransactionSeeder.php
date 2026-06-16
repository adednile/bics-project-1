<?php

namespace Database\Seeders;

use App\Models\Chama;
use App\Models\Contribution;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\MappedMpesaTransaction;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $chama = Chama::query()->firstOrFail();
        $members = User::query()->where('role', 'member')->where('chama_id', $chama->id)->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Skipping demo transactions.');
            return;
        }

        foreach ($members as $member) {
            Contribution::create([
                'user_id' => $member->id,
                'chama_id' => $chama->id,
                'amount' => 2000, // ✅ corrected: was 'repayment_amount'
                'contribution_date' => now()->subDays(5)->toDateString(),
                'source' => 'manual',
                'reference' => 'demo-contrib',
                'notes' => 'Demo contribution',
            ]);
        }

        $member = $members->first();

        // Create a loan (status 'pending' so it doesn't affect active loan checks)
        $loan = Loan::create([
            'user_id' => $member->id,
            'chama_id' => $chama->id,
            'amount' => 10000,
            'interest_rate' => 5.00,
            'term_months' => 3,
            'approved_amount' => 10000,
            'status' => 'pending',
            'reason' => 'Demo loan application',
        ]);

        // Create a repayment for the loan (use correct columns)
        Repayment::create([
            'loan_id' => $loan->id, // use the created loan's ID
            'repayment_amount' => 3000,   // ✅ corrected: was 'amount'
            'repayment_date' => now()->toDateString(), // ✅ corrected: was 'paid_at'
            'remaining_balance' => 10000 - 3000,
            'is_late' => false,
        ]);

        Fine::create([
            'user_id' => $member->id,
            'chama_id' => $chama->id,
            'amount' => 100,
            'type' => 'late_contribution',
            'status' => 'unpaid',
            'due_date' => now()->toDateString(),
            'description' => 'Demo fine',
        ]);

        MappedMpesaTransaction::create([
            'user_id' => $member->id,
            'amount' => 2000,
            'sender' => 'John Doe',
            'transaction_code' => 'QWERTY',
            'message' => 'Thank you for buying airtime. Amount: 2000.00 from John Doe. Ref: QWERTY. Date: 2026-06-15',
            'status' => 'unmapped',
        ]);

        $this->command->info('Demo transactions seeded successfully.');
    }
}