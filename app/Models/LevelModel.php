<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelModel extends Model
{
    // Nama tabel dalam database
    protected $table = 'm_level';

    // Nama kolom primary key dalam tabel
    protected $primaryKey = 'level_id';

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = ['level_kode', 'level_nama'];

    // Relasi one-to-many ke tabel user (jika ada)
    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'level_id', 'level_id');
    }
}
