<x-layouts.app>
    <x-slot name="toolbar">
        <x-toolbar>
            <x-page-heading>Create Manager</x-page-heading>
            <x-breadcrumbs.list>
                <x-breadcrumbs.item :url="route('dashboard')" label="Home" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item :url="route('managers.index')" label="Managers" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item label="Create" />
            </x-breadcrumbs.list>
        </x-toolbar>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <x-card.header title="Create Manager Form" />
        </x-slot>
        <x-card.body>
            <x-form :action="route('managers.store')" id="createManagerForm">
                @include('managers.partials.form')
            </x-form>
        </x-card.body>
        <x-slot name="footer">
            <x-card.footer>
                <x-form.buttons.reset form="createManagerForm"/>
                <x-form.buttons.submit form="createManagerForm"/>
            </x-card.footer>
        </x-slot>
    </x-card>
</x-layouts.app>
