<?php
// app/Http/Controllers/API/RincianPembayaranController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RincianPembayaran;
use Illuminate\Http\Request;

class RincianPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rincian = RincianPembayaran::active()
                ->ordered()
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_rincian_pembayaran' => $item->nama_rincian_pembayaran,
                        'total_harga' => (float) $item->total_harga,
                        'formatted_total_harga' => $item->formatted_total_harga,
                        'deskripsi' => $item->deskripsi,
                        'jenis' => $item->jenis,
                        'jenis_label' => $item->jenis_label,
                        'urutan' => $item->urutan,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $rincian,
                'message' => 'Data rincian pembayaran berhasil diambil',
                'total' => $rincian->count(),
                'total_harga' => $rincian->sum('total_harga'),
                'formatted_total_harga' => 'Rp ' . number_format($rincian->sum('total_harga'), 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data rincian pembayaran: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $rincian = RincianPembayaran::active()->find($id);

            if (!$rincian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rincian pembayaran tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rincian->id,
                    'nama_rincian_pembayaran' => $rincian->nama_rincian_pembayaran,
                    'total_harga' => (float) $rincian->total_harga,
                    'formatted_total_harga' => $rincian->formatted_total_harga,
                    'deskripsi' => $rincian->deskripsi,
                    'jenis' => $rincian->jenis,
                    'jenis_label' => $rincian->jenis_label,
                    'urutan' => $rincian->urutan,
                    'is_active' => $rincian->is_active,
                ],
                'message' => 'Data rincian pembayaran berhasil diambil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data rincian pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get summary of payment details
     */
    public function summary()
    {
        try {
            $rincian = RincianPembayaran::active()->ordered()->get();
            $total = $rincian->sum('total_harga');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_items' => $rincian->count(),
                    'total_harga' => (float) $total,
                    'formatted_total_harga' => 'Rp ' . number_format($total, 0, ',', '.'),
                    'items' => $rincian->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'nama' => $item->nama_rincian_pembayaran,
                            'harga' => (float) $item->total_harga,
                            'formatted_harga' => $item->formatted_total_harga,
                            'deskripsi' => $item->deskripsi,
                        ];
                    }),
                ],
                'message' => 'Ringkasan rincian pembayaran berhasil diambil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ringkasan rincian pembayaran: ' . $e->getMessage(),
                'data' => [
                    'total_items' => 0,
                    'total_harga' => 0,
                    'formatted_total_harga' => 'Rp 0',
                    'items' => [],
                ]
            ], 500);
        }
    }
}