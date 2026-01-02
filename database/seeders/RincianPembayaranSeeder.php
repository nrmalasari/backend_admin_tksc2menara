<?php

namespace Database\Seeders;

use App\Models\RincianPembayaran;
use Illuminate\Database\Seeder;

class RincianPembayaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_rincian_pembayaran' => 'Biaya Pendaftaran',
                'total_harga' => 250000,
                'deskripsi' => 'Dibayar sekali saat pendaftaran',
                'jenis' => 'formulir',
                'urutan' => 1,
            ],
            [
                'nama_rincian_pembayaran' => 'Uang Pangkal',
                'total_harga' => 5000000,
                'deskripsi' => 'Dibayar sekali saat diterima',
                'jenis' => 'uang_bangunan',
                'urutan' => 2,
            ],
            [
                'nama_rincian_pembayaran' => 'SPP Bulanan',
                'total_harga' => 750000,
                'deskripsi' => 'Dibayar setiap bulan',
                'jenis' => 'lainnya',
                'urutan' => 3,
            ],
            [
                'nama_rincian_pembayaran' => 'Seragam Sekolah',
                'total_harga' => 1200000,
                'deskripsi' => 'Paket lengkap (4 stel)',
                'jenis' => 'seragam',
                'urutan' => 4,
            ],
            [
                'nama_rincian_pembayaran' => 'Buku Paket',
                'total_harga' => 850000,
                'deskripsi' => 'Untuk satu tahun ajaran',
                'jenis' => 'buku',
                'urutan' => 5,
            ],
            [
                'nama_rincian_pembayaran' => 'Baju Batik',
                'total_harga' => 200000,
                'deskripsi' => 'Seragam batik sekolah',
                'jenis' => 'seragam',
                'urutan' => 6,
            ],
            [
                'nama_rincian_pembayaran' => 'Baju Olahraga',
                'total_harga' => 200000,
                'deskripsi' => 'Seragam olahraga sekolah',
                'jenis' => 'seragam',
                'urutan' => 7,
            ],
            [
                'nama_rincian_pembayaran' => 'Baju Kuning Hijau',
                'total_harga' => 200000,
                'deskripsi' => 'Seragam sehari-hari sekolah',
                'jenis' => 'seragam',
                'urutan' => 8,
            ],
        ];

        foreach ($data as $item) {
            RincianPembayaran::create($item);
        }
    }
}