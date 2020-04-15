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
            <x-datatable id="employed_table" dataTable="employed_wrestlers.index" />
        </x-portlet>

        <x-portlet title="Released Wrestlers">
            <x-datatable id="released_wrestlers_table" dataTable="released_wrestlers.index" />
        </x-portlet>

        <x-portlet title="Retired Wrestlers">
                <x-datatable id="retired_wrestlers_table" dataTable="retired_wrestlers.index" />
        </x-portlet>
    </x-content>
    @push('scripts-after')
    <script src="{{ mix('js/wrestlers/index.js') }}"></script>
    @endpush
</x-layouts.app>
