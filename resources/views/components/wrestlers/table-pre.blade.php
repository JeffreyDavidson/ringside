<x-container-fixed>
    <div class="flex flex-col justify-center gap-2">
        <x-page.heading>Wrestlers</x-page.heading>
        <x-tables.metadata>
            <span class="text-md text-gray-600">
                All Wrestlers:
            </span>
            <span class="text-md gray-800 font-semibold me-2">
                {{ $this->builder()->count() }}
            </span>
            <span class="text-md text-gray-600">
                Bookable
            </span>
            <span class="text-md gray-800 font-semibold">
                {{ $this->builder()->where('status', \App\Enums\WrestlerStatus::Bookable->value)->count() }}
            </span>
        </x-tables.metadata>
    </div>
    <div class="flex items-center gap-2.5">
        <div class="flex justify-items-end gap-2.5">
            <button class="btn btn-sm btn-primary"
                @click="$dispatch('openModal', { component: 'wrestlers.modals.form-modal' })">
                Add Wrestler
            </button>
        </div>
    </div>
</x-container-fixed>
