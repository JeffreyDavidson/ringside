@extends('layouts.app')

@section('content-head')
<x-subheader title="Managers" :link="route('managers.index')" />
@endsection

@section('content')
<x-portlet title="Create Manager Form">
    <x-form.form class="kt-form" method="post" :action="route('managers.store')">
        <div class="kt-portlet__body">
            @include('managers.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
