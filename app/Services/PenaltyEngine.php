<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;

class PenaltyEngine
{
    public function applyDailyPenalties(): int
    {
        $users = User::query()->where('role', 'member')->get();
        $count = 0;

        foreach ($users as $user) {
            $lastContribution = Contribution::query()
                ->where('user_id', $user->id)
                ->latest('contribution_date')
                ->first();

            if (! $lastContribution || Carbon::parse($lastContribution->contribution_date)->diffInDays(now()) > 7) {
                Fine::create([
                    'user_id' => $user->id,
                    'chama_id' => $user->chama_id,
                    'amount' => 100,
                    'type' => 'late_contribution',
                    'status' => 'pending',
                    'due_date' => now()->toDateString(),
                    'description' => 'Late contribution penalty',
                ]);
                $count++;
            }

            $activeLoan = Loan::query()
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->latest('created_at')
                ->first();

            if ($activeLoan) {
                $lastRepayment = Repayment::query()->where('loan_id', $activeLoan->id)->latest('paid_at')->first();
                $dueDate = $lastRepayment ? Carbon::parse($lastRepayment->paid_at)->addMonth() : Carbon::parse($activeLoan->created_at)->addMonth();

                if ($dueDate->isPast()) {
                    Fine::create([
                        'user_id' => $user->id,
                        'chama_id' => $user->chama_id,
                        'amount' => 50,
                        'type' => 'late_loan_repayment',
                        'status' => 'pending',
                        'due_date' => $dueDate->toDateString(),
                        'description' => 'Late loan repayment penalty',
                    ]);
                    $count++;
                }
            }
        }

        return $count;
    }
}
