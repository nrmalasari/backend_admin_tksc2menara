<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use Illuminate\Support\Str;

class BeritaAcaraSeeder extends Seeder
{
    public function run()
    {
        $beritaList = [
            [
                'judul' => 'TK SC2 Menara Meriahkan Peringatan Hari Kemerdekaan',
                'slug' => 'tk-sc2-menara-meriahkan-peringatan-hari-kemerdekaan',
                'tanggal_acara' => '2024-08-17',
                'deskripsi' => 'Dalam rangka menyemarakkan Hari Kemerdekaan Republik Indonesia yang ke-79, TK SC2 Menara Parepare menyelenggarakan berbagai kegiatan bertema kebangsaan yang sarat nilai edukatif.',
                'thumbnail' => null,
                'publikasi' => 'publik',
                'tags' => ['kegiatan', 'nasional', 'sekolah'],
            ],
            [
                'judul' => 'Workshop Parenting untuk Orang Tua Murid',
                'slug' => 'workshop-parenting-untuk-orang-tua-murid',
                'tanggal_acara' => '2024-09-15',
                'deskripsi' => 'Workshop parenting dengan tema "Mendidik Anak di Era Digital" diikuti oleh orang tua murid.',
                'thumbnail' => null,
                'publikasi' => 'publik',
                'tags' => ['workshop', 'parenting', 'pendidikan'],
            ],
            [
                'judul' => 'Field Trip Edukatif ke Kebun Binatang Mini',
                'slug' => 'field-trip-edukatif-ke-kebun-binatang-mini',
                'tanggal_acara' => '2024-10-05',
                'deskripsi' => 'Siswa-siswi TK SC2 Menara melakukan field trip edukatif ke kebun binatang mini untuk belajar tentang hewan.',
                'thumbnail' => null,
                'publikasi' => 'publik',
                'tags' => ['fieldtrip', 'edukasi', 'kegiatan'],
            ],
        ];

        foreach ($beritaList as $beritaData) {
            BeritaAcara::create($beritaData);
        }

        $this->command->info('Berita acara berhasil di-seed!');
    }
}