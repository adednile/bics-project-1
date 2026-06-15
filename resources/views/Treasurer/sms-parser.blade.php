<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">SMS Parser</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Paste an MPesa SMS</h3>
                <form method="POST" action="{{ route('treasurer.sms-parser.store') }}" class="space-y-4">
                    @csrf
                    <textarea name="message" rows="5" class="w-full rounded-md border-gray-300" required></textarea>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Parse</button>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Parsed transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Sender</th>
                                <th class="py-2">Amount</th>
                                <th class="py-2">Code</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="border-b">
                                    <td class="py-2">{{ $transaction->sender ?? '-' }}</td>
                                    <td class="py-2">{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="py-2">{{ $transaction->transaction_code ?? '-' }}</td>
                                    <td class="py-2">{{ $transaction->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
