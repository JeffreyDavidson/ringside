<field-label :for="$name" :label="$label ?? ''" />

<input
    type="number"
    class="form-control @error($name) is-invalid @enderror"
    min="{{ $min ?? '' }}"
    max="{{ $max ?? '' }}"
    name="{{ $name }}"
    placeholder="Enter {{ $label }}"
    value="{{ old($name, $model->$name) }}"
>

@error($name)
    <form-error name="{{ $name }} />
@enderror
