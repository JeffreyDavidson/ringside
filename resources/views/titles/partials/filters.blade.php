<div class="kt-nav">
    <div class="kt-nav__head">
        Filter Options:
    </div>
    <div class="kt-nav__separator"></div>
    <div class="kt-nav__item">
        <div class="form-group m-3">
            <label>Status:</label>
            <select class="form-control" name="status" id="status-dropdown">
                <option value="">Select</option>
                <option value="only_active">Active</option>
                <option value="only_inactive">Inactive</option>
                <option value="only_retired">Retired</option>
            </select>
        </div>
    </div>
    <div class="kt-nav__item">
        <div class="form-group m-3">
            <label>Introduced Between:</label>
            <div class="input-group flatpickr kt-input-icon kt-input-icon--right">
                <input class="form-control flatpickr-input" placeholder="Start Date" id="introduced_at_start" data-datetimepicker="" type="text" readonly="readonly">
                <span class="kt-input-icon__icon kt-input-icon__icon--right">
                            <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
                        </span>
            </div>
            <small class="text-center font-weight-bold text-muted d-block">to</small>
            <div class="input-group flatpickr kt-input-icon kt-input-icon--right">
                <input class="form-control flatpickr-input" placeholder="End Date" id="introduced_at_end" data-datetimepicker="" type="text" readonly="readonly">
                <span class="kt-input-icon__icon kt-input-icon__icon--right">
                            <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
                        </span>
            </div>
        </div>
    </div>
    <div class="kt-nav__separator"></div>
    <div class="kt-nav__foot">
        <a class="btn btn-label-brand btn-bold btn-sm" href="#" id="applyFilters">Apply Filters</a>
        <a class="btn btn-clean btn-bold btn-sm" href="#" id="clearFilters">Clear Filters</a>
    </div>
</div>
