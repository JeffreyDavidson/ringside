<x-layouts.app>
    <x-container-fixed>
        <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-gray-900">
                    Wrestlers
                </h1>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="flex justify-items-end gap-2.5">
                    <button class="btn btn-sm btn-primary"
                        wire:click="$dispatch('openModal', { component: 'wrestlers.wrestler-modal' })">
                        Add Wrestler
                    </button>
                </div>
            </div>
        </div>
    </x-container-fixed>
    <x-container-fixed>
        <div class="card-body">
            <livewire:wrestlers.wrestlers-table />
        </div>
    </x-container-fixed>
</x-layouts.app>
