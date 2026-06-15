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

        foreach ($members as $member) {
            Contribution::create([
                'user_id' => $member->id,
                'chama_id' => $chama->id,
                'amount' => 2000,
                'contribution_date' => now()->subDays(5)->toDateString(),
                'source' => 'manual',
                'reference' => 'demo-contrib',
                'notes' => 'Demo contribution',
            ]);
        }

        $member = $members->first();

        Loan::create([
            'user_id' => $member->id,
            'chama_id' => $chama->id,
            'amount' => 10000,
            'interest_rate' => 5.00,
            'term_months' => 3,
            'approved_amount' => 10000,
            'status' => 'pending',
            'reason' => 'Demo loan application',
        ]);

        Fine::create([
            'user_id' => $member->id,
            'chama_id' => $chama->id,
            'amount' => 100,
            'type' => 'late_contribution',
            'status' => 'pending',
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

        Repayment::create([
            'loan_id' => 1,
            'amount' => 3000,
            'paid_at' => now()->toDateString(),
        ]);
    }
}
