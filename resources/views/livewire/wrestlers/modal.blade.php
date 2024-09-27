<modal :title="{{ $wrestler ? 'Edit Wrestler ${$wrestler->name}' : 'Create Wrestler' }}">
    <div>
        Name: <input type="text" wire:model.live="form.name" />
        <div>
            @error('form.name')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        Weight: <input type="text" wire:model="form.weight" />
        <div>
            @error('form.weight')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        Height Feet: <input type="text" wire:model="form.height_feet" />
        Inches: <input type="text" wire:model="form.height_inches" />
        <div>
            @error('form.height_feet')
                {{ $message }}
            @enderror
        </div>
        <div>
            @error('form.height_inches')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        Home Town: <input type="text" wire:model="form.hometown" />
        <div>
            @error('form.hometown')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        Signature Move: <input type="text" wire:model="form.signature_move" />
        <div>
            @error('form.signature_move')
                {{ $message }}
            @enderror
        </div>
    </div>
</modal>
