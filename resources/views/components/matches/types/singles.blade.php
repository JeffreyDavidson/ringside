<div class="mb-10 row">
    <div class="col-lg-6">
        <x-form.inputs.select
            name="competitors[0]"
            label="Competitor"
            :selected="old('wrestler', $match->competitors?->first()?->competitor->id)"
            :options="\App\Models\Wrestler::bookable()->pluck('name', 'id')"
        />
    </div>
</div>
