@extends('layouts.app')

@section('content-head')
<!-- begin:: Content Head -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Managers</h3>
    </div>
    <div class="kt-subheader__toolbar">
        <a href="{{ route('managers.index') }}" class="btn btn-label-brand btn-bold">
            Back To Managers
        </a>
    </div>
</div>

<!-- end:: Content Head -->
@endsection

@section('content')
<!--begin::Portlet-->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Edit Manager Form
            </h3>
        </div>
    </div>

    <!--begin::Form-->
    <form class="kt-form" method="post" action="{{ route('managers.update', $manager) }}">
        @method('PUT')
        @csrf
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                @include('managers.partials.form')
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>

    <!--end::Form-->
</div>

<!--end::Portlet-->
@endsection
