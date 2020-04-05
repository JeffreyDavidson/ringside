@extends('layouts.app')

@section('content-head')
<x-subheader title="Referees" :link="route('referees.index')" />
@endsection

@section('content')
<x-portlet title="Create Referee Form">
    <x-form.form class="kt-form" method="post" :action="route('referees.store')">
        <div class="kt-portlet__body">
            @include('referees.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
