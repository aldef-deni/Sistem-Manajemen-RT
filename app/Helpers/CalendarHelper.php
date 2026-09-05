<?php

namespace App\Helpers;

class CalendarHelper
{
    /**
     * Nama hari Jawa (Pasar)
     */
    public static $hariPasaran = ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'];

    /**
     * Nama hari Masehi dalam Bahasa Indonesia
     */
    public static $hariMasehi = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    /**
     * Warna untuk setiap hari pasaran
     */
    public static $pasaranColors = [
        'Legi'   => ['bg' => 'bg-emerald-500', 'text' => 'text-white', 'dot' => 'bg-emerald-500'],
        'Pahing' => ['bg' => 'bg-orange-500', 'text' => 'text-white', 'dot' => 'bg-orange-500'],
        'Pon'    => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'dot' => 'bg-blue-500'],
        'Wage'   => ['bg' => 'bg-red-500', 'text' => 'text-white', 'dot' => 'bg-red-500'],
        'Kliwon' => ['bg' => 'bg-indigo-500', 'text' => 'text-white', 'dot' => 'bg-indigo-500'],
    ];

    /**
     * Menghitung hari pasaran untuk tanggal tertentu
     * Referensi: 1 Januari 1900 = Pon (index 2)
     */
    public static function getHariPasaran($date)
    {
        if (is_string($date)) {
            $date = new \Carbon\Carbon($date);
        }

        // Jumlah hari dari referensi 1 Jan 1900 ke tanggal target
        $refDate = new \Carbon\Carbon('1900-01-01');
        $diffDays = $refDate->diffInDays($date);

        // 1 Jan 1900 = Pon (index 2)
        $index = ($diffDays + 2) % 5;
        if ($index < 0) $index += 5;

        return self::$hariPasaran[$index];
    }

    /**
     * Mendapatkan index warna pasaran
     */
    public static function getPasaranColor($pasaran)
    {
        return self::$pasaranColors[$pasaran] ?? ['bg' => 'bg-slate-400', 'text' => 'text-white', 'dot' => 'bg-slate-400'];
    }

    /**
     * Mendapatkan tahun Jawa (Saka) dari tahun Masehi
     */
    public static function getTahunJawa($year)
    {
        return $year - 78;
    }

    /**
     * Mendapatkan nama windu/abad Jawa (opsional)
     */
    public static function getWindu($year)
    {
        $winduNames = [
            'Alip' => [1, 'windu'],
            'Ehe' => [2, 'windu'],
            'Jimakir' => [3, 'windu'],
            'Dal' => [4, 'windu'],
            'Sawal' => [5, 'windu'],
            'Sela' => [6, 'windu'],
            'Sri' => [7, 'windu'],
            'Akir' => [8, 'windu'],
        ];

        $winduYear = (($year - 1900) % 32) + 1;
        $winduPhase = ceil($winduYear / 4);
        $winduPosition = (($winduYear - 1) % 4) + 1;

        $keys = array_keys($winduNames);
        $winduName = $keys[$winduPhase - 1] ?? 'Alip';

        return [
            'windu' => $winduName,
            'year_in_windu' => $winduPosition,
            'windu_full' => $winduName . ' ' . $winduPosition,
        ];
    }

    /**
     * Mendapatkan jumlah hari dalam bulan tertentu
     */
    public static function getDaysInMonth($month, $year)
    {
        // date('t') memberi hasil yang sama tanpa ekstensi calendar, yang
        // tidak terpasang pada PHP bawaan aaPanel di server produksi.
        return (int) date('t', mktime(0, 0, 0, (int) $month, 1, (int) $year));
    }

    /**
     * Mendapatkan hari pertama dalam seminggu (0=Minggu, 1=Senin, ...)
     */
    public static function getFirstDayOfMonth($month, $year)
    {
        return (int) date('w', mktime(0, 0, 0, $month, 1, $year));
    }

    /**
     * Format nama bulan dalam Bahasa Indonesia
     */
    public static $bulanNames = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    /**
     * Format nama bulan Jawa (tradisional)
     */
    public static $bulanJawa = [
        1 => 'Sura', 'Sapar', 'Mulud', 'Bakda Mulud', 'Jumadilawal', 'Jumadilakhir',
        'Rejeb', 'Ruwah', 'Sela', 'Besar', 'Sura', 'Sapar'
    ];

    public static function getBulanName($month)
    {
        return self::$bulanNames[$month] ?? '';
    }

    /**
     * Mendapatkan data lengkap untuk satu hari
     */
    public static function getDayData($date)
    {
        $carbon = is_string($date) ? new \Carbon\Carbon($date) : $date;
        $pasaran = self::getHariPasaran($carbon);
        $hariMasehi = self::$hariMasehi[$carbon->dayOfWeek];
        $colors = self::getPasaranColor($pasaran);

        return [
            'date' => $carbon,
            'day' => $carbon->day,
            'dayOfWeek' => $carbon->dayOfWeek,
            'hari_masehi' => $hariMasehi,
            'hari_pasaran' => $pasaran,
            'colors' => $colors,
            'is_weekend' => $carbon->dayOfWeek === 0 || $carbon->dayOfWeek === 6,
            'is_today' => $carbon->isToday(),
        ];
    }
}
