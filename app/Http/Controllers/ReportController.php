<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function memberStatement(User $user)
    {
        $contributions = Contribution::query()->where('user_id', $user->id)->get();
        $loans = Loan::query()->where('user_id', $user->id)->get();
        $fines = Fine::query()->where('user_id', $user->id)->get();
        $transactions = Transaction::query()->where('user_id', $user->id)->latest()->get();

        return view('Member.statement', compact('user', 'contributions', 'loans', 'fines', 'transactions'));
    }

    public function treasurerReports()
    {
        $users = User::query()->where('chama_id', Auth::user()->chama_id)->get();
        $contributions = Contribution::query()->where('chama_id', Auth::user()->chama_id)->get();
        $loans = Loan::query()->where('chama_id', Auth::user()->chama_id)->get();
        $fines = Fine::query()->where('chama_id', Auth::user()->chama_id)->get();

        return view('Treasurer.reports', compact('users', 'contributions', 'loans', 'fines'));
    }
}
