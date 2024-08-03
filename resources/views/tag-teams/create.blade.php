<x-layouts.app>
    <x-slot name="toolbar">
        <x-toolbar>
            <x-page-heading>Create Tag Team</x-page-heading>
            <x-breadcrumbs.list>
                <x-breadcrumbs.item :url="route('dashboard')" label="Home" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item :url="route('tag-teams.index')" label="Tag Teams" />
                <x-breadcrumbs.separator />
                <x-breadcrumbs.item label="Create" />
            </x-breadcrumbs.list>
        </x-toolbar>
    </x-slot>

    <x-card>
        <x-slot name="header">
            <x-card.header title="Create Tag Team Form" />
        </x-slot>
        <x-card.body>
            <x-form :action="route('tag-teams.store')" id="createTagTeamForm">
                @include('tag-teams.partials.form')
            </x-form>
        </x-card.body>
        <x-slot name="footer">
            <x-card.footer>
                <x-form.buttons.reset form="createTagTeamForm"/>
                <x-form.buttons.submit form="createTagTeamForm"/>
            </x-card.footer>
        </x-slot>
    </x-card>
</x-layouts.app>
