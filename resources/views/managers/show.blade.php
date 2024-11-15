<x-layouts.app>
    <x-container-fixed>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-7.5">
            <div class="col-span-1">
                <div class="grid gap-5 lg:gap-7.5">
                    {{-- <x-managers.show.general-info :$manager /> --}}
                </div>
            </div>
            <div class="col-span-2">
                <div class="flex flex-col gap-5 lg:gap-7.5">
                    <livewire:managers.previous-wrestlers-table :$manager />
                    {{-- <livewire:managers.previous-tag-teams-table :$manager /> --}}
                    {{-- <livewire:managers.previous-stables-table :$manager /> --}}
                </div>
            </div>
        </div>
    </x-container-fixed>
</x-layouts.app>
