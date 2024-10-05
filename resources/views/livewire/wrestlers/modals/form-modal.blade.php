<div class="w-full overflow-auto">
    <div class="flex flex-col relative mx-auto rounded-xl max-w-[600] top-[10%] shadow-[--tw-modal-box-shadow] dark:shadow[--tw-modal-box-shadow] bg-[--tw-modal-background-color] dark:bg-[--tw-modal-background-color]">
        <x-modal.header>
            <x-modal.heading-title>{{ $this->getModalTitle() }}</x-modal.heading-title>
        </x-modal.header>

        <x-modal.content>
            <x-wrestlers.form id="wrestlerForm"/>
        </x-modal.content>

        <x-modal.footer>
            <div class="flex gap-4">
                <x-buttons.secondary wire:click="closeModal">Cancel</x-buttons.secondary>
                <x-buttons.primary type="submit" form="wrestlerForm">Submit</x-buttons.primary>
            </div>
        </x-modal.footer>
    </div>
</div>
