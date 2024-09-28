<label class="form-label" for="{{ $name }}">{{ $label }}</label>

{{ $slot }}

@error($name)
    <x-form.validation-error name="{{ $name }}" :message="$message" />
@enderror
