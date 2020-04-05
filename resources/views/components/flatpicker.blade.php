<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">{{ $title }}</h3>
        </div>
    </div>

    {{ $slot }}

</div>

<div class="input-group flatpickr kt-input-icon kt-input-icon--right">
    <div class="kt-input-icon kt-input-icon--right">
        <input type="text" class="form-control @error(" {$name}.start") is-invalid @enderror" name="{{ $name }}[start]"
            id="{{ $name }}_start" placeholder="Enter {{ $label }} Start" data-datetimepicker data-input>
        <span class="kt-input-icon__icon kt-input-icon__icon--right">
            <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
        </span>
        @error("{$name}.start")
        <x-form.validation-error name="{{ $name }}['start']" :message="$message" />
        @enderror
    </div>
</div>
