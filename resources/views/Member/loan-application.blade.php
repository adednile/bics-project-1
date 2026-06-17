@extends('layouts.app')
@section('title', 'Loan Application & Calculator')

@section('content')
<div class="space-y-6">

    {{-- Form Card --}}
    <div class="premium-card rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        <h3 class="text-base font-bold font-title text-white mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-gold-500">edit_note</span> Apply for a Loan
        </h3>
        
        <form method="POST" action="{{ route('member.loans.store') }}" x-data="loanCalculator()" x-init="init()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Requested Principal (Ksh)</label>
                    <input type="number" step="0.01" name="amount" id="amount" x-model="amount" x-on:input="calculate()" required 
                        class="w-full h-12 px-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all"
                        placeholder="e.g. 20000">
                </div>
                <div>
                    <label for="term_months" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Repayment Period</label>
                    <select name="term_months" id="term_months" x-model="term" x-on:change="calculate()" 
                        class="w-full h-12 px-4 rounded-xl bg-[#151d30] border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all">
                        <option value="1">1 Month</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12" selected>12 Months</option>
                        <option value="18">18 Months</option>
                        <option value="24">24 Months</option>
                        <option value="36">36 Months</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Purpose for Loan</label>
                <textarea name="reason" id="reason" rows="2" required 
                    class="w-full p-4 rounded-xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all" 
                    placeholder="Briefly state the reason or utility of this loan request..."></textarea>
            </div>

            {{-- EMI Calculation Preview --}}
            <div class="mt-6 p-6 bg-brand-navy/60 border border-white/5 rounded-xl text-slate-300" x-show="amount > 0 && term > 0" x-cloak>
                <h4 class="font-bold text-sm mb-4 flex items-center gap-1 text-white font-title">
                    <span class="material-symbols-outlined text-gold-500 text-base">calculate</span> Calculated Product Summary
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/5 p-4 rounded-xl text-center border border-white/5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Monthly Repayment (EMI)</span>
                        <p class="text-xl font-title font-black text-gold-500 mt-1" x-text="'Ksh ' + monthly_repayment.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl text-center border border-white/5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Interest Charged (5%)</span>
                        <p class="text-xl font-title font-black text-rose-400 mt-1" x-text="'Ksh ' + total_interest.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl text-center border border-white/5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Repayment Amount</span>
                        <p class="text-xl font-title font-black text-brand-emerald mt-1" x-text="'Ksh ' + total_repayment.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <input type="checkbox" id="terms" required class="w-4.5 h-4.5 bg-white/5 border-white/10 rounded text-gold-500 focus:ring-gold-500/30">
                <label for="terms" class="text-xs text-slate-400">I confirm that all inputted details are valid and I consent to the terms of the <a href="#" class="text-gold-500 underline hover:text-gold-600">Kenyan Chama Bylaws Agreement</a>.</label>
            </div>

            <div class="mt-6">
                <button type="submit" class="gold-gradient-btn px-6 py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 text-sm shadow-md">
                    <span class="material-symbols-outlined text-sm font-bold">send</span> Apply for Disbursement
                </button>
            </div>
        </form>
    </div>

    {{-- Loan History List --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5">
            <h3 class="text-sm font-bold font-title text-white">Your Loan Request Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Application Date</th>
                        <th class="px-6 py-4">Principal Amount</th>
                        <th class="px-6 py-4">Current Status</th>
                        <th class="px-6 py-4">Term Period</th>
                        <th class="px-6 py-4 text-right">Repayment Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($loans ?? [] as $loan)
                    <tr class="hover:bg-white/5 transition align-middle">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-bold text-white whitespace-nowrap">Ksh {{ number_format($loan->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = $loan->status === 'active' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 
                                               ($loan->status === 'pending' ? 'text-gold-500 bg-gold-500/10 border-gold-500/20' : 
                                               ($loan->status === 'completed' ? 'text-slate-400 bg-white/5 border border-white/10' : 'text-brand-rose bg-brand-rose/10 border-brand-rose/20'));
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->term_months }} months</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if($loan->status === 'active')
                                <form method="POST" action="{{ route('member.loans.repay', $loan) }}" class="inline-flex items-center gap-2 justify-end">
                                    @csrf
                                    <input type="number" name="amount" placeholder="Amount" required
                                        class="w-28 h-9 px-3 rounded-xl bg-white/5 border border-white/10 focus:ring-1 focus:ring-gold-500 focus:border-gold-500 outline-none text-xs text-white">
                                    <button type="submit" class="bg-brand-emerald text-brand-navy px-3.5 py-1.5 rounded-xl text-xs font-bold transition hover:opacity-90">
                                        Repay
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-500 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">receipt_long</span>
                            No loan application history found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loanCalculator() {
        return {
            amount: 0,
            term: 12,
            monthly_repayment: 0,
            total_interest: 0,
            total_repayment: 0,
            calculate() {
                if (this.amount > 0 && this.term > 0) {
                    const principal = parseFloat(this.amount);
                    const months = parseInt(this.term);
                    const rate = 0.05; // 5% annual
                    const monthlyRate = rate / 12;
                    const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
                    this.monthly_repayment = emi || 0;
                    this.total_repayment = emi * months;
                    this.total_interest = this.total_repayment - principal;
                } else {
                    this.monthly_repayment = 0;
                    this.total_interest = 0;
                    this.total_repayment = 0;
                }
            },
            init() { this.calculate(); }
        }
    }
</script>
@endpush
@endsection