<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Group Financial Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500">Total Contributions</p>
                    <p class="text-2xl font-bold text-blue-600">Ksh {{ number_format($contributions->sum('amount'), 2) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500">Total Loans Disbursed</p>
                    <p class="text-2xl font-bold text-green-600">Ksh {{ number_format($loans->sum('amount'), 2) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500">Total Fines Collected</p>
                    <p class="text-2xl font-bold text-red-600">Ksh {{ number_format($fines->where('status', 'paid')->sum('amount'), 2) }}</p>
                </div>
            </div>

            <!-- Member-wise Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Member Summary</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contributions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loans</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fines</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users ?? [] as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ksh {{ number_format($contributions->where('user_id', $user->id)->sum('amount'), 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ksh {{ number_format($loans->where('user_id', $user->id)->sum('amount'), 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ksh {{ number_format($fines->where('user_id', $user->id)->sum('amount'), 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">Ksh {{ number_format($contributions->where('user_id', $user->id)->sum('amount') - $loans->where('user_id', $user->id)->sum('amount'), 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No members found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="{{ route('reports.treasurer') }}?download=pdf" class="btn btn-primary">
    📄 Download Group Report (PDF)
</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>