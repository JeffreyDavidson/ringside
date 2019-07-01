<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="" data-placement="right" data-original-title="Quick filters">
    <a href="#" class="btn btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="flaticon-interface-7"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-224.75px, 35.25px, 0px);">
        <!--begin::Nav-->
        <ul class="kt-nav">
            <li class="kt-nav__head">
                Filter Options:
            </li>
            <li class="kt-nav__separator"></li>
            <li class="kt-nav__item">
                <div class="form-group m-3">
                    <label>Status:</label>
                    <select class="form-control" name="status" id="status-dropdown">
                        <option value="">Select</option>
                        <option value="only_active">Active</option>
                        <option value="only_inactive">Inactive</option>
                        <option value="only_retired">Retired</option>
                    </select>
                </div>
            </li>
            <li class="kt-nav__item">
                <div class="form-group m-3 flatpickr">
                    <label>Introduced At:</label>
                    <input class="form-control" placeholder="Start Date" id="introduced_at_start">
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
                    </span>
                    <small class="text-center font-weight-bold text-muted d-block">to</small>
                    <input class="form-control" placeholder="End Date" id="introduced_at_end">
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
                    </span>
                </div>
            </li>
            <li class="kt-nav__separator"></li>
            <li class="kt-nav__foot">
                <a class="btn btn-label-brand btn-bold btn-sm" href="#" id="applyFilters">Apply Filters</a>
                <a class="btn btn-clean btn-bold btn-sm" href="#" id="clearFilters">Clear Filters</a>
            </li>
        </ul>
        <!--end::Nav-->
    </div>
</div>
