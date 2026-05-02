<div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" class="form-control" wire:model="judul">
            @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Konten</label>
            <textarea class="form-control" wire:model="konten" rows="5"></textarea>
            @error('konten') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" wire:model="foto">
            
            @if ($foto)
                <div class="mt-2">
                    <img src="{{ $foto->temporaryUrl() }}" style="max-width: 300px;">
                </div>
            @endif
            
            @error('foto') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>