@extends('layouts.app')
@section('title', 'Treasurer Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Summary Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="premium-card rounded-2xl p-5 border-l-4 border-blue-500 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Savings Pool</span>
                <span class="material-symbols-outlined text-blue-400 text-lg">payments</span>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <span class="text-2xl font-title font-black text-white">Ksh {{ number_format($totalSavings ?? 0, 2) }}</span>
                <span class="text-[10px] text-brand-emerald bg-brand-emerald/10 border border-brand-emerald/20 px-2 py-0.5 rounded-full font-bold">+12%</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-brand-emerald flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Disbursed Loans</span>
                <span class="material-symbols-outlined text-brand-emerald text-lg">account_balance</span>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-title font-black text-brand-emerald">Ksh {{ number_format($activeLoans ?? 0, 2) }}</span>
                <span class="text-xs text-slate-500 block mt-1">{{ $activeLoansCount ?? 0 }} active obligations</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-gold-500 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending App Approvals</span>
                <span class="material-symbols-outlined text-gold-500 text-lg">pending_actions</span>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-title font-black text-gold-500">{{ $pendingApplications ?? 0 }}</span>
                <span class="text-xs text-slate-500 block mt-1">Require administrative review</span>
            </div>
        </div>
        <div class="premium-card rounded-2xl p-5 border-l-4 border-brand-rose flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Outstanding Fines</span>
                <span class="material-symbols-outlined text-brand-rose text-lg">gavel</span>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-title font-black text-brand-rose">Ksh {{ number_format($totalFines ?? 0, 2) }}</span>
                <span class="text-xs text-slate-500 block mt-1">{{ $unpaidFinesCount ?? 0 }} overdue unpaid records</span>
            </div>
        </div>
    </div>

    {{-- Pending Loan Approvals & Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Pending Approvals List --}}
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-sm font-bold font-title text-white tracking-wide">Pending Loan Queue</h4>
                <a href="{{ route('treasurer.loans.pending') }}" class="text-xs font-bold text-gold-500 hover:underline">View Queue Details</a>
            </div>
            <div class="premium-card rounded-2xl overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                        <tr>
                            <th class="px-4 py-3">Applicant</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Score</th>
                            <th class="px-4 py-3">Term</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-300">
                        @forelse(($pendingLoanList ?? []) as $loan)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-4 py-3 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gold-500/10 border border-gold-500/20 text-gold-500 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                </div>
                                <span class="font-medium">{{ $loan->user->name }}</span>
                            </td>
                            <td class="px-4 py-3 font-bold">Ksh {{ number_format($loan->amount, 2) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $score = $loan->credit_score ?? 0;
                                    $scoreColor = $score >= 7 ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 
                                                 ($score >= 5 ? 'text-gold-500 bg-gold-500/10 border-gold-500/20' : 'text-brand-rose bg-brand-rose/10 border-brand-rose/20');
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold border {{ $scoreColor }}">
                                    {{ $score }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $loan->term_months }} mo</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <form method="POST" action="{{ route('treasurer.loans.approve', $loan) }}" class="inline" onsubmit="return confirm('Approve this loan?');">
                                    @csrf
                                    <button type="submit" class="text-brand-emerald hover:bg-brand-emerald/10 p-1.5 rounded-lg border border-white/5 bg-white/5 transition inline-flex items-center">
                                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                                    </button>
                                </form>
                                <a href="{{ route('treasurer.loans.pending') }}" class="text-brand-rose hover:bg-brand-rose/10 p-1.5 rounded-lg border border-white/5 bg-white/5 transition inline-flex items-center">
                                    <span class="material-symbols-outlined text-sm font-bold">close</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                <span class="material-symbols-outlined text-2xl block mb-2 opacity-50">verified_user</span>
                                No loan applications pending review.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h4 class="text-sm font-bold font-title text-white mb-4">Quick Admin Actions</h4>
            <div class="premium-card p-5 rounded-2xl space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('member.contributions') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                        <span class="material-symbols-outlined text-xl text-slate-400 group-hover:text-gold-500 mb-1">add_circle</span>
                        <span class="text-[10px] font-semibold text-slate-300">Add Savings</span>
                    </a>
                    <button onclick="openSmsModal()" class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center w-full">
                        <span class="material-symbols-outlined text-xl text-slate-400 group-hover:text-gold-500 mb-1">sms</span>
                        <span class="text-[10px] font-semibold text-slate-300">Parse SMS</span>
                    </button>
                    <a href="{{ route('treasurer.loans.pending') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                        <span class="material-symbols-outlined text-xl text-slate-400 group-hover:text-gold-500 mb-1">request_quote</span>
                        <span class="text-[10px] font-semibold text-slate-300">Loan Queue</span>
                    </a>
                    <a href="{{ route('reports.treasurer') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/5 bg-white/5 hover:border-gold-500/30 hover:bg-white/10 transition group text-center">
                        <span class="material-symbols-outlined text-xl text-slate-400 group-hover:text-gold-500 mb-1">picture_as_pdf</span>
                        <span class="text-[10px] font-semibold text-slate-300">Run Reports</span>
                    </a>
                </div>
                
                <button onclick="openSmsModal()" class="w-full py-2.5 bg-brand-emerald text-brand-navy hover:opacity-90 font-bold rounded-xl text-xs transition flex items-center justify-center gap-1.5 shadow-md">
                    <span class="material-symbols-outlined text-sm font-bold">account_balance_wallet</span> Map M-Pesa SMS
                </button>
            </div>
        </div>
    </div>

    {{-- Recent Transactions Logs --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-sm font-bold font-title text-white">Chama Master Transaction Log</h3>
            <div class="flex gap-2">
                <input type="text" id="logSearch" placeholder="Search logs..." class="text-xs bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-gold-500 focus:border-gold-500 outline-none text-white w-48">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="logTable">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Post Date</th>
                        <th class="px-6 py-4">Chama Member</th>
                        <th class="px-6 py-4">Transaction Type</th>
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($recentTransactions ?? [] as $transaction)
                    <tr class="hover:bg-white/5 transition search-row">
                        <td class="px-6 py-4">{{ $transaction->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-semibold text-white">{{ $transaction->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-xs font-bold uppercase">
                            @php
                                $typeColor = $transaction->type === 'contribution' || $transaction->type === 'repayment' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 'text-gold-500 bg-gold-500/10 border-gold-500/20';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full border {{ $typeColor }}">
                                {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $transaction->reference ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-white">
                            Ksh {{ number_format($transaction->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">account_balance_wallet</span>
                            No transactions posted yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('partials.sms-modal')

@push('scripts')
<script>
    document.getElementById('logSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#logTable .search-row');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection