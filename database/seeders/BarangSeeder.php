<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'B001', 'barang_nama' => 'Roti', 'harga_beli' => 5000, 'harga_jual' => 7000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'B002', 'barang_nama' => 'Nasi Kotak', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'B003', 'barang_nama' => 'Kopi Botol', 'harga_beli' => 8000, 'harga_jual' => 12000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'B004', 'barang_nama' => 'Teh Kotak', 'harga_beli' => 6000, 'harga_jual' => 10000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'B005', 'barang_nama' => 'Headset Bluetooth', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'B006', 'barang_nama' => 'Charger HP', 'harga_beli' => 30000, 'harga_jual' => 50000],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'B007', 'barang_nama' => 'Baju Kaos', 'harga_beli' => 25000, 'harga_jual' => 40000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'B008', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'B009', 'barang_nama' => 'Pulpen', 'harga_beli' => 2000, 'harga_jual' => 5000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'B010', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 10000, 'harga_jual' => 15000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
