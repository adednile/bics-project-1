<?php

namespace App\Http\Controllers;

use App\Models\MappedMpesaTransaction;
use App\Models\Contribution;
use App\Models\User;
use App\Services\MpesaSMSParser;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MpesaParserController extends Controller
{
    public function index()
    {
        $transactions = MappedMpesaTransaction::query()->latest()->get();
        $members = User::where('role', 'member')
            ->where('chama_id', Auth::user()->chama_id)
            ->orderBy('name')
            ->get();

        return view('Treasurer.sms-parser', compact('transactions', 'members'));
    }

    public function store(Request $request, MpesaSMSParser $parser)
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $parsed = $parser->parse($data['message']);

        $transaction = MappedMpesaTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $parsed['amount'],
            'sender' => $parsed['sender'],
            'transaction_code' => $parsed['transaction_code'],
            'message' => $parsed['message'],
            'status' => 'unmapped',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'amount' => number_format($parsed['amount'], 2),
                'sender' => $parsed['sender'],
                'transaction_code' => $parsed['transaction_code'],
                'date' => $parsed['date'] ?? now()->toDateString(),
            ]
        ]);
    }

    public function match(MappedMpesaTransaction $tx, Request $request, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($data['user_id']);

        if ($tx->status !== 'unmapped') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction has already been processed.'
            ], 422);
        }

        // Create contribution
        $contribution = Contribution::create([
            'user_id' => $user->id,
            'chama_id' => $user->chama_id,
            'amount' => $tx->amount,
            'contribution_date' => now()->toDateString(),
            'source' => 'mpesa',
            'reference' => $tx->transaction_code,
            'notes' => 'Matched MPesa SMS transaction from ' . $tx->sender,
        ]);

        // Record ledger entry
        $ledgerService->record(
            'contribution',
            $user->id,
            $user->chama_id,
            $tx->amount,
            'Contribution matched from MPesa SMS',
            $tx->transaction_code
        );

        // Update transaction mapping
        $tx->update([
            'status' => 'mapped',
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction successfully matched to ' . $user->name
        ]);
    }

    public function reject(MappedMpesaTransaction $tx)
    {
        if ($tx->status !== 'unmapped') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction has already been processed.'
            ], 422);
        }

        $tx->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction rejected.'
        ]);
    }
}
