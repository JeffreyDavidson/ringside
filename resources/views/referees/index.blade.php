@extends('layouts.app')

@push('scripts-after')
    <script src="{{ mix('js/referees/index.js') }}"></script>
@endpush

@section('content-head')
<!-- begin:: Content Head -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Referees</h3>
        <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        <x-search />
        @include('referees.partials.filters')
    </div>
    <div class="kt-subheader__toolbar">
        <a href="{{ route('referees.create') }}"
            class="btn btn-label-brand btn-bold">
            Add Referee
        </a>
    </div>
</div>

<!-- end:: Content Head -->
@endsection

@section('content')
<x-portlet title="Employed Referees">
    <table id="referees_table" data-table="referees.index" class="table table-hover"></table>
</x-portlet>
@endsection
