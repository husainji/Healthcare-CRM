<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Lab Manager Dashboard
        </h2>
    </x-slot>

    <div class="p-6">
        Welcome, {{ auth()->user()->name }} (Lab Manager)
    </div>
</x-app-layout>
