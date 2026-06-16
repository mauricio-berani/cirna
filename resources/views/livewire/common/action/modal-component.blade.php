<x-modal wire:model="modal" class="backdrop-blur">
    <div class="my-8">{{ $modalText }}</div>
    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
        <x-button label="{{ __('interface.cancel_button') }}" class="btn bg-error text-white w-full sm:w-auto" wire:click="closeModal" />
        <x-button label="{{ __('interface.continue_button') }}" class="btn-primary text-white w-full sm:w-auto" spinner wire:click="confirmAction" />
    </div>
</x-modal>
