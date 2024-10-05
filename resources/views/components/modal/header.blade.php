<div class="flex items-center justify-between border-gray-200 border border-b py-2.5 px-5">
    <h3 class="text-sm font-semibold text-gray-900">{{ $this->getModalTitle() }}</h3>
    <button
        class="inline-flex items-center cursor-pointer leading-4 rounded-md border border-solid w-7 h-7 justify-center shrink-0 p-0 gap-0 font-medium text-base text-gray-700 border-gray-300 bg-light"
        wire:click="closeModal">
        <i class="ki-outline ki-cross"></i>
    </button>
</div>
