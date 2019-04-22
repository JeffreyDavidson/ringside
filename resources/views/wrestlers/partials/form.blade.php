<div class="form-group">
    <label>Name:</label>
    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" placeholder="Enter name" value="{{ $wrestler->name ?? old('name') }}">
    @error('name')
        <div id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
    @enderror
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Hometown:</label>
            <input type="text" class="form-control {{ $errors->has('hometown') ? 'is-invalid' : '' }}" name="hometown" placeholder="Enter hometown" value="{{ $wrestler->hometown ?? old('hometown') }}">
            @error('hometown')
                <div id="hometown-error" class="error invalid-feedback">{{ $errors->first('hometown') }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Signature Move:</label>
            <input type="text" class="form-control {{ $errors->has('signature_move') ? 'is-invalid' : '' }}" name="signature_move" placeholder="Enter signature move" value="{{ $wrestler->signature_move ?? old('signature_move') }}">
            @error('signature_move')
                <div id="signature-move-error" class="error invalid-feedback">{{ $errors->first('signature_move') }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label>Feet:</label>
            <input type="number" class="form-control {{ $errors->has('feet') ? 'is-invalid' : '' }}" min="5" max="7" name="feet" placeholder="Enter feet" value="{{ $wrestler->feet ?? old('feet') }}">
            @error('feet')
                <div id="feet-error" class="error invalid-feedback">{{ $errors->first('feet') }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Inches:</label>
            <input type="number" class="form-control {{ $errors->has('inches') ? 'is-invalid' : '' }}" min="1" max="11" name="inches" placeholder="Enter inches" value="{{ $wrestler->inches ?? old('inches') }}">
            @error('inches')
                <div id="inches-error" class="error invalid-feedback">{{ $errors->first('inches') }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Weight:</label>
            <input type="number" class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" name="weight" placeholder="Enter weight" value="{{ $wrestler->weight ?? old('weight') }}">
            @error('weight')
                <div id="weight-error" class="error invalid-feedback">{{ $errors->first('weight') }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Date Hired:</label>
            <input type="date" class="form-control {{ $errors->has('hired_at') ? 'is-invalid' : '' }}" name="hired_at" placeholder="Enter date hired" value="{{ $wrestler->hired_at->toDateString() ?? old('hired_at') }}">
            @error('hired_at')
                <div id="hired-at-error" class="error invalid-feedback">{{ $errors->first('hired_at') }}</div>
            @enderror
        </div>
    </div>
</div>
