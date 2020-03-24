@extends('layouts.app')

@section('content-head')
<x-subheader title="Titles" :link="route('titles.index')" />
@endsection

@section('content')
<x-portlet title="Edit Title Form">
    <x-form method="PATCH" action="route('titles.update, $title')">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('titles.partials.form')
            </div>
        </div>
    </x-form>
</x-portlet>
@endsection
