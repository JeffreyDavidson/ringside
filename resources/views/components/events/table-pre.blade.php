<div class="flex flex-wrap items-center justify-between gap-5 pb-7.5">
    <div class="flex flex-col justify-center gap-2">
        <h1 class="text-xl font-medium leading-none text-gray-900">
            Events
        </h1>
        <div class="flex items-center flex-wrap gap-1.5 font-medium">
            <span class="text-md text-gray-600">
                All Events:
            </span>
            <span class="text-md gray-800 font-semibold me-2">
                {{ $this->builder()->count() }}
            </span>
            <span class="text-md text-gray-600">
                Scheduled
            </span>
            <span class="text-md gray-800 font-semibold">
                {{ $this->builder()->where('status', \App\Enums\EventStatus::Scheduled->value)->count() }}
            </span>
        </div>
    </div>
    <div class="flex items-center gap-2.5">
        <div class="flex justify-items-end gap-2.5">
            <button class="btn btn-sm btn-primary"
                @click="$dispatch('openModal', { component: 'events.modals.form-modal' })">
                Add Event
            </button>
        </div>
    </div>
</div>
