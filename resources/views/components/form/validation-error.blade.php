@props([
    'field' => '',
])

<div class="">
    @error($field)
        {{ $message }}
    @enderror
</div>
