<div>


    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif
    <input type="file" wire:model="file">
    @error('file') <span class="text-red-600">{{ $message }}</span> @enderror

    <button wire:click="import" class="bg-blue-600 text-white px-4 py-2 mt-2">Importer</button>
</div>
