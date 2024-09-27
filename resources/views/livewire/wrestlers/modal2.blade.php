<div>
    <div>
        @isset($wrestler)
            Edit Wrestler: {{ $wrestler->name }}
        @else
            Create Wrestler
        @endisset
    </div>
    <div>
        <div class="mb-10">
            <x-form.inputs.text wire:model.live="form.name" label="Name:" placeholder="Enter Wrestler Name" />
        </div>

        <div class="mb-10">
            <div class="mb-5 row gx-10">
                <div class="col-lg-3">
                    <x-form.inputs.number label="Height (Feet):" name="feet" max="8"
                        wire:model="form.height_inches" />
                </div>

                <div class="col-lg-3">
                    <x-form.inputs.number label="Height (Inches):" name="inches" max="11"
                        wire:model="form.weight" />
                </div>

                <div class="col-lg-6">
                    <x-form.inputs.number label="Weight:" name="weight" wire:model="form.hometown" />
                </div>
            </div>
        </div>

        <div class="mb-10">
            <x-form.inputs.text wire:model.live="form.hometown" label="Hometown:" placeholder="Orlando, FL" />
        </div>

        <div class="mb-10">
            <x-form.inputs.text wire:model.live="form.signature_move" label="Signature Move:"
                placeholder="This Amazing Finisher" />
        </div>

        {{-- <div class="mb-10">
            <x-form.inputs.date label="Start Date:" name="start_date" :value="old('start_date', $wrestler->started_at?->format('Y-m-d'))" />
        </div> --}}

        <div>
            <button wire:click="save">Save Wrestler</button>
        </div>
    </div>
</div>
