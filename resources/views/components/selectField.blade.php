<field-label :for="$name" :label="$label ?? ''" />

<select
    class="form-control"
    name="{{ $name }}"
    id="{{ $name }}-dropdown"
>
    <option value="">Select</option>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}"> {{ $value }}</option>
    @endforeach
</select>
