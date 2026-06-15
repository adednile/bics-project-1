<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Member Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">Welcome, {{ Auth::user()->name }}</h3>
                <p class="text-sm text-gray-600 mt-2">Your member dashboard is ready for contribution, loan, and statement features.</p>
            </div>
        </div>
    </div>
</x-app-layout>
