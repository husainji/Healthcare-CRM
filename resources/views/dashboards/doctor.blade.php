<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Doctor Dashboard
        </h2>
    </x-slot>

    <div class="p-6">
        Welcome, {{ auth()->user()->name }} (Doctor)
    </div>
</x-app-layout>
