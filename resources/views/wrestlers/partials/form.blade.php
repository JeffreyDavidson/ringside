<context :model="$wrestler">
    <div class="kt-section">
        <h3 class="kt-section__title">General Information:</h3>
        <div class="kt-section_content">
            <div class="form-group">
                <input-field name="name" label="Name" />
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <input-field name="hometown" label="Hometown" />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <date-field name="started_at" label="Date Started" />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <input-field name="signature_move" label="Signature Move" />
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
                        <number-field name="feet" label="Feet" min="5" max="11" />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <number-field name="inches" label="Inches" max="11" />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <input-field name="weight" label="Weight" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</context>
