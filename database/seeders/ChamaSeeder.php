<?php

namespace Database\Seeders;

use App\Models\Chama;
use Illuminate\Database\Seeder;

class ChamaSeeder extends Seeder
{
    public function run(): void
    {
        Chama::firstOrCreate([
            'name' => 'Mwangaza Chama',
        ], [
            'location' => 'Nairobi',
            'description' => 'Demo chama for the financial system',
            'currency' => 'KES',
        ]);
    }
}
