@extends('layouts.app')

@section('content-head')
<x-subheader title="Wrestlers" :link="route('wrestlers.index')" />
@endsection

@section('content')
<x-portlet title="Edit Wrestler Form">
    <x-form class="kt-form" method="patch" :action="route('wrestlers.update', $wrestler)">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('wrestlers.partials.form')
            </div>
        </div>
    </x-form>
</x-portlet>
@endsection
