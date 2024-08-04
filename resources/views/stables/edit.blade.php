<x-layouts.app>
    <x-slot name="toolbar">
        <x-toolbar>
            <x-page-heading>Edit Stable</x-page-heading>
            <x-breadcrumbs.list>
                <x-breadcrumbs.item :url="route('dashboard')" label="Home" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item :url="route('stables.index')" label="Stables" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item :url="route('stables.show', $stable)" :label="$stable->name" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item label="Edit" />
            </x-breadcrumbs.list>
        </x-toolbar>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <x-card.header title="Edit Stable Form" />
        </x-slot>
        <x-card.body>
            <x-form :action="route('stables.update', $stable)" id="editStableForm">
                @include('stables.partials.form')
            </x-form>
        </x-card.body>
        <x-slot name="footer">
            <x-card.footer>
                <x-form.buttons.reset form="editStableForm"/>
                <x-form.buttons.submit form="editStableForm"/>
            </x-card.footer>
        </x-slot>
    </x-card>
</x-layouts.app>
