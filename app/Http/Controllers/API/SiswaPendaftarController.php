<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiswaPendaftar;
use App\Models\RegistPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // TAMBAHKAN INI

class SiswaPendaftarController extends Controller
{
    /**
     * Mendapatkan user ID dari request
     */
    private function getUserId(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            if (is_numeric($token)) {
                return (int)$token;
            }
        }
        
        if ($request->filled('user_id')) {
            return (int)$request->input('user_id');
        }
        
        if ($request->filled('user')) {
            return (int)$request->query('user');
        }
        
        if ($request->filled('regist_pendaftar_id')) {
            return (int)$request->input('regist_pendaftar_id');
        }
        
        return null;
    }

    /**
     * Mendapatkan semua data siswa pendaftar user
     */
    public function index(Request $request)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }
        
        try {
            $siswaPendaftar = SiswaPendaftar::where('regist_pendaftar_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$siswaPendaftar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data formulir pendaftaran belum ada'
                ], 404);
            }
            
            $data = [
                'id' => $siswaPendaftar->id,
                'regist_pendaftar_id' => $siswaPendaftar->regist_pendaftar_id,
                
                // Data Anak
                'nama_lengkap' => $siswaPendaftar->nama_lengkap,
                'nik' => $siswaPendaftar->nik_decrypted,
                'tempat_lahir' => $siswaPendaftar->tempat_lahir,
                'tanggal_lahir' => $siswaPendaftar->tanggal_lahir,
                'formatted_tanggal_lahir' => $siswaPendaftar->formatted_tanggal_lahir,
                'agama' => $siswaPendaftar->agama,
                'jenis_kelamin' => $siswaPendaftar->jenis_kelamin,
                'usia' => $siswaPendaftar->usia,
                'alamat_jalan' => $siswaPendaftar->alamat_jalan,
                'rt' => $siswaPendaftar->rt,
                'rw' => $siswaPendaftar->rw,
                'kelurahan' => $siswaPendaftar->kelurahan,
                'kecamatan' => $siswaPendaftar->kecamatan,
                'kota' => $siswaPendaftar->kota,
                'kode_pos' => $siswaPendaftar->kode_pos,
                'tinggi_badan' => $siswaPendaftar->tinggi_badan,
                'berat_badan' => $siswaPendaftar->berat_badan,
                'jumlah_saudara' => $siswaPendaftar->jumlah_saudara,
                'jarak_sekolah' => (float)$siswaPendaftar->jarak_sekolah,
                'waktu_tempuh' => $siswaPendaftar->waktu_tempuh,
                
                // Data Orang Tua
                'nama_ayah' => $siswaPendaftar->nama_ayah,
                'nama_ibu' => $siswaPendaftar->nama_ibu,
                'nik_ayah' => $siswaPendaftar->nik_ayah_decrypted,
                'nik_ibu' => $siswaPendaftar->nik_ibu_decrypted,
                'tempat_lahir_ayah' => $siswaPendaftar->tempat_lahir_ayah,
                'tempat_lahir_ibu' => $siswaPendaftar->tempat_lahir_ibu,
                'tanggal_lahir_ayah' => $siswaPendaftar->tanggal_lahir_ayah,
                'tanggal_lahir_ibu' => $siswaPendaftar->tanggal_lahir_ibu,
                'pendidikan_ayah' => $siswaPendaftar->pendidikan_ayah,
                'pendidikan_ibu' => $siswaPendaftar->pendidikan_ibu,
                'pekerjaan_ayah' => $siswaPendaftar->pekerjaan_ayah,
                'pekerjaan_ibu' => $siswaPendaftar->pekerjaan_ibu,
                'alamat_ayah' => $siswaPendaftar->alamat_ayah,
                'alamat_ibu' => $siswaPendaftar->alamat_ibu,
                'no_telp' => $siswaPendaftar->no_telp_decrypted,
                'penghasilan' => (float)$siswaPendaftar->penghasilan_decrypted,
                'formatted_penghasilan' => $siswaPendaftar->formatted_penghasilan,
                
                // Dokumen dengan URL yang benar
                'akte_kelahiran' => $siswaPendaftar->akte_kelahiran_url,
                'akte_kelahiran_exists' => $siswaPendaftar->akte_kelahiran_exists,
                'kartu_keluarga' => $siswaPendaftar->kartu_keluarga_url,
                'kartu_keluarga_exists' => $siswaPendaftar->kartu_keluarga_exists,
                'kia' => $siswaPendaftar->kia_url,
                'kia_exists' => $siswaPendaftar->kia_exists,
                'bpjs' => $siswaPendaftar->bpjs_url,
                'bpjs_exists' => $siswaPendaftar->bpjs_exists,
                
                // Status
                'status' => $siswaPendaftar->status,
                'status_text' => $siswaPendaftar->status_text,
                'status_color' => $siswaPendaftar->status_color,
                'catatan_admin' => $siswaPendaftar->catatan_admin,
                
                'created_at' => $siswaPendaftar->created_at->format('d/m/Y H:i'),
                'updated_at' => $siswaPendaftar->updated_at->format('d/m/Y H:i'),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data formulir pendaftaran berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data formulir pendaftaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan formulir pendaftaran baru
     */
    public function store(Request $request)
    {
        Log::info('=== STORE METHOD SISWA PENDAFTAR CALLED ===');
        Log::info('Headers:', $request->headers->all());
        Log::info('All Input Data:', $request->all());
        Log::info('Files:', array_keys($request->files->all()));
        
        $userId = $this->getUserId($request);
        Log::info('User ID from request:', ['user_id' => $userId]);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }
        
        // Cek apakah user sudah memiliki data pendaftaran
        $existingData = SiswaPendaftar::where('regist_pendaftar_id', $userId)->first();
        if ($existingData) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengisi formulir pendaftaran. Silakan edit data yang sudah ada.'
            ], 400);
        }
        
        // Validasi untuk CREATE (semua field required)
        $validator = Validator::make($request->all(), [
            // Data Anak
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'usia' => 'required|integer|min:0',
            'alamat_jalan' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'tinggi_badan' => 'required|integer|min:0',
            'berat_badan' => 'required|integer|min:0',
            'jumlah_saudara' => 'required|integer|min:0',
            'jarak_sekolah' => 'required|numeric|min:0',
            'waktu_tempuh' => 'required|integer|min:0',
            
            // Data Orang Tua
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nik_ayah' => 'required|string|max:20',
            'nik_ibu' => 'required|string|max:20',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date',
            'tanggal_lahir_ibu' => 'required|date',
            'pendidikan_ayah' => 'required|string|max:50',
            'pendidikan_ibu' => 'required|string|max:50',
            'pekerjaan_ayah' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'alamat_ayah' => 'required|string',
            'alamat_ibu' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'penghasilan' => 'required|numeric|min:0',
            
            // File Upload (required untuk create)
            'akte_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kia' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bpjs' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validasi gagal:', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi formulir gagal'
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            // Cek apakah user ada
            $user = RegistPendaftar::find($userId);
            if (!$user) {
                Log::error('User tidak ditemukan:', ['user_id' => $userId]);
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            
            Log::info('User ditemukan:', ['user' => $user->username]);
            
            // Upload file dengan cara yang sama seperti pembayaran
            $uploadFiles = function($file, $type) {
                if ($file) {
                    // Generate nama file unik seperti di pembayaran
                    $filename = $type . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan ke storage
                    $path = $file->storeAs('siswa/dokumen', $filename, 'public');
                    
                    // Simpan path lengkap ke database
                    return 'siswa/dokumen/' . $filename;
                }
                return null;
            };
            
            $aktePath = $uploadFiles($request->file('akte_kelahiran'), 'akte');
            $kkPath = $uploadFiles($request->file('kartu_keluarga'), 'kk');
            $kiaPath = $uploadFiles($request->file('kia'), 'kia');
            $bpjsPath = $uploadFiles($request->file('bpjs'), 'bpjs');
            
            Log::info('Files uploaded:', [
                'akte' => $aktePath,
                'kk' => $kkPath,
                'kia' => $kiaPath,
                'bpjs' => $bpjsPath
            ]);
            
            // Data untuk disimpan
            $siswaData = [
                'regist_pendaftar_id' => $userId,
                
                // Data Anak
                'nama_lengkap' => $request->input('nama_lengkap'),
                'nik' => $request->input('nik'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'agama' => $request->input('agama'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'usia' => (int)$request->input('usia'),
                'alamat_jalan' => $request->input('alamat_jalan'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'kelurahan' => $request->input('kelurahan'),
                'kecamatan' => $request->input('kecamatan'),
                'kota' => $request->input('kota'),
                'kode_pos' => $request->input('kode_pos'),
                'tinggi_badan' => (int)$request->input('tinggi_badan'),
                'berat_badan' => (int)$request->input('berat_badan'),
                'jumlah_saudara' => (int)$request->input('jumlah_saudara'),
                'jarak_sekolah' => (float)$request->input('jarak_sekolah'),
                'waktu_tempuh' => (int)$request->input('waktu_tempuh'),
                
                // Data Orang Tua
                'nama_ayah' => $request->input('nama_ayah'),
                'nama_ibu' => $request->input('nama_ibu'),
                'nik_ayah' => $request->input('nik_ayah'),
                'nik_ibu' => $request->input('nik_ibu'),
                'tempat_lahir_ayah' => $request->input('tempat_lahir_ayah'),
                'tempat_lahir_ibu' => $request->input('tempat_lahir_ibu'),
                'tanggal_lahir_ayah' => $request->input('tanggal_lahir_ayah'),
                'tanggal_lahir_ibu' => $request->input('tanggal_lahir_ibu'),
                'pendidikan_ayah' => $request->input('pendidikan_ayah'),
                'pendidikan_ibu' => $request->input('pendidikan_ibu'),
                'pekerjaan_ayah' => $request->input('pekerjaan_ayah'),
                'pekerjaan_ibu' => $request->input('pekerjaan_ibu'),
                'alamat_ayah' => $request->input('alamat_ayah'),
                'alamat_ibu' => $request->input('alamat_ibu'),
                'no_telp' => $request->input('no_telp'),
                'penghasilan' => (float)$request->input('penghasilan'),
                
                // File paths
                'akte_kelahiran_path' => $aktePath,
                'kartu_keluarga_path' => $kkPath,
                'kia_path' => $kiaPath,
                'bpjs_path' => $bpjsPath,
                
                // Status default
                'status' => 'menunggu',
                'catatan_admin' => null,
            ];
            
            Log::info('Data untuk disimpan:', $siswaData);
            
            $siswaPendaftar = SiswaPendaftar::create($siswaData);
            Log::info('SiswaPendaftar berhasil dibuat:', ['id' => $siswaPendaftar->id]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswaPendaftar->id,
                    'nama_lengkap' => $siswaPendaftar->nama_lengkap,
                    'status' => $siswaPendaftar->status,
                    'status_text' => $siswaPendaftar->status_text,
                    'akte_kelahiran' => $siswaPendaftar->akte_kelahiran_url,
                    'kartu_keluarga' => $siswaPendaftar->kartu_keluarga_url,
                    'kia' => $siswaPendaftar->kia_url,
                    'bpjs' => $siswaPendaftar->bpjs_url,
                    'created_at' => $siswaPendaftar->created_at->format('d/m/Y H:i'),
                ],
                'message' => 'Formulir pendaftaran berhasil disimpan. Silakan tunggu verifikasi dari admin.'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error menyimpan formulir pendaftaran:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan formulir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update formulir pendaftaran
     */
    public function update(Request $request, $id)
    {
        Log::info('=== UPDATE METHOD SISWA PENDAFTAR CALLED ===');
        Log::info('ID to update:', ['id' => $id]);
        Log::info('Request data:', $request->all());
        Log::info('Files:', array_keys($request->files->all()));
        
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }
        
        $siswaPendaftar = SiswaPendaftar::where('regist_pendaftar_id', $userId)
            ->where('id', $id)
            ->first();
        
        if (!$siswaPendaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Data formulir tidak ditemukan'
            ], 404);
        }
        
        // Hanya bisa update jika status masih menunggu
        if ($siswaPendaftar->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Formulir tidak dapat diubah karena sudah diproses'
            ], 400);
        }
        
        // Validasi untuk update (file opsional)
        $validator = Validator::make($request->all(), [
            // Data Anak
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'usia' => 'required|integer|min:0',
            'alamat_jalan' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'tinggi_badan' => 'required|integer|min:0',
            'berat_badan' => 'required|integer|min:0',
            'jumlah_saudara' => 'required|integer|min:0',
            'jarak_sekolah' => 'required|numeric|min:0',
            'waktu_tempuh' => 'required|integer|min:0',
            
            // Data Orang Tua
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nik_ayah' => 'required|string|max:20',
            'nik_ibu' => 'required|string|max:20',
            'tempat_lahir_ayah' => 'required|string|max:100',
            'tempat_lahir_ibu' => 'required|string|max:100',
            'tanggal_lahir_ayah' => 'required|date',
            'tanggal_lahir_ibu' => 'required|date',
            'pendidikan_ayah' => 'required|string|max:50',
            'pendidikan_ibu' => 'required|string|max:50',
            'pekerjaan_ayah' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'alamat_ayah' => 'required|string',
            'alamat_ibu' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'penghasilan' => 'required|numeric|min:0',
            
            // File Upload (opsional untuk update)
            'akte_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kia' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bpjs' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validasi update gagal:', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $updateData = [
                // Data Anak
                'nama_lengkap' => $request->input('nama_lengkap'),
                'nik' => $request->input('nik'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'agama' => $request->input('agama'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'usia' => (int)$request->input('usia'),
                'alamat_jalan' => $request->input('alamat_jalan'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'kelurahan' => $request->input('kelurahan'),
                'kecamatan' => $request->input('kecamatan'),
                'kota' => $request->input('kota'),
                'kode_pos' => $request->input('kode_pos'),
                'tinggi_badan' => (int)$request->input('tinggi_badan'),
                'berat_badan' => (int)$request->input('berat_badan'),
                'jumlah_saudara' => (int)$request->input('jumlah_saudara'),
                'jarak_sekolah' => (float)$request->input('jarak_sekolah'),
                'waktu_tempuh' => (int)$request->input('waktu_tempuh'),
                
                // Data Orang Tua
                'nama_ayah' => $request->input('nama_ayah'),
                'nama_ibu' => $request->input('nama_ibu'),
                'nik_ayah' => $request->input('nik_ayah'),
                'nik_ibu' => $request->input('nik_ibu'),
                'tempat_lahir_ayah' => $request->input('tempat_lahir_ayah'),
                'tempat_lahir_ibu' => $request->input('tempat_lahir_ibu'),
                'tanggal_lahir_ayah' => $request->input('tanggal_lahir_ayah'),
                'tanggal_lahir_ibu' => $request->input('tanggal_lahir_ibu'),
                'pendidikan_ayah' => $request->input('pendidikan_ayah'),
                'pendidikan_ibu' => $request->input('pendidikan_ibu'),
                'pekerjaan_ayah' => $request->input('pekerjaan_ayah'),
                'pekerjaan_ibu' => $request->input('pekerjaan_ibu'),
                'alamat_ayah' => $request->input('alamat_ayah'),
                'alamat_ibu' => $request->input('alamat_ibu'),
                'no_telp' => $request->input('no_telp'),
                'penghasilan' => (float)$request->input('penghasilan'),
            ];
            
            // Handle file upload jika ada
            $fileFields = [
                'akte_kelahiran' => 'akte_kelahiran_path',
                'kartu_keluarga' => 'kartu_keluarga_path',
                'kia' => 'kia_path',
                'bpjs' => 'bpjs_path',
            ];
            
            foreach ($fileFields as $requestField => $dbField) {
                if ($request->hasFile($requestField)) {
                    Log::info('Uploading file:', ['field' => $requestField]);
                    
                    // Hapus file lama jika ada
                    if ($siswaPendaftar->$dbField) {
                        // Hapus path relatif dari storage
                        $cleanPath = $siswaPendaftar->$dbField;
                        if (strpos($cleanPath, 'storage/') === 0) {
                            $cleanPath = substr($cleanPath, 8);
                        }
                        if (strpos($cleanPath, 'public/') === 0) {
                            $cleanPath = substr($cleanPath, 7);
                        }
                        
                        Storage::disk('public')->delete($cleanPath);
                        Log::info('Deleted old file:', ['path' => $cleanPath]);
                    }
                    
                    $file = $request->file($requestField);
                    
                    // Generate nama file unik seperti di pembayaran
                    $filename = $requestField . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan ke storage
                    $path = $file->storeAs('siswa/dokumen', $filename, 'public');
                    
                    // Simpan path lengkap ke database
                    $updateData[$dbField] = 'siswa/dokumen/' . $filename;
                    
                    Log::info('New file uploaded:', ['path' => $path, 'db_value' => $updateData[$dbField]]);
                }
            }
            
            Log::info('Data to update:', $updateData);
            
            $siswaPendaftar->update($updateData);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswaPendaftar->id,
                    'nama_lengkap' => $siswaPendaftar->nama_lengkap,
                    'status' => $siswaPendaftar->status,
                    'status_text' => $siswaPendaftar->status_text,
                    'akte_kelahiran' => $siswaPendaftar->akte_kelahiran_url,
                    'kartu_keluarga' => $siswaPendaftar->kartu_keluarga_url,
                    'kia' => $siswaPendaftar->kia_url,
                    'bpjs' => $siswaPendaftar->bpjs_url,
                    'updated_at' => $siswaPendaftar->updated_at->format('d/m/Y H:i'),
                ],
                'message' => 'Formulir berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error update formulir: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui formulir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus formulir pendaftaran
     */
    public function destroy(Request $request, $id)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }
        
        $siswaPendaftar = SiswaPendaftar::where('regist_pendaftar_id', $userId)
            ->where('id', $id)
            ->first();
        
        if (!$siswaPendaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Data formulir tidak ditemukan'
            ], 404);
        }
        
        // Hanya bisa hapus jika status masih menunggu
        if ($siswaPendaftar->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Formulir tidak dapat dihapus karena sudah diproses'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // Hapus file-file dokumen
            $fileFields = [
                'akte_kelahiran_path',
                'kartu_keluarga_path', 
                'kia_path',
                'bpjs_path'
            ];
            
            foreach ($fileFields as $field) {
                if ($siswaPendaftar->$field) {
                    // Hapus path relatif dari storage
                    $cleanPath = $siswaPendaftar->$field;
                    if (strpos($cleanPath, 'storage/') === 0) {
                        $cleanPath = substr($cleanPath, 8);
                    }
                    if (strpos($cleanPath, 'public/') === 0) {
                        $cleanPath = substr($cleanPath, 7);
                    }
                    
                    Storage::disk('public')->delete($cleanPath);
                    Log::info('Deleted file:', ['field' => $field, 'path' => $cleanPath]);
                }
            }
            
            $siswaPendaftar->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Formulir berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error delete formulir: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus formulir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk debugging - lihat semua data
     */
    public function debugAll()
    {
        try {
            Log::info('=== DEBUG ALL SISWA PENDAFTAR METHOD CALLED ===');
            
            $siswaPendaftars = SiswaPendaftar::with('registPendaftar')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'username' => $siswa->registPendaftar?->username,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nik' => $siswa->nik_decrypted,
                        'akte_raw' => $siswa->akte_kelahiran_path,
                        'akte_url' => $siswa->akte_kelahiran_url,
                        'akte_exists' => $siswa->akte_kelahiran_exists,
                        'kk_raw' => $siswa->kartu_keluarga_path,
                        'kk_url' => $siswa->kartu_keluarga_url,
                        'kk_exists' => $siswa->kartu_keluarga_exists,
                        'status' => $siswa->status,
                        'created_at' => $siswa->created_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $siswaPendaftars,
                'message' => 'Debug data siswa pendaftar'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Debug all error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk cek file dokumen
     */
    public function checkFiles($id)
    {
        try {
            $siswa = SiswaPendaftar::find($id);
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    
                    // Akte Kelahiran
                    'akte_database' => $siswa->akte_kelahiran_path,
                    'akte_url' => $siswa->akte_kelahiran_url,
                    'akte_exists' => $siswa->akte_kelahiran_exists,
                    
                    // Kartu Keluarga
                    'kk_database' => $siswa->kartu_keluarga_path,
                    'kk_url' => $siswa->kartu_keluarga_url,
                    'kk_exists' => $siswa->kartu_keluarga_exists,
                    
                    // KIA
                    'kia_database' => $siswa->kia_path,
                    'kia_url' => $siswa->kia_url,
                    'kia_exists' => $siswa->kia_exists,
                    
                    // BPJS
                    'bpjs_database' => $siswa->bpjs_path,
                    'bpjs_url' => $siswa->bpjs_url,
                    'bpjs_exists' => $siswa->bpjs_exists,
                    
                    'storage_files' => Storage::disk('public')->files('siswa/dokumen'),
                ],
                'message' => 'File check completed'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk testing - get semua data tanpa auth
     */
    public function testIndex()
    {
        try {
            Log::info('=== TEST INDEX SISWA PENDAFTAR METHOD CALLED ===');
            
            $siswaPendaftars = SiswaPendaftar::orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'regist_pendaftar_id' => $siswa->regist_pendaftar_id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nik' => $siswa->nik_decrypted,
                        'akte_kelahiran' => $siswa->akte_kelahiran_url,
                        'kartu_keluarga' => $siswa->kartu_keluarga_url,
                        'kia' => $siswa->kia_url,
                        'bpjs' => $siswa->bpjs_url,
                        'status' => $siswa->status,
                        'status_text' => $siswa->status_text,
                        'created_at' => $siswa->created_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $siswaPendaftars,
                'message' => 'Data formulir pendaftaran test berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk testing - create data tanpa auth
     */
    public function testStore(Request $request)
    {
        try {
            Log::info('=== TEST STORE SISWA PENDAFTAR METHOD CALLED ===');
            Log::info('Test request data:', $request->all());
            
            // Cari user pertama atau buat dummy
            $user = RegistPendaftar::first();
            if (!$user) {
                // Buat dummy user untuk testing
                $user = RegistPendaftar::create([
                    'username' => 'testuser',
                    'email' => 'test@example.com',
                    'password' => bcrypt('password123')
                ]);
            }
            
            $siswaData = [
                'regist_pendaftar_id' => $user->id,
                
                // Data Anak
                'nama_lengkap' => 'Test Siswa',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Test Kota',
                'tanggal_lahir' => '2020-01-01',
                'agama' => 'islam',
                'jenis_kelamin' => 'laki-laki',
                'usia' => 5,
                'alamat_jalan' => 'Jalan Test',
                'rt' => '001',
                'rw' => '002',
                'kelurahan' => 'Test Kelurahan',
                'kecamatan' => 'Test Kecamatan',
                'kota' => 'Test Kota',
                'kode_pos' => '12345',
                'tinggi_badan' => 110,
                'berat_badan' => 20,
                'jumlah_saudara' => 2,
                'jarak_sekolah' => 2.5,
                'waktu_tempuh' => 15,
                
                // Data Orang Tua
                'nama_ayah' => 'Test Ayah',
                'nama_ibu' => 'Test Ibu',
                'nik_ayah' => '1111111111111111',
                'nik_ibu' => '2222222222222222',
                'tempat_lahir_ayah' => 'Test Kota Ayah',
                'tempat_lahir_ibu' => 'Test Kota Ibu',
                'tanggal_lahir_ayah' => '1990-01-01',
                'tanggal_lahir_ibu' => '1991-01-01',
                'pendidikan_ayah' => 's1',
                'pendidikan_ibu' => 'sma',
                'pekerjaan_ayah' => 'PNS',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'alamat_ayah' => 'Alamat Ayah',
                'alamat_ibu' => 'Alamat Ibu',
                'no_telp' => '081234567890',
                'penghasilan' => 5000000,
                
                // Status
                'status' => 'menunggu',
                'catatan_admin' => null,
            ];
            
            $siswaPendaftar = SiswaPendaftar::create($siswaData);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswaPendaftar->id,
                    'akte_kelahiran' => $siswaPendaftar->akte_kelahiran_url,
                    'kartu_keluarga' => $siswaPendaftar->kartu_keluarga_url,
                ],
                'message' => 'Formulir test berhasil disimpan'
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Test store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan formulir test: ' . $e->getMessage()
            ], 500);
        }
    }
}