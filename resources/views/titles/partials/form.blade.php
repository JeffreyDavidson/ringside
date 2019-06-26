<div class="row">
    <div class="col-lg-8">
        <div class="form-group">
            <label>Title Name:</label>
            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" placeholder="Enter title name" value="{{ $title->name ?? old('name') }}">
            @if ($errors->has('name'))
                <div id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Title Slug:</label>
            <input type="text" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" name="slug" placeholder="Enter venue slug" value="{{ $title->slug ?? old('slug') }}">
            @if ($errors->has('slug'))
                <div id="slug-error" class="error invalid-feedback">{{ $errors->first('slug') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="form-group date">
    <label>Date Introduced:</label>
    <div class="input-group date">
        <input type="text" class="form-control {{ $errors->has('introduced_at') ? 'is-invalid' : '' }}" id="kt_datetimepicker_2" name="introduced_at" placeholder="Enter date introduced" readonly value="{{ $venue->introduced_at ?? old('introduced_at') }}">
        <div class="input-group-append">
            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
        </div>
    </div>
    @if ($errors->has('introduced_at'))
        <div id="introduced_at-error" class="error invalid-feedback">{{ $errors->first('introduced_at') }}</div>
    @endif
</div>
