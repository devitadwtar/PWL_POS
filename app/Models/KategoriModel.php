<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    // Menentukan nama tabel
    protected $table = 'm_kategori';

    // Menentukan primary key
    protected $primaryKey = 'kategori_id';

    // Kolom yang boleh diisi
    protected $fillable = ['kategori_kode', 'kategori_nama'];

    // Menonaktifkan timestamps
    public $timestamps = false;
}
