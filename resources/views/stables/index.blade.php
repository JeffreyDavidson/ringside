<x-layouts.app>
    <x-slot name="toolbar">
        <a href="{{ route('stables.create') }}" class="btn btn-label-brand btn-bold">
            Create Stables
        </a>
    </x-slot>

    <x-content>
       <livewire:stables.all-stables>
    </x-content>
</x-layouts.app>
