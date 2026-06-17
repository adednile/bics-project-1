@extends('layouts.app')
@section('title', 'My Contributions')

@section('content')
<div class="space-y-6">

    {{-- Form Card --}}
    <div class="premium-card rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        <h3 class="text-base font-bold font-title text-white mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-gold-500">add_circle</span> Record New Savings Contribution
        </h3>
        <form method="POST" action="{{ route('member.contributions.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label for="amount" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Amount (Ksh)</label>
                <input type="number" step="0.01" name="amount" id="amount" required 
                    class="w-full h-12 px-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all"
                    placeholder="e.g. 5000">
            </div>
            <div>
                <label for="contribution_date" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Contribution Date</label>
                <input type="date" name="contribution_date" id="contribution_date" value="{{ date('Y-m-d') }}" required 
                    class="w-full h-12 px-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all">
            </div>
            <div>
                <label for="reference" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Transaction Reference (Optional)</label>
                <input type="text" name="reference" id="reference" 
                    class="w-full h-12 px-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all" 
                    placeholder="e.g. M-Pesa Code">
            </div>
            <div class="md:col-span-3">
                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Transaction Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="2" 
                    class="w-full p-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all"
                    placeholder="Enter additional transaction notes..."></textarea>
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="gold-gradient-btn px-6 py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 text-sm shadow-md">
                    <span class="material-symbols-outlined text-sm font-bold">save</span> Post Contribution Record
                </button>
            </div>
        </form>
    </div>

    {{-- History Table --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5">
            <h3 class="text-sm font-bold font-title text-white">Contribution Ledger History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Posting Date</th>
                        <th class="px-6 py-4">Transaction Code</th>
                        <th class="px-6 py-4">Funding Source</th>
                        <th class="px-6 py-4">Notes</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($contributions ?? [] as $contribution)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4">{{ $contribution->contribution_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-gold-500">{{ $contribution->reference ?? 'Manual Reconcile' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $sourceColor = $contribution->source === 'mpesa' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 'text-slate-400 bg-white/5 border border-white/10';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $sourceColor }}">
                                {{ ucfirst($contribution->source) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ $contribution->notes ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-white">
                            Ksh {{ number_format($contribution->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">payments</span>
                            No contribution history posted.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection