@extends('layouts.app')

@section('content-head')
<x-subheader title="Wrestlers" :link="route('wrestlers.index')" />
@endsection

@section('content')
<x-portlet title="Create Wrestler Form">
    <x-form class="kt-form" method="post" :action="route('wrestlers.store')">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('wrestlers.partials.form')
            </div>
        </div>
    </x-form>
</x-portlet>
@endsection
