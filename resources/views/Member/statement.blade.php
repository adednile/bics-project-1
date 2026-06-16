<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Financial Statement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-lg">Statement for {{ $user->name }}</h3>
                    <a href="{{ route('reports.member', $user) }}?download=pdf" class="btn btn-primary">
    📄 Download PDF Statement
</a>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-500">Total Contributions</p>
                        <p class="text-xl font-bold text-blue-600">Ksh {{ number_format($contributions->sum('amount'), 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-500">Total Loans</p>
                        <p class="text-xl font-bold text-green-600">Ksh {{ number_format($loans->sum('amount'), 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-500">Total Fines</p>
                        <p class="text-xl font-bold text-red-600">Ksh {{ number_format($fines->sum('amount'), 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-500">Current Balance</p>
                        <p class="text-xl font-bold text-purple-600">Ksh {{ number_format($transactions->sum('amount'), 2) }}</p>
                    </div>
                </div>

                <!-- Transaction Details -->
                <h4 class="font-semibold text-md mb-3">Transaction History</h4>
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
                            @forelse($transactions ?? [] as $tx)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $tx->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($tx->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $tx->description ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tx->type === 'credit' ? '+' : '-' }} Ksh {{ number_format($tx->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>