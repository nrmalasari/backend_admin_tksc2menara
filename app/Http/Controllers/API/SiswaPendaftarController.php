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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

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
     * Simpan file ke disk 'rahasia'
     */
    private function saveFile($file, $type)
    {
        if (!$file) {
            return null;
        }
        
        // Generate nama file unik
        $filename = $type . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Simpan ke storage/app/secure-files (disk 'rahasia')
        $path = $file->storeAs('', $filename, 'rahasia');
        
        Log::info('File saved to secure storage:', [
            'type' => $type,
            'filename' => $filename,
            'path' => $path,
            'disk' => 'rahasia'
        ]);
        
        return $filename; // Hanya return nama file, karena sudah ada di secure-files
    }

    /**
     * Mendapatkan URL untuk file dokumen (GANTI INI!)
     */
    private function getFileUrl($filename)
    {
        if (!$filename) {
            return null;
        }
        
        try {
            // Gunakan route proxy untuk semua akses file
            $url = route('ambil.file.rahasia', ['filename' => $filename]);
            Log::info('Generated route proxy URL: ' . $url);
            return $url;
        } catch (\Exception $e) {
            Log::error('Failed to generate route URL: ' . $e->getMessage());
            
            // Fallback ke URL langsung jika route tidak ada
            $fallbackUrl = url('/lihat-file/' . $filename);
            Log::info('Using fallback URL: ' . $fallbackUrl);
            return $fallbackUrl;
        }
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
            
            // Generate URL menggunakan route proxy /lihat-file/{filename}
            $akteUrl = $siswaPendaftar->akte_kelahiran_path 
                ? $this->getFileUrl($siswaPendaftar->akte_kelahiran_path)
                : null;
                
            $kkUrl = $siswaPendaftar->kartu_keluarga_path 
                ? $this->getFileUrl($siswaPendaftar->kartu_keluarga_path)
                : null;
                
            $kiaUrl = $siswaPendaftar->kia_path 
                ? $this->getFileUrl($siswaPendaftar->kia_path)
                : null;
                
            $bpjsUrl = $siswaPendaftar->bpjs_path 
                ? $this->getFileUrl($siswaPendaftar->bpjs_path)
                : null;
            
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
                
                // Dokumen dengan URL route proxy /lihat-file/{filename}
                'akte_kelahiran' => $akteUrl,
                'akte_kelahiran_url' => $akteUrl,
                'akte_kelahiran_exists' => $siswaPendaftar->akte_kelahiran_exists,
                'kartu_keluarga' => $kkUrl,
                'kartu_keluarga_url' => $kkUrl,
                'kartu_keluarga_exists' => $siswaPendaftar->kartu_keluarga_exists,
                'kia' => $kiaUrl,
                'kia_url' => $kiaUrl,
                'kia_exists' => $siswaPendaftar->kia_exists,
                'bpjs' => $bpjsUrl,
                'bpjs_url' => $bpjsUrl,
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
            // Jika sudah ada data dan status ditolak/revisi, arahkan untuk update
            if ($existingData->status === 'ditolak' || $existingData->status === 'revisi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki formulir yang perlu diperbaiki. Silakan edit data yang sudah ada.',
                    'requires_update' => true,
                    'existing_id' => $existingData->id
                ], 400);
            }
            
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
            
            // Upload file ke disk 'rahasia'
            $akteFilename = $this->saveFile($request->file('akte_kelahiran'), 'akte');
            $kkFilename = $this->saveFile($request->file('kartu_keluarga'), 'kk');
            $kiaFilename = $this->saveFile($request->file('kia'), 'kia');
            $bpjsFilename = $this->saveFile($request->file('bpjs'), 'bpjs');
            
            Log::info('Files uploaded to secure storage:', [
                'akte' => $akteFilename,
                'kk' => $kkFilename,
                'kia' => $kiaFilename,
                'bpjs' => $bpjsFilename
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
                
                // File paths - hanya nama file (disk 'rahasia')
                'akte_kelahiran_path' => $akteFilename,
                'kartu_keluarga_path' => $kkFilename,
                'kia_path' => $kiaFilename,
                'bpjs_path' => $bpjsFilename,
                
                // Status default
                'status' => 'menunggu',
                'catatan_admin' => null,
            ];
            
            Log::info('Data untuk disimpan:', $siswaData);
            
            $siswaPendaftar = SiswaPendaftar::create($siswaData);
            Log::info('SiswaPendaftar berhasil dibuat:', ['id' => $siswaPendaftar->id]);
            
            DB::commit();
            
            // Generate URL menggunakan route proxy /lihat-file/{filename}
            $akteUrl = $akteFilename ? $this->getFileUrl($akteFilename) : null;
            $kkUrl = $kkFilename ? $this->getFileUrl($kkFilename) : null;
            $kiaUrl = $kiaFilename ? $this->getFileUrl($kiaFilename) : null;
            $bpjsUrl = $bpjsFilename ? $this->getFileUrl($bpjsFilename) : null;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswaPendaftar->id,
                    'nama_lengkap' => $siswaPendaftar->nama_lengkap,
                    'status' => $siswaPendaftar->status,
                    'status_text' => $siswaPendaftar->status_text,
                    'akte_kelahiran' => $akteUrl,
                    'kartu_keluarga' => $kkUrl,
                    'kia' => $kiaUrl,
                    'bpjs' => $bpjsUrl,
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
        
        // Boleh update jika status menunggu, ditolak, atau revisi
        $allowedStatuses = ['menunggu', 'ditolak', 'revisi'];
        if (!in_array($siswaPendaftar->status, $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Formulir tidak dapat diubah karena sudah diproses atau diverifikasi'
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
            
            // Parameter khusus untuk reset status (dari frontend)
            'reset_status' => 'nullable|boolean',
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
            
            // Reset status ke "menunggu" jika formulir ditolak/revisi
            $isCorrection = ($siswaPendaftar->status === 'ditolak' || $siswaPendaftar->status === 'revisi');
            if ($isCorrection && ($request->has('reset_status') || !$request->has('reset_status'))) {
                $updateData['status'] = 'menunggu';
                $updateData['catatan_admin'] = null;
                Log::info('Reset status to "menunggu" for correction from status:', ['old_status' => $siswaPendaftar->status]);
            }
            
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
                        Storage::disk('rahasia')->delete($siswaPendaftar->$dbField);
                        Log::info('Deleted old file:', ['path' => $siswaPendaftar->$dbField, 'disk' => 'rahasia']);
                    }
                    
                    $file = $request->file($requestField);
                    $updateData[$dbField] = $this->saveFile($file, $requestField);
                    
                    Log::info('New file uploaded:', ['path' => $updateData[$dbField], 'disk' => 'rahasia']);
                }
            }
            
            Log::info('Data to update:', $updateData);
            
            $siswaPendaftar->update($updateData);
            
            DB::commit();
            
            $message = 'Formulir berhasil diperbarui';
            if ($isCorrection) {
                $message = 'Formulir berhasil diperbaiki dan status diubah menjadi "Menunggu Verifikasi"';
            }
            
            // Generate URL menggunakan route proxy /lihat-file/{filename}
            $akteUrl = $siswaPendaftar->akte_kelahiran_path 
                ? $this->getFileUrl($siswaPendaftar->akte_kelahiran_path)
                : null;
                
            $kkUrl = $siswaPendaftar->kartu_keluarga_path 
                ? $this->getFileUrl($siswaPendaftar->kartu_keluarga_path)
                : null;
                
            $kiaUrl = $siswaPendaftar->kia_path 
                ? $this->getFileUrl($siswaPendaftar->kia_path)
                : null;
                
            $bpjsUrl = $siswaPendaftar->bpjs_path 
                ? $this->getFileUrl($siswaPendaftar->bpjs_path)
                : null;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswaPendaftar->id,
                    'nama_lengkap' => $siswaPendaftar->nama_lengkap,
                    'status' => $siswaPendaftar->status,
                    'status_text' => $siswaPendaftar->status_text,
                    'akte_kelahiran' => $akteUrl,
                    'kartu_keluarga' => $kkUrl,
                    'kia' => $kiaUrl,
                    'bpjs' => $bpjsUrl,
                    'catatan_admin' => $siswaPendaftar->catatan_admin,
                    'updated_at' => $siswaPendaftar->updated_at->format('d/m/Y H:i'),
                ],
                'message' => $message
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
        
        // Boleh hapus jika status masih menunggu, ditolak, atau revisi
        $allowedStatuses = ['menunggu', 'ditolak', 'revisi'];
        if (!in_array($siswaPendaftar->status, $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Formulir tidak dapat dihapus karena sudah diproses atau diverifikasi'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // Hapus file-file dokumen dari disk 'rahasia'
            $fileFields = [
                'akte_kelahiran_path',
                'kartu_keluarga_path', 
                'kia_path',
                'bpjs_path'
            ];
            
            foreach ($fileFields as $field) {
                if ($siswaPendaftar->$field) {
                    Storage::disk('rahasia')->delete($siswaPendaftar->$field);
                    Log::info('Deleted file from secure storage:', ['field' => $field, 'path' => $siswaPendaftar->$field]);
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
                    // Generate URLs menggunakan route proxy
                    $akteUrl = $siswa->akte_kelahiran_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->akte_kelahiran_path])
                        : null;
                        
                    $kkUrl = $siswa->kartu_keluarga_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->kartu_keluarga_path])
                        : null;
                        
                    $kiaUrl = $siswa->kia_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->kia_path])
                        : null;
                        
                    $bpjsUrl = $siswa->bpjs_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->bpjs_path])
                        : null;
                    
                    return [
                        'id' => $siswa->id,
                        'username' => $siswa->registPendaftar?->username,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nik' => $siswa->nik_decrypted,
                        'akte_raw' => $siswa->akte_kelahiran_path,
                        'akte_url' => $akteUrl,
                        'akte_exists' => $siswa->akte_kelahiran_exists,
                        'kk_raw' => $siswa->kartu_keluarga_path,
                        'kk_url' => $kkUrl,
                        'kk_exists' => $siswa->kartu_keluarga_exists,
                        'kia_raw' => $siswa->kia_path,
                        'kia_url' => $kiaUrl,
                        'kia_exists' => $siswa->kia_exists,
                        'bpjs_raw' => $siswa->bpjs_path,
                        'bpjs_url' => $bpjsUrl,
                        'bpjs_exists' => $siswa->bpjs_exists,
                        'status' => $siswa->status,
                        'catatan_admin' => $siswa->catatan_admin,
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
            
            $akteUrl = $siswa->akte_kelahiran_path 
                ? route('ambil.file.rahasia', ['filename' => $siswa->akte_kelahiran_path])
                : null;
                
            $kkUrl = $siswa->kartu_keluarga_path 
                ? route('ambil.file.rahasia', ['filename' => $siswa->kartu_keluarga_path])
                : null;
                
            $kiaUrl = $siswa->kia_path 
                ? route('ambil.file.rahasia', ['filename' => $siswa->kia_path])
                : null;
                
            $bpjsUrl = $siswa->bpjs_path 
                ? route('ambil.file.rahasia', ['filename' => $siswa->bpjs_path])
                : null;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    
                    // Akte Kelahiran
                    'akte_database' => $siswa->akte_kelahiran_path,
                    'akte_url' => $akteUrl,
                    'akte_exists' => $siswa->akte_kelahiran_exists,
                    'storage_path' => storage_path('app/secure-files/' . $siswa->akte_kelahiran_path),
                    'web_url' => $akteUrl,
                    
                    // Kartu Keluarga
                    'kk_database' => $siswa->kartu_keluarga_path,
                    'kk_url' => $kkUrl,
                    'kk_exists' => $siswa->kartu_keluarga_exists,
                    
                    // KIA
                    'kia_database' => $siswa->kia_path,
                    'kia_url' => $kiaUrl,
                    'kia_exists' => $siswa->kia_exists,
                    
                    // BPJS
                    'bpjs_database' => $siswa->bpjs_path,
                    'bpjs_url' => $bpjsUrl,
                    'bpjs_exists' => $siswa->bpjs_exists,
                    
                    'storage_files' => Storage::disk('rahasia')->files(''),
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
                    // Generate URLs untuk test
                    $akteUrl = $siswa->akte_kelahiran_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->akte_kelahiran_path])
                        : null;
                        
                    $kkUrl = $siswa->kartu_keluarga_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->kartu_keluarga_path])
                        : null;
                        
                    $kiaUrl = $siswa->kia_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->kia_path])
                        : null;
                        
                    $bpjsUrl = $siswa->bpjs_path 
                        ? route('ambil.file.rahasia', ['filename' => $siswa->bpjs_path])
                        : null;
                    
                    return [
                        'id' => $siswa->id,
                        'regist_pendaftar_id' => $siswa->regist_pendaftar_id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nik' => $siswa->nik_decrypted,
                        'akte_kelahiran' => $akteUrl,
                        'kartu_keluarga' => $kkUrl,
                        'kia' => $kiaUrl,
                        'bpjs' => $bpjsUrl,
                        'status' => $siswa->status,
                        'status_text' => $siswa->status_text,
                        'catatan_admin' => $siswa->catatan_admin,
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
                    'akte_kelahiran' => null, // Tidak ada file untuk test
                    'kartu_keluarga' => null,
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

    /**
     * Migrate file dari siswa/dokumen/ ke secure-files/
     */
    public function migrateFiles(Request $request)
    {
        try {
            Log::info('=== MIGRATE FILES METHOD CALLED ===');
            
            $siswaPendaftars = SiswaPendaftar::all();
            $migratedCount = 0;
            
            foreach ($siswaPendaftars as $siswa) {
                $fileFields = [
                    'akte_kelahiran_path',
                    'kartu_keluarga_path',
                    'kia_path',
                    'bpjs_path',
                ];
                
                $updated = false;
                
                foreach ($fileFields as $field) {
                    $oldPath = $siswa->$field;
                    
                    // Cek jika path mengandung siswa/dokumen/ atau public/
                    if ($oldPath && (strpos($oldPath, 'siswa/dokumen/') !== false || strpos($oldPath, 'public/') !== false)) {
                        $filename = basename($oldPath);
                        
                        // Cek jika file ada di lokasi lama (public disk)
                        $oldCleanPath = str_replace(['storage/', 'public/'], '', $oldPath);
                        
                        if (Storage::disk('public')->exists($oldCleanPath)) {
                            // Pindahkan file dari public ke rahasia
                            $fileContent = Storage::disk('public')->get($oldCleanPath);
                            Storage::disk('rahasia')->put($filename, $fileContent);
                            
                            // Hapus file lama dari public
                            Storage::disk('public')->delete($oldCleanPath);
                            
                            Log::info("Migrated file: {$oldPath} → secure-files/{$filename}");
                        }
                        
                        // Update path di database (hanya nama file)
                        $siswa->$field = $filename;
                        $updated = true;
                    }
                }
                
                if ($updated) {
                    $siswa->save();
                    $migratedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Migrasi selesai. {$migratedCount} data diperbarui.",
                'migrated_count' => $migratedCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Migrate files error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memigrasi file: ' . $e->getMessage()
            ], 500);
        }
    }
}