<?php

namespace App\Http\Controllers;

use App\Models\MappedMpesaTransaction;
use App\Services\MpesaSMSParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MpesaParserController extends Controller
{
    public function index()
    {
        $transactions = MappedMpesaTransaction::query()->latest()->get();

        return view('Treasurer.sms-parser', compact('transactions'));
    }

    public function store(Request $request, MpesaSMSParser $parser)
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $parsed = $parser->parse($data['message']);

        MappedMpesaTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $parsed['amount'],
            'sender' => $parsed['sender'],
            'transaction_code' => $parsed['transaction_code'],
            'message' => $parsed['message'],
            'status' => 'unmapped',
        ]);

        return redirect()->back()->with('success', 'MPesa message parsed.');
    }
}
