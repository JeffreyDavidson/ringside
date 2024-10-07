<x-modal>
    <x-modal.header>
        <x-modal.heading-title>{{ $this->getModalTitle() }}</x-modal.heading-title>
    </x-modal.header>

    <x-modal.content>
        <x-events.form id="eventForm"/>
    </x-modal.content>

    <x-modal.footer>
        <div class="flex gap-4">
            <x-buttons.secondary wire:click="closeModal">Cancel</x-buttons.secondary>
            <x-buttons.primary type="submit" form="eventForm">Submit</x-buttons.primary>
        </div>
    </x-modal.footer>
</x-modal>
