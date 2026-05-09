<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Property</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('properties.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @include('properties.partials.form', ['property' => null])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
