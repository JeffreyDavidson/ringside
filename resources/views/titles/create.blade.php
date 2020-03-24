@extends('layouts.app')

@section('content-head')
<x-subheader title="Titles" :link="route('titles.index')" />
@endsection

@section('content')
<x-portlet title="Create Title Form">
    <x-form method="POST" :action="route('titles.store')">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('titles.partials.form')
            </div>
        </div>
    </x-form>
</x-portlet>
@endsection
