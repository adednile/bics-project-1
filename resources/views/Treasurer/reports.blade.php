<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Reports</h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Group report summary</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li>Total contributions: {{ number_format($contributions->sum('amount'), 2) }}</li>
                <li>Total loans: {{ number_format($loans->sum('amount'), 2) }}</li>
                <li>Total fines: {{ number_format($fines->sum('amount'), 2) }}</li>
                <li>Members: {{ $users->count() }}</li>
            </ul>
        </div>
    </div>
</x-app-layout>
