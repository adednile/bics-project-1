<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class LedgerService
{
    public function record(string $type, int $userId, int $chamaId, float $amount, ?string $description = null, ?string $reference = null): Transaction
    {
        return DB::transaction(function () use ($type, $userId, $chamaId, $amount, $description, $reference): Transaction {
            $transaction = Transaction::create([
                'user_id' => $userId,
                'chama_id' => $chamaId,
                'type' => $type,
                'amount' => round($amount, 2),
                'description' => $description,
                'reference' => $reference,
                'posted_at' => now(),
            ]);

            return $transaction;
        });
    }

    public function balance(int $userId, int $chamaId): float
    {
        $credit = Transaction::query()
            ->where('user_id', $userId)
            ->where('chama_id', $chamaId)
            ->whereIn('type', ['contribution', 'loan_approved', 'fine_paid'])
            ->sum('amount');

        $debit = Transaction::query()
            ->where('user_id', $userId)
            ->where('chama_id', $chamaId)
            ->whereIn('type', ['loan_disbursement', 'fine_assessed', 'repayment'])
            ->sum('amount');

        return round((float) $credit - (float) $debit, 2);
    }
}