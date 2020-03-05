<input type="text"
    class="form-control
    @error($name) is-invalid @enderror"
    name="{{ $name }}"
    placeholder="Enter {{ $label }}"
    value="{{ $value }}">

<x-validation-error />
