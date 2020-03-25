<div class="kt-section">
    <h3 class="kt-section__title">General Information:</h3>
    <div class="kt-section_content">
        <!-- Name -->
        <div class="form-group">
            <x-inputs.text name="name" label="Name" :value="old('name')" />
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.text name="hometown" label="Hometown" :value="old('hometown')" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.date name="started_at" label="Date Started" :value="old('started_at')" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.text name="signature_move" label="Signature Move" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="kt-section">
    <h3 class="kt-section__title">Physical Information:</h3>
    <div class="kt-section_content">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.number name="feet" label="Feet" min="5" max="11" :value="old('feet')" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.number name="inches" label="Inches" max="11" :value="old('inches')" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <x-inputs.text name="weight" label="Weight" :value="old('weight')" />
                </div>
            </div>
        </div>
    </div>
</div>
