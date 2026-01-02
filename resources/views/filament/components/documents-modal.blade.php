<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($documents as $document)
            @if($document['exists'] && $document['url'])
                <div class="border rounded-lg overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h3 class="font-medium text-gray-900">{{ $document['name'] }}</h3>
                        <p class="text-sm text-gray-500 mt-1 truncate">{{ $document['path'] }}</p>
                    </div>
                    <div class="p-4">
                        @php
                            $extension = pathinfo($document['url'], PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp
                        
                        @if($isImage)
                            <div class="text-center">
                                <img src="{{ $document['url'] }}" 
                                     alt="{{ $document['name'] }}" 
                                     class="max-w-full h-auto mx-auto rounded-lg shadow-sm max-h-64">
                            </div>
                        @else
                            <div class="text-center p-8 bg-gray-50 rounded-lg">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-600 mb-2">File: {{ strtoupper($extension) }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-4 flex justify-center space-x-3">
                            <a href="{{ $document['url'] }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ $document['url'] }}" 
                               download 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="border rounded-lg overflow-hidden shadow-sm bg-gray-50">
                    <div class="bg-gray-100 px-4 py-3 border-b">
                        <h3 class="font-medium text-gray-700">{{ $document['name'] }}</h3>
                    </div>
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-gray-600">Dokumen tidak tersedia</p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="mt-6 pt-6 border-t">
        <div class="text-sm text-gray-600">
            <p><strong>Catatan:</strong> Semua dokumen disimpan di <code>storage/app/public/siswa/dokumen/</code></p>
            @if($record->akte_kelahiran_exists || $record->kartu_keluarga_exists || $record->kia_exists || $record->bpjs_exists)
                <p class="mt-2 text-green-600">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Semua dokumen tersedia dan dapat diakses
                </p>
            @endif
        </div>
    </div>
</div>