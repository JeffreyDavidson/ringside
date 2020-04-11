@extends('layouts.app')

@push('scripts-after')
    <script src="{{ mix('js/wrestlers/index.js') }}"></script>
@endpush

@section('content-head')
<!-- begin:: Content Head -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Wrestlers</h3>
        <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        <x-search />
        @include('wrestlers.partials.filters')
    </div>
    <div class="kt-subheader__toolbar">
        <a href="{{ route('wrestlers.create') }}"
            class="btn btn-label-brand btn-bold">
            Add Wrestler
        </a>
    </div>
</div>

<!-- end:: Content Head -->
@endsection

@section('content')
<x-portlet title="Employed Wrestlers">
    <x-table id="employed_table" dataTable="employed_wrestlers.index" />
</x-portlet>

<x-portlet title="Released Wrestlers">
    <x-table id="released_wrestlers_table" dataTable="released_wrestlers.index" />
</x-portlet>

<x-portlet title="Retired Wrestlers">
    <x-table id="retired_wrestlers_table" dataTable="retired_wrestlers.index" />
</x-portlet>
@endsection
