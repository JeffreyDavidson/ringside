<x-layouts.app>
    <x-subheader
        title="Wrestlers"
        :link="route('wrestlers.index')"
        linkText="Back to Wrestlers"
    />
    <x-content>
        <x-portlet title="Edit Wrestler Form">
            <x-form.form
                method="patch"
                :action="route('wrestlers.update', $wrestler)"
            >
                <div class="kt-portlet__body">
                    @include('wrestlers.partials.form')
                </div>
            </x-form>
        </x-portlet>
    </x-content>
</x-layouts.app>
