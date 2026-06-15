<?php

namespace App\Services;

class CreditScoringEngine
{
    public function score(array $data): int
    {
        $savingsConsistency = (float) ($data['savings_consistency'] ?? 0);
        $repaymentHistory = (float) ($data['repayment_history'] ?? 0);
        $attendance = (float) ($data['attendance'] ?? 0);
        $membershipDuration = (float) ($data['membership_duration'] ?? 0);

        $score = ($savingsConsistency * 0.4)
            + ($repaymentHistory * 0.3)
            + ($attendance * 0.2)
            + ($membershipDuration * 0.1);

        return (int) round(min(max($score, 0), 10));
    }
}
