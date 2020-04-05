@extends('layouts.app')

@section('content-head')
<x-subheader title="Managers" :link="route('managers.index')" />
@endsection

@section('content')
<x-portlet title="Edit Manager Form">
    <x-form.form class="kt-form" method="patch" :action="route('managers.update', $manager)">
        <div class="kt-portlet__body">
            @include('managers.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
