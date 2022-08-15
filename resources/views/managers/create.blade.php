<x-layouts.app>
    <x-slot name="toolbar">
        <x-toolbar title="Managers">
            <x-breadcrumbs.item :url="route('dashboard')" label="Home" />
            <x-breadcrumbs.separator />
            <x-breadcrumbs.item :url="route('managers.index')" label="Managers" />
            <x-breadcrumbs.separator />
            <x-breadcrumbs.item label="Create" />
        </x-toolbar>
    </x-slot>

    <div class="shadow-sm card">
        <div class="card-header">
            <h3 class="card-title">Create A New Manager Form</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('managers.store') }}">
                @csrf
                <div class="mb-10">
                    <div class="mb-5 row gx-10">
                        <div class="col-lg-6">
                            <x-form.inputs.text label="First Name:" name="first_name" placeholder="First Name Here" />
                        </div>
                        <div class="col-lg-6">
                            <x-form.inputs.text label="Last Name:" name="last_name" placeholder="Last Name Here" />
                        </div>
                    </div>
                </div>
                <div class="mb-10">
                    <x-form.inputs.date label="Start Date:" name="start_date" />
                </div>
        </div>
        <div class="card-footer">
            <x-form.buttons.submit />
            <x-form.buttons.reset />
        </div>
        </form>
    </div>
</x-layouts.app>
