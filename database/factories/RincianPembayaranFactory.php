<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RincianPembayaranFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_rincian_pembayaran' => $this->faker->sentence(3),
            'total_harga' => $this->faker->numberBetween(100000, 5000000),
            'deskripsi' => $this->faker->sentence(),
            'jenis' => $this->faker->randomElement(['formulir', 'uang_bangunan', 'seragam', 'buku', 'lainnya']),
            'is_active' => true,
            'urutan' => $this->faker->numberBetween(1, 10),
        ];
    }
}