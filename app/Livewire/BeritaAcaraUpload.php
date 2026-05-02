<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BeritaAcara;

class BeritaAcaraUpload extends Component
{
    use WithFileUploads;
    
    public $judul;
    public $konten;
    public $foto;
    
    protected $rules = [
        'judul' => 'required|string|max:255',
        'konten' => 'required|string',
        'foto' => 'required|image|max:10240', // 10MB
    ];
    
    public function save()
    {
        $this->validate();
        
        // Simpan file
        $path = $this->foto->store('berita-acara', 'public');
        
        // Simpan ke database
        BeritaAcara::create([
            'title' => $this->judul,
            'content' => $this->konten,
            'image_path' => $path,
        ]);
        
        session()->flash('message', 'Berita acara berhasil ditambahkan!');
        
        // Reset form
        $this->reset(['judul', 'konten', 'foto']);
        
        return redirect()->route('admin.berita-acara.index');
    }
    
    public function render()
    {
        return view('livewire.berita-acara-upload');
    }
}