<div class="p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Siswa: {{ $record->nama_lengkap }}</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($documents as $document)
            <div class="bg-gray-50 p-4 rounded-lg border @if(!$document['exists']) border-dashed border-gray-300 @endif">
                <h4 class="font-medium text-gray-700 mb-2">{{ $document['name'] }}</h4>
                
                @if($document['exists'])
                    @if($document['type'] === 'image')
                        <div class="mb-3">
                            <img src="{{ $document['url'] }}" alt="{{ $document['name'] }}" 
                                 class="w-full max-w-xs h-48 object-cover rounded-lg mx-auto">
                        </div>
                    @elseif($document['type'] === 'pdf')
                        <div class="mb-3 text-center">
                            <div class="inline-flex items-center justify-center w-48 h-48 bg-red-100 rounded-lg">
                                <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ $document['url'] }}" target="_blank" 
                       class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Dokumen
                    </a>
                @else
                    <p class="text-sm text-gray-500">Dokumen tidak tersedia</p>
                @endif
            </div>
        @endforeach
    </div>
    
    @if($record->siswaPendaftar)
        <div class="mt-6 pt-4 border-t">
            <h4 class="font-medium text-gray-700 mb-2">Data Pendaftaran Asli</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                <div>
                    <span class="font-medium">NIK:</span> {{ $record->siswaPendaftar->nik_decrypted }}
                </div>
                <div>
                    <span class="font-medium">Tanggal Lahir:</span> {{ $record->siswaPendaftar->formatted_tanggal_lahir }}
                </div>
                <div>
                    <span class="font-medium">Nama Ayah:</span> {{ $record->siswaPendaftar->nama_ayah }}
                </div>
                <div>
                    <span class="font-medium">Nama Ibu:</span> {{ $record->siswaPendaftar->nama_ibu }}
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium">Alamat:</span> {{ $record->siswaPendaftar->alamat_jalan }}
                </div>
            </div>
        </div>
    @endif
</div>