<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\MappedMpesaTransaction;
use App\Models\Transaction;
use App\Services\LedgerService;
use App\Services\MpesaSMSParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    public function index()
    {
        $contributions = Contribution::query()
            ->where('chama_id', Auth::user()->chama_id)
            ->latest()
            ->get();

        return view('Member.contributions', compact('contributions'));
    }

    public function store(Request $request, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'contribution_date' => ['required', 'date'],
            'reference' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $contribution = Contribution::create([
            'user_id' => Auth::id(),
            'chama_id' => Auth::user()->chama_id,
            'amount' => round((float) $data['amount'], 2),
            'contribution_date' => $data['contribution_date'],
            'source' => 'manual',
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        $ledgerService->record('contribution', Auth::id(), Auth::user()->chama_id, $contribution->amount, 'Contribution received', $contribution->reference);

        return redirect()->back()->with('success', 'Contribution recorded.');
    }

    public function parseSms(Request $request, MpesaSMSParser $parser, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $parsed = $parser->parse($data['message']);

        $mapped = MappedMpesaTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $parsed['amount'],
            'sender' => $parsed['sender'],
            'transaction_code' => $parsed['transaction_code'],
            'message' => $parsed['message'],
            'status' => 'unmapped',
        ]);

        $ledgerService->record('mpesa_received', Auth::id(), Auth::user()->chama_id, $mapped->amount, 'MPesa SMS parsed', $mapped->transaction_code);

        return redirect()->back()->with('success', 'MPesa SMS captured for review.');
    }
}
