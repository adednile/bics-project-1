<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    public function index()
    {
        $fines = Fine::query()
            ->where('chama_id', Auth::user()->chama_id)
            ->latest()
            ->get();

        return view('Treasurer.penalties', compact('fines'));
    }

    public function markPaid(Fine $fine)
    {
        $fine->update([
            'status' => 'paid',
            'paid_at' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Fine marked as paid.');
    }
}
