<x-layouts.app>
    <x-subheader search="true" filters="wrestlers.partials.filters" title="Wrestlers">
        <x-slot name="actions">
            <a href="{{ route('wrestlers.create') }}" class="btn btn-label-brand btn-bold">
                Create Wrestlers
            </a>
        </x-slot>
    </x-subheader>
    <x-content>
        <x-portlet title="Employed Wrestlers">
            <div class="kt-portlet__body">
                <x-table id="employed_wrestlers_table" dataTable="employed_wrestlers.index" class="dataTable" />
            </div>
        </x-portlet>
    </x-content>
    @push('scripts-after')
    <script src="{{ mix('js/wrestlers/index.js') }}"></script>
    @endpush
</x-layouts.app>
