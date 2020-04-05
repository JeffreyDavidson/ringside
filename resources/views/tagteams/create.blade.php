@extends('layouts.app')

@section('content-head')
<x-subheader title="Tag Teams" :link="route('tag-teams.index')" />
@endsection

@section('content')
<x-portlet title="Create Tag Team Form">
    <x-form.form class="kt-form" method="post" :action="route('tag-teams.store')">
        <div class="kt-portlet__body">
            @include('tagteams.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
