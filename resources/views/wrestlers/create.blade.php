<x-layouts.app>
    <x-subheader
        title="Wrestlers"
        :link="route('wrestlers.index')"
        linkText="Back to Wrestlers"
    />
    <x-content>
        <x-portlet title="Create Wrestler Form">
            <x-form.form class="kt-form" method="post" :action="route('wrestlers.store')">
                <div class="kt-portlet__body">
                    @include('wrestlers.partials.form')
                </div>
            </x-form>
        </x-portlet>
    </x-content>
</x-layouts.app>
