<input type="text"
    class="form-control
    @error($name) is-invalid @enderror"
    name="{{ $name }}"
    placeholder="Enter {{ $label }}"
    value="$value">

@if ($errors->has($name))
    <x-validation-error name="{{ $name }}"/>
@endif
