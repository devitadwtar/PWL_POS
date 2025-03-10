<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // ['kategori_id' => 1, 'kategori_kode' => 'MK', 'kategori_nama' => 'Makanan'],
            // ['kategori_id' => 2, 'kategori_kode' => 'MN', 'kategori_nama' => 'Minuman'],
            // ['kategori_id' => 3, 'kategori_kode' => 'EL', 'kategori_nama' => 'Elektronik'],
            // ['kategori_id' => 4, 'kategori_kode' => 'PK', 'kategori_nama' => 'Pakaian'],
            // ['kategori_id' => 5, 'kategori_kode' => 'AT', 'kategori_nama' => 'Alat Tulis'],
            ['kategori_id' => 6, 'kategori_kode' => 'CML', 'kategori_nama' => 'Camilan'],
            ['kategori_id' => 7, 'kategori_kode' => 'MNR', 'kategori_nama' => 'Minuman Ringan'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
