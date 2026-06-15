<?php

namespace App\Console\Commands;

use App\Services\PenaltyEngine;
use Illuminate\Console\Command;

class CalculateDailyPenalties extends Command
{
    protected $signature = 'chama:penalties';

    protected $description = 'Apply daily penalties for late contributions and loan repayments';

    public function handle(PenaltyEngine $penaltyEngine): int
    {
        $count = $penaltyEngine->applyDailyPenalties();

        $this->info("Created {$count} penalty records.");

        return self::SUCCESS;
    }
}
