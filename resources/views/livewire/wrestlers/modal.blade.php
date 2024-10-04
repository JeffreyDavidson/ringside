<div class="modal">
    <div class="modal-content flex flex-col relative mx-auto rounded-xl max-w-[600] top-[10%] tw-modal-background-color">
        <div class="flex items-center justify-between border-gray-200 border border-b py-2.5 px-5">
            <h3 class="text-sm font-semibold text-gray-900">{{ $this->getModalTitle() }}</h3>
            <button
                class="inline-flex items-center cursor-pointer leading-4 rounded-md border border-solid w-7 h-7 justify-center shrink-0 p-0 gap-0 font-medium text-base btn-light"
                wire:click="closeModal">
                <i class="ki-outline ki-cross"></i>
            </button>
        </div>
        <div class="py-2.5 px-5">
            <form wire:submit="save" id="wrestlerForm">
                <div class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <label
                            class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.name') }}:</label>
                        <input type="text"
                            class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                            wire:model.live="form.name" />
                        <div>
                            @error('form.name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="columns-3">
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.feet') }}:</label>
                                <input type="text"
                                    class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                                    wire:model="form.height_feet" />
                                <div>
                                    @error('form.height_feet')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.inches') }}:</label>
                                <input type="text"
                                    class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                                    wire:model="form.height_inches" />
                                <div>
                                    @error('form.height_inches')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.weight') }}:</label>
                                <input type="text"
                                    class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                                    wire:model="form.weight" />
                                <div>
                                    @error('form.weight')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.hometown') }}:</label>
                        <input type="text"
                            class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                            wire:model="form.hometown" />
                        <div>
                            @error('form.hometown')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="flex w-full font-normal text-gray-900 text-2sm">{{ __('employments.start_date') }}:</label>
                        <input type="date"
                            class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                            wire:model="form.start_date" />
                        <div>
                            @error('form.start_date')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label
                            class="flex w-full font-normal text-gray-900 text-2sm">{{ __('wrestlers.signature_move') }}:</label>
                        <input type="text"
                            class="block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light"
                            wire:model="form.signature_move" />
                        <div>
                            @error('form.signature_move')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div
            class="flex items-center justify-end border border-t py-2.5 px-5 border-solid border-1 border-gray-300 font-medium text-2sm text-gray-700">
            <div class="flex gap-4">
                <button class="inline-flex items-center cursor-pointer leading-4 rounded-md h-10 px-4 gap-1.5 border"
                    wire:click="closeModal">Cancel</button>
                <button
                    class="inline-flex items-center cursor-pointer leading-4 rounded-md h-10 px-4 gap-1.5 border border-transparent border-solid text-white bg-blue-500"
                    type="submit" form="wrestlerForm">Submit</button>
            </div>
        </div>
    </div>
</div>
