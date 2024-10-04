<div class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">{{ $this->getModalTitle() }}</h3>
        </div>
        <div class="modal-body">
            <form wire:submit="save" id="wrestlerForm">
                <div class="mb-10">
                    <label class="form-label">{{ __('wrestlers.name') }}:</label>
                    <input type="text" class="input" wire:model.live="form.name" />
                    <div>
                        @error('form.name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="mb-10">
                    <div class="mb-5 row gx-10">
                        <div class="col-lg-3">
                            <label class="form-label">{{ __('wrestlers.feet') }}:</label>
                            <input type="text" class="input" wire:model="form.height_feet" />
                            <div>
                                @error('form.height_feet')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">{{ __('wrestlers.inches') }}:</label>
                            <input type="text" class="input" wire:model="form.height_inches" />
                            <div>
                                @error('form.height_inches')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ __('wrestlers.weight') }}:</label>
                            <input type="text" class="input" wire:model="form.weight" />
                            <div>
                                @error('form.weight')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label">{{ __('wrestlers.hometown') }}:</label>
                    <input type="text" class="input" wire:model="form.hometown" />
                    <div>
                        @error('form.hometown')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label">{{ __('employments.start_date') }}:</label>
                    <input type="date" class="input" wire:model="form.start_date" />
                    <div>
                        @error('form.start_date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('wrestlers.signature_move') }}:</label>
                    <input type="text" class="input" wire:model="form.signature_move" />
                    <div>
                        @error('form.signature_move')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-end">
            <div class="flex gap-4">
                <button class="btn btn-light" wire:click="$dispatch('closeModal')">Cancel</button>
                <button class="btn btn-primary" type="submit" form="wrestlerForm" >Submit</button>
            </div>
        </div>
    </div>
</div>
