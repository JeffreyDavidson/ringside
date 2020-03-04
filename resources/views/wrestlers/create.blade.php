@extends('layouts.app')

@section('content-head')
<x-subheader title="Wrestlers" :link="route('wrestlers.index')"/>
@endsection

@section('content')
<!--begin::Portlet-->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Create Wrestler Form
            </h3>
        </div>
    </div>

    <!--begin::Form-->
    <x-form class="kt-form" method="post" :action="route('wrestlers.store')">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('wrestlers.partials.form')
            </div>
        </div>
    </x-form>
     <!--end::Form-->
</div>

<!--end::Portlet-->
@endsection
