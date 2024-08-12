<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;

class NilaiController extends Controller
{
    public function getNilaiRt()
    {
        $nilai = Nilai::select('id_siswa', 'nama', 'nisn', 'skor', 'nama_pelajaran')
            ->where('materi_uji_id', 7)
            ->where('nama_pelajaran', '!=', 'Pelajaran Khusus')
            ->get();

        $grouped = $nilai->groupBy('nama')->map(function ($items) {
            $result = [
                'nama' => $items->first()->nama,
                'nilaiRt' => [],
                'nisn' => $items->first()->nisn,
            ];

            foreach ($items as $item) {
                $result['nilaiRt'][strtolower($item->nama_pelajaran)] = $item->skor;
            }

            ksort($result['nilaiRt']);

            return $result;
        });

        return $grouped->values();
    }

    public function getNilaiSt()
    {
        $nilai = Nilai::select('id_siswa', 'nama', 'nisn', 'skor', 'nama_pelajaran', 'pelajaran_id')
            ->where('materi_uji_id', 4)
            ->where('nama_pelajaran', '!=', 'Pelajaran Khusus')
            ->get();

        $grouped = $nilai->groupBy('nama')->map(function ($items) {
            $result = [
                'listNilai' => [],
                'nama' => $items->first()->nama,
                'nisn' => $items->first()->nisn,
                'total' => 0,
            ];

            foreach ($items as $item) {
                $multiplier = 1;

                switch ($item->pelajaran_id) {
                    case 44:
                        $multiplier = 41.67;
                        break;
                    case 45:
                        $multiplier = 29.67;
                        break;
                    case 46:
                        $multiplier = 100;
                        break;
                    case 47:
                        $multiplier = 23.81;
                        break;
                }

                $nilai_akhir = $item->skor * $multiplier;
                $result['listNilai'][strtolower($item->nama_pelajaran)] = $nilai_akhir;
                $result['total'] += $nilai_akhir;
            }

            ksort($result['listNilai']);

            return $result;
        });

        $sorted = $grouped->sortByDesc('total');

        return $sorted->values();
    }
}
