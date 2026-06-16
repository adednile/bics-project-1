<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Personal Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500">Savings Balance</div>
                    <div class="text-2xl font-bold text-blue-600">Ksh {{ number_format($savingsBalance ?? 0, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500">Loan Limit</div>
                    <div class="text-2xl font-bold text-green-600">Ksh {{ number_format($loanLimit ?? 0, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500">Outstanding Loan</div>
                    <div class="text-2xl font-bold text-red-600">Ksh {{ number_format($outstandingLoan ?? 0, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500">Unpaid Fines</div>
                    <div class="text-2xl font-bold text-yellow-600">Ksh {{ number_format($unpaidFines ?? 0, 2) }}</div>
                </div>
            </div>
<!-- Quick Actions -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h3 class="font-semibold text-lg">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('member.contributions') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                ➕ Add Contribution
            </a>
            <button
                @if($canApplyForLoan ?? false)
                    onclick="window.location.href='{{ route('member.loans') }}'"
                @else
                    disabled
                    title="{{ $loanIneligibilityReason ?? 'You have unpaid fines or an active loan' }}"
                @endif
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md {{ ($canApplyForLoan ?? false) ? '' : 'opacity-50 cursor-not-allowed' }}"
            >
                📝 Apply for Loan
            </button>
            <!-- ✅ NEW: My Loans Button -->
            <a href="{{ route('member.loans') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                💰 My Loans
            </a>
            <a href="{{ route('reports.member', auth()->user()) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                📄 Download Statement
            </a>
        </div>
    </div>
    @if(!($canApplyForLoan ?? true))
        <p class="text-sm text-red-600 mt-2">⚠️ {{ $loanIneligibilityReason ?? 'You cannot apply for a loan at this time.' }}</p>
    @endif
</div>

            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Recent Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTransactions ?? [] as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->description ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }} Ksh {{ number_format($transaction->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No transactions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Loan Tracker -->
            @if($activeLoan ?? false)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-8">
                    <h3 class="font-semibold text-lg mb-4">Active Loan Tracker</h3>
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-medium">Loan #{{ $activeLoan->id }}</p>
                        <p class="text-sm text-gray-600">Amount: Ksh {{ number_format($activeLoan->amount, 2) }}</p>
                        <p class="text-sm text-gray-600">Remaining: Ksh {{ number_format($activeLoan->remaining_balance ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-600">Next Due: {{ $activeLoan->next_due_date ?? 'N/A' }}</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $activeLoan->repayment_progress ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $activeLoan->repayment_progress ?? 0 }}% repaid</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>