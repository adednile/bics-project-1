<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Repayment;
use App\Services\CreditScoringEngine;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::query()
            ->where('chama_id', Auth::user()->chama_id)
            ->latest()
            ->get();

        return view('Member.loan-application', compact('loans'));
    }

    public function store(Request $request, CreditScoringEngine $scoringEngine, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string'],
            'term_months' => ['required', 'integer', 'min:1'],
        ]);

        $score = $scoringEngine->score([
            'savings_consistency' => 8,
            'repayment_history' => 7,
            'attendance' => 9,
            'membership_duration' => 6,
        ]);

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'chama_id' => Auth::user()->chama_id,
            'amount' => round((float) $data['amount'], 2),
            'interest_rate' => 5.00,
            'term_months' => (int) $data['term_months'],
            'approved_amount' => $score >= 7 ? round((float) $data['amount'], 2) : 0,
            'status' => $score >= 7 ? 'approved' : 'pending',
            'reason' => $data['reason'],
            'approved_at' => $score >= 7 ? now() : null,
        ]);

        if ($loan->status === 'approved') {
            $ledgerService->record('loan_approved', Auth::id(), Auth::user()->chama_id, $loan->approved_amount, 'Loan approved', $loan->id);
        }

        return redirect()->back()->with('success', 'Loan application submitted.');
    }

    public function approve(Loan $loan)
    {
        $loan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_amount' => $loan->amount,
        ]);

        return redirect()->back()->with('success', 'Loan approved.');
    }

    public function repay(Loan $loan, Request $request, LedgerService $ledgerService)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        Repayment::create([
            'loan_id' => $loan->id,
            'amount' => round((float) $data['amount'], 2),
            'paid_at' => now()->toDateString(),
        ]);

        $ledgerService->record('repayment', Auth::id(), Auth::user()->chama_id, (float) $data['amount'], 'Loan repayment', $loan->id);

        return redirect()->back()->with('success', 'Repayment recorded.');
    }
}
