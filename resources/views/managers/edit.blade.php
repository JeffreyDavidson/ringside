<x-layouts.app>
    <x-slot name="toolbar">
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <!--begin::Title-->
                    <h1 class="my-0 page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center">Managers</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bg-gray-400 bullet w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('managers.index') }}" class="text-muted text-hover-primary">Managers</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bg-gray-400 bullet w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('managers.show', $manager) }}" class="text-muted text-hover-primary">{{ $manager->full_name }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bg-gray-400 bullet w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Edit</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
    </x-slot>

    <div class="shadow-sm card">
        <div class="card-header">
            <h3 class="card-title">Edit Manager Form</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('managers.update', $manager) }}">
                @method('patch')
                @csrf
                <div class="mb-10">
                    <div class="mb-5 row gx-10">
                        <div class="col-lg-6">
                            <label class="form-label">First Name:</label>
                            <input type="text" name="first_name" class="form-control" placeholder="Manager First Name Here" value="{{ $manager->first_name }}">
                            @error('first_name')
                                <div class="fv-plugins-message-container invalid-feedback"><div data-field="name" data-validator="notEmpty">{{ $message }}</div></div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Last Name:</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Manager Last Name Here" value="{{ $manager->last_name }}">
                            @error('last_name')
                                <div class="fv-plugins-message-container invalid-feedback"><div data-field="name" data-validator="notEmpty">{{ $message }}</div></div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" placeholder="MM-DD-YYYY" value="{{ $manager->started_at?->format('Y-m-d') }}">
                    @error('start_date')
                        <div class="fv-plugins-message-container invalid-feedback"><div data-field="name" data-validator="notEmpty">{{ $message }}</div></div>
                    @enderror
                </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
        </form>
    </div>
</x-layouts.app>
