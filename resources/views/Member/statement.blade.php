@extends('layouts.app')
@section('title', 'My Financial Statement')

@section('content')
<div class="space-y-6">

    {{-- Header Card --}}
    <div class="premium-card rounded-2xl p-6 flex items-center justify-between flex-wrap gap-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        <div>
            <h3 class="text-base font-bold font-title text-white">Statement for {{ $user->name }}</h3>
            <p class="text-xs text-slate-400 mt-1">Audit log of your savings contributions, active loans, and overdue fines.</p>
        </div>
        <a href="{{ route('reports.member', $user) }}?download=pdf" class="gold-gradient-btn px-6 py-2.5 rounded-xl font-bold flex items-center gap-1.5 text-xs shadow-md">
            <span class="material-symbols-outlined text-sm">picture_as_pdf</span> Download Statement (PDF)
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="premium-card p-5 rounded-2xl border-l-4 border-blue-500 text-center">
            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Savings</span>
            <p class="text-xl font-title font-black text-white mt-1">Ksh {{ number_format($contributions->sum('amount'), 2) }}</p>
        </div>
        <div class="premium-card p-5 rounded-2xl border-l-4 border-emerald-500 text-center">
            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Loans</span>
            <p class="text-xl font-title font-black text-brand-emerald mt-1">Ksh {{ number_format($loans->sum('amount'), 2) }}</p>
        </div>
        <div class="premium-card p-5 rounded-2xl border-l-4 border-brand-rose text-center">
            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Fines</span>
            <p class="text-xl font-title font-black text-brand-rose mt-1">Ksh {{ number_format($fines->sum('amount'), 2) }}</p>
        </div>
        <div class="premium-card p-5 rounded-2xl border-l-4 border-gold-500 text-center">
            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Current Balance</span>
            <p class="text-xl font-title font-black text-gold-500 mt-1">Ksh {{ number_format($transactions->sum('amount'), 2) }}</p>
        </div>
    </div>

    {{-- History Table --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5">
            <h3 class="text-sm font-bold font-title text-white">Full Transaction Audit Ledger</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Transaction Date</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($transactions ?? [] as $tx)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tx->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColor = $tx->type === 'credit' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 'text-brand-rose bg-brand-rose/10 border-brand-rose/20';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $typeColor }}">
                                {{ ucfirst($tx->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ $tx->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-white whitespace-nowrap">
                            {{ $tx->type === 'credit' ? '+' : '-' }} Ksh {{ number_format($tx->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">receipt</span>
                            No transactions posted to your account.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection