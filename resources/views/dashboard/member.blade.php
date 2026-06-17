@extends('layouts.app')
@section('title', 'Member Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Fine Alert --}}
    @if(($unpaidFines ?? 0) > 0)
    <div class="bg-rose-500/10 border-l-4 border-brand-rose text-rose-300 p-4 rounded-r-xl flex items-center justify-between animate-pulse">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-brand-rose">warning</span>
            <p class="text-sm font-medium">You have unpaid fines of <strong>Ksh {{ number_format($unpaidFines, 2) }}</strong>. Please clear this balance to remain eligible for loans.</p>
        </div>
        <a href="{{ route('member.loans') }}" class="text-xs text-gold-500 font-bold hover:underline">Clear Fines</a>
    </div>
    @endif

    {{-- Summary Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="premium-card rounded-2xl p-5 border-l-4 border-blue-500 flex flex-col justify-between">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Savings Balance</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-2xl font-title font-black text-white">Ksh {{ number_format($savingsBalance ?? 0, 2) }}</span>
                <span class="text-[10px] text-brand-emerald bg-brand-emerald/10 border border-brand-emerald/20 px-2 py-0.5 rounded-full font-bold">+12%</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-gold-500 flex flex-col justify-between">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Loan Limit</span>
            <div class="mt-2">
                <span class="text-2xl font-title font-black text-gold-500">Ksh {{ number_format($loanLimit ?? 0, 2) }}</span>
                <span class="text-[9px] text-slate-500 block mt-1">3x of your savings pool</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-purple-500 flex flex-col justify-between">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Outstanding Loan</span>
            <div class="mt-2">
                <span class="text-2xl font-title font-black text-purple-400">Ksh {{ number_format($outstandingLoan ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-brand-rose flex flex-col justify-between">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unpaid Fines</span>
            <div class="mt-2">
                <span class="text-2xl font-title font-black text-brand-rose">Ksh {{ number_format($unpaidFines ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Credit Score & Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Credit Score Gauge --}}
        <div class="premium-card p-6 rounded-2xl relative overflow-hidden flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Calculated Credit Score</span>
                <p class="text-xs text-slate-500 mt-0.5">Based on repayments, attendance, and savings patterns.</p>
            </div>
            
            <div class="my-6 flex flex-col items-center justify-center relative">
                @php
                    $score = $creditScore ?? 0;
                    $scoreColor = $score >= 7 ? 'text-brand-emerald' : ($score >= 5 ? 'text-gold-500' : 'text-brand-rose');
                    $scoreBg = $score >= 7 ? 'bg-brand-emerald/10 border-brand-emerald/20' : ($score >= 5 ? 'bg-gold-500/10 border-gold-500/20' : 'bg-brand-rose/10 border-brand-rose/20');
                    $scoreStatus = $score >= 7 ? 'Excellent' : ($score >= 5 ? 'Good' : 'Needs Work');
                @endphp
                <div class="w-32 h-32 rounded-full border-4 border-slate-800 flex flex-col items-center justify-center">
                    <span class="text-4xl font-title font-black text-white leading-none">{{ number_format($score, 1) }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase mt-1">/ 10</span>
                </div>
                <div class="mt-4 px-3 py-1 rounded-full text-xs font-bold border {{ $scoreColor }} {{ $scoreBg }}">
                    {{ $scoreStatus }}
                </div>
            </div>

            <p class="text-[11px] text-slate-500 text-center">Maintain consistency to maximize your limits.</p>
        </div>

        {{-- Quick Actions --}}
        <div class="lg:col-span-2 premium-card p-6 rounded-2xl flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold font-title text-white mb-4">Quick Financial Actions</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('member.contributions') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                    <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-gold-500 mb-2">add_circle</span>
                    <span class="text-xs font-semibold text-slate-300">Add Contribution</span>
                </a>
                <button @if($canApplyForLoan ?? false) onclick="window.location.href='{{ route('member.loans') }}'" @else disabled @endif
                        class="flex flex-col items-center justify-center p-4 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center w-full {{ ($canApplyForLoan ?? false) ? '' : 'opacity-40 cursor-not-allowed' }}">
                    <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-gold-500 mb-2">account_balance_wallet</span>
                    <span class="text-xs font-semibold text-slate-300">Apply for Loan</span>
                </button>
                <a href="{{ route('member.loans') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                    <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-gold-500 mb-2">receipt_long</span>
                    <span class="text-xs font-semibold text-slate-300">My Loans</span>
                </a>
                <a href="{{ route('reports.member', auth()->user()) }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                    <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-gold-500 mb-2">download</span>
                    <span class="text-xs font-semibold text-slate-300">Get Statement</span>
                </a>
            </div>
            
            <button onclick="openSmsModal()" class="w-full mt-4 py-3 bg-brand-emerald text-brand-navy hover:opacity-90 font-bold rounded-xl transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm font-bold">send</span> Lipa na M-Pesa (SMS Parser)
            </button>
        </div>
    </div>

    {{-- Active Loan Tracker --}}
    @if($activeLoan ?? false)
    <div class="premium-card p-6 rounded-2xl">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold font-title text-white">Active Loan Tracker</h3>
                <p class="text-xs text-slate-500 mt-1">Loan Account ID: #{{ $activeLoan->id }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400 block">Remaining Balance</span>
                <span class="text-lg font-bold text-gold-500">Ksh {{ number_format($activeLoan->outstanding_balance ?? 0, 2) }}</span>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-slate-400">Repayment Progress</span>
                @php
                    $totalDues = $activeLoan->amount * (1 + ($activeLoan->interest_rate / 100));
                    $repaidDues = max($totalDues - $activeLoan->outstanding_balance, 0);
                    $progress = $totalDues > 0 ? min(round(($repaidDues / $totalDues) * 100), 100) : 0;
                @endphp
                <span class="font-bold text-gold-500">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2">
                <div class="bg-gradient-to-r from-gold-500 to-gold-700 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-white/5 text-xs">
            <span class="flex items-center gap-1.5 text-slate-400">
                <span class="material-symbols-outlined text-sm text-gold-500">calendar_month</span> 
                Maturity Date: <strong class="text-white">{{ $activeLoan->maturity_date ? \Carbon\Carbon::parse($activeLoan->maturity_date)->format('d/m/Y') : 'N/A' }}</strong>
            </span>
            <span class="text-slate-400 bg-white/5 border border-white/10 px-3 py-1 rounded-full">
                Interest Rate: <strong class="text-gold-500">{{ number_format($activeLoan->interest_rate, 2) }}%</strong>
            </span>
        </div>
    </div>
    @endif

    {{-- Recent Transactions --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5">
            <h3 class="text-sm font-bold font-title text-white">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Transaction Date</th>
                        <th class="px-6 py-4">Transaction Type</th>
                        <th class="px-6 py-4">Reference/Code</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($recentTransactions ?? [] as $transaction)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4">{{ $transaction->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $typeColor = $transaction->type === 'contribution' || $transaction->type === 'repayment' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 'text-gold-500 bg-gold-500/10 border-gold-500/20';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $typeColor }}">
                                {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $transaction->reference ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $transaction->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-white">
                            Ksh {{ number_format($transaction->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">receipt</span>
                            No transactions recorded.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('partials.sms-modal')
@endsection