<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Fines</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Pending fines</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Member</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                            <tr class="border-b">
                                <td class="py-2">{{ $fine->user->name ?? '-' }}</td>
                                <td class="py-2">{{ $fine->type }}</td>
                                <td class="py-2">{{ number_format($fine->amount, 2) }}</td>
                                <td class="py-2">{{ $fine->status }}</td>
                                <td class="py-2">
                                    @if($fine->status !== 'paid')
                                        <form method="POST" action="{{ route('treasurer.penalties.markPaid', $fine) }}">
                                            @csrf
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Mark paid</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
