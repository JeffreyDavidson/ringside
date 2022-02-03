<x-layouts.app>
    <x-slot name="toolbar">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Create Title
        </h2>
    </x-slot>

    <x-content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Title Form</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('titles.store') }}" method="post">
                    @csrf
                    @include('titles.partials.form')
                    <div class="row">
                        <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                            <button type="submit" class="me-2 btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>

                        <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                            <a href="{{ route('titles.index') }}" class="btn btn-md btn-secondary">
                                <x-icons.arrow />
                                Back to Titles
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-content>
</x-layouts.app>
