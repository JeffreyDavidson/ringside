@extends('layouts.app')

@section('content-head')
<x-subheader title="Stables" :link="route('stables.index')" />
@endsection

@section('content')
<x-portlet title="Create Stable Form">
    <x-form.form class="kt-form" method="post" :action="route('stables.store')">
        <div class="kt-portlet__body">
            @include('stables.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
