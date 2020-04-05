@extends('layouts.app')

@section('content-head')
<x-subheader title="Tag Team" :link="route('tag-teams.index')" />
@endsection

@section('content')
<x-portlet title="Edit Tag Team Form">
    <x-form.form class="kt-form" method="patch" :action="route('tag-teams.update', $tagTeam)">
        <div class="kt-portlet__body">
            @include('tagteams.partials.form')
        </div>
    </x-form>
</x-portlet>
@endsection
