<div class="mb-10">
    <x-form.inputs.select
        name="match_type_id"
        label="Match Type"
        :selected="old('match_type', $match->match_type_id)"
        :options="\App\Models\MatchType::pluck('name', 'id')"
    />
</div>

<div class="mb-10">
    <label class="form-label">Referees</label>
    <input class="form-control d-flex align-items-center" value="" id="kt_tagify_referees" />
</div>

<div class="mb-10">
    <label class="form-label">Titles</label>
    <input class="form-control d-flex align-items-center" value="" id="kt_tagify_titles" />
</div>

<div class="mb-10">
    <x-form.inputs.textarea
        name="preview"
        label="Preview"
        :value="old('preview', $match->preview)"
    />
</div>
