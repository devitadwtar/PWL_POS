<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'user_id' => 1, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 93],
            ['barang_id' => 2, 'user_id' => 1, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 62],
            ['barang_id' => 3, 'user_id' => 2, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 30],
            ['barang_id' => 4, 'user_id' => 2, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 48],
            ['barang_id' => 5, 'user_id' => 3, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 20],
            ['barang_id' => 6, 'user_id' => 3, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 18],
            ['barang_id' => 7, 'user_id' => 1, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 11],
            ['barang_id' => 8, 'user_id' => 2, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 78],
            ['barang_id' => 9, 'user_id' => 3, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 52],
            ['barang_id' => 10, 'user_id' => 1, 'stok_tanggal' => Carbon::now(), 'stok_jumlah' => 53],
        ];

        DB::table('t_stok')->insert($data);
    }
}
