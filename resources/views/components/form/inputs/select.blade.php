<label for="{{ $name }}">{{ $label }}:</label>

<select
    class="form-control @error($name) is-invalid @enderror"
    name="{{ $name }}"
    id="{{ $name }}-dropdown"
>
    <option value="">Select</option>
    @foreach ($options as $key => $value)
        <option
            {{-- {{ !$isSelected($key) ? 'selected="selected"' : '' }} --}}
            {{-- {{ isset($isSelected && $isSelected($key) ? 'selected="selected"' : '' }} --}}
            @if( $isSelected($key)) selected="selected"' @endif
            value="{{ $key }}"
        >{{ $value }}</option>
    @endforeach
</select>

@error($name)
    <x-form.validation-error name="{{ $name }}" :message="$message" />
@enderror
