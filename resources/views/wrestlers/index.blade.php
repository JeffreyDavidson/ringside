<x-layouts.app>
    <x-subheader
        search="true"
        filters="wrestlers.partials.filters"
        title="Wrestlers"
    >
        <x-slot name="actions">
            <a href="{{ route('wrestlers.create') }}" class="btn btn-label-brand btn-bold">
                Create Wrestlers
            </a>
        </x-slot>
    </x-subheader>
    <x-content>
        <x-portlet title="Employed Wrestlers">
            <div class="kt-portlet__body">
                <livewire:wrestlers.employed-wrestlers>
            </div>
        </x-portlet>

        <x-portlet title="Released Wrestlers">
            <div class="kt-portlet__body">
                <livewire:wrestlers.released-wrestlers>
            </div>
        </x-portlet>

        <x-portlet title="Retired Wrestlers">
            <div class="kt-portlet__body">
                <livewire:wrestlers.retired-wrestlers>
            </div>
        </x-portlet>
    </x-content>
    @push('scripts-after')
        <script src="{{ mix('js/wrestlers/index.js') }}"></script>
    @endpush
</x-layouts.app>
