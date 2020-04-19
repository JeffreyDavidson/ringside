<x-filters>
    <div class="kt-nav__item">
        <div class="form-group m-3">
            <x-form.inputs.select
                name="status"
                label="Status"
                :options="\App\Enums\TitleStatus::labels()"
            />
        </div>
    </div>
    <div class="kt-nav__item">
        <div class="form-group m-3">
            <x-form.inputs.date-range
                name="introduced_at"
                label="Introduced At"
            />
        </div>
    </div>
</x-filters>
