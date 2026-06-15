<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Member Statement</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Statement for {{ $user->name }}</h3>
            <p class="text-sm text-gray-600 mb-6">Contributions, loans, fines, and transactions are listed below.</p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold">Contributions</h4>
                    <ul class="list-disc list-inside">
                        @foreach($contributions as $contribution)
                            <li>{{ $contribution->contribution_date->format('Y-m-d') }} - {{ number_format($contribution->amount, 2) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold">Loans</h4>
                    <ul class="list-disc list-inside">
                        @foreach($loans as $loan)
                            <li>{{ $loan->status }} - {{ number_format($loan->amount, 2) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold">Fines</h4>
                    <ul class="list-disc list-inside">
                        @foreach($fines as $fine)
                            <li>{{ $fine->type }} - {{ number_format($fine->amount, 2) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
