<field-label :for="$name" :label="$label ?? ''" />

<div class="input-group flatpickr kt-input-icon kt-input-icon--right">
    <div class="kt-input-icon kt-input-icon--right">
        <input
            type="text"
            class="form-control @error($name) is-invalid @enderror"
            name="{{ $name }}"
            id="{{ $name }}_start"
            placeholder="Enter {{ $label }} Start"
            data-datetimepicker
            data-input
        >
        <span class="kt-input-icon__icon kt-input-icon__icon--right">
            <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
        </span>
        @error($name)
            <form-error name="{{ $name }}" />
        @enderror
    </div>
</div>
<small class="text-center font-weight-bold text-muted d-block">to</small>
<div class="input-group flatpickr kt-input-icon kt-input-icon--right">
    <div class="kt-input-icon kt-input-icon--right">
        <input
            type="text"
            class="form-control @error($name) is-invalid @enderror"
            name="{{ $name }}"
            id="{{ $name }}_end"
            placeholder="Enter {{ $label }} End"
            data-datetimepicker
            data-input
        >
        <span class="kt-input-icon__icon kt-input-icon__icon--right">
            <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
        </span>
        @error($name)
            <form-error name="{{ $name }}" />
        @enderror
    </div>
</div>
