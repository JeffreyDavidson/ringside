<div class="form-group">
    <x-form.inputs.text
        name="name"
        label="Title Name"
        :value="old('name', $title->name)"
    />
</div>

<div class="form-group">
    <x-form.inputs.date
        name="introduced_at"
        label="Introduced At"
        :value="old('introduced_at', $title->introduced_at)"
    />
</div>

