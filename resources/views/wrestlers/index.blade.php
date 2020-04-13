<x-layouts.app>
    <x-subheader
        search="true"
        filters="wrestlers.partials.filters"
        title="Wrestlers"
        :link="route('wrestlers.create')"
        linkText="Create Wrestler"
    />
    <x-content>
        <x-portlet title="Employed Wrestlers">
            <x-table id="employed_table" dataTable="employed_wrestlers.index" />
        </x-portlet>

        <x-portlet title="Released Wrestlers">
            <x-table id="released_wrestlers_table" dataTable="released_wrestlers.index" />
        </x-portlet>

        <x-portlet title="Retired Wrestlers">
            <x-table id="retired_wrestlers_table" dataTable="retired_wrestlers.index" />
        </x-portlet>
    </x-content>
    @push('scripts-after')
        <script src="{{ mix('js/wrestlers/index.js') }}"></script>
    @endpush
</x-layouts.app>
