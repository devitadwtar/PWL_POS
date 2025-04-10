<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokModel extends Model
{
    protected $table = 't_stok';
    protected $primaryKey = 'id'; // ganti jika primary key lain
    protected $fillable = ['barang_id', 'jumlah'];
    public $timestamps = false;
}
