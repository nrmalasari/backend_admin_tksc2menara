<?php

namespace App\Filament\Widgets;

use App\Models\SiswaPendaftar;
use App\Models\Siswa;
use App\Models\Guru;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
     protected static ?int $sort = 1;
    protected function getStats(): array
    {
        // Jumlah Pendaftar (hanya yang status menunggu atau diproses)
        $totalPendaftar = SiswaPendaftar::whereIn('status', ['menunggu', 'diproses'])->count();
        
        // Jumlah Total Siswa (semua status)
        $totalSiswa = Siswa::count();
        
        // Jumlah Total Guru (hanya yang jabatan = 'Guru')
        $totalGuru = Guru::where('jabatan', 'Guru')->count();
        
        // Jumlah Siswa yang Lulus
        $totalLulus = Siswa::where('status', 'lulus')->count();
        
        // Jumlah Siswa Aktif
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
        
        // Jumlah Guru Aktif (hanya yang jabatan = 'Guru' dan status aktif)
        $totalGuruAktif = Guru::where('jabatan', 'Guru')
            ->where('status', 'aktif')
            ->count();

        return [
            Stat::make('Pendaftar Baru', $totalPendaftar)
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart($this->getPendaftarTrend())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Total Siswa', $totalSiswa)
                ->description($totalSiswaAktif . ' siswa aktif')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success')
                ->chart($this->getSiswaTrend()),
            
            Stat::make('Total Guru', $totalGuru)
                ->description($totalGuruAktif . ' guru aktif')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('primary')
                ->chart($this->getGuruTrend()),
            
            Stat::make('Siswa Lulus', $totalLulus)
                ->description('Telah menyelesaikan pendidikan')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info')
                ->chart($this->getLulusTrend()),
        ];
    }
    
    /**
     * Trend data pendaftar baru (contoh sederhana)
     */
    private function getPendaftarTrend(): array
    {
        // Data real: ambil data 6 bulan terakhir untuk pendaftar baru
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = SiswaPendaftar::whereIn('status', ['menunggu', 'diproses'])
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $data[] = $count;
        }
        
        // Jika data kosong semua, beri contoh data
        if (array_sum($data) === 0) {
            return [5, 8, 12, 7, 10, 15];
        }
        
        return $data;
    }
    
    /**
     * Trend data siswa (contoh sederhana)
     */
    private function getSiswaTrend(): array
    {
        // Data real: ambil data 6 bulan terakhir untuk siswa baru
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Siswa::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $data[] = $count;
        }
        
        // Jika data kosong semua, beri contoh data
        if (array_sum($data) === 0) {
            return [8, 12, 15, 18, 20, 22];
        }
        
        return $data;
    }
    
    /**
     * Trend data guru (contoh sederhana)
     */
    private function getGuruTrend(): array
    {
        // Data real: ambil data 6 bulan terakhir untuk guru baru
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Guru::where('jabatan', 'Guru')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $data[] = $count;
        }
        
        // Jika data kosong semua, beri contoh data
        if (array_sum($data) === 0) {
            return [2, 3, 4, 3, 5, 4];
        }
        
        return $data;
    }
    
    /**
     * Trend data lulus (contoh sederhana)
     */
    private function getLulusTrend(): array
    {
        // Data real: ambil data 6 bulan terakhir untuk siswa lulus
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Siswa::where('status', 'lulus')
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->count();
            $data[] = $count;
        }
        
        // Jika data kosong semua, beri contoh data
        if (array_sum($data) === 0) {
            return [3, 5, 8, 10, 12, 15];
        }
        
        return $data;
    }
    
    /**
     * Mengatur jumlah kolom pada widget
     */
    protected function getColumns(): int
    {
        return 4; // Menampilkan 4 kolom di desktop, akan responsif di mobile
    }
}