<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 

class LevelController extends Controller 
{ 
    public function index() 
    { 
        // Insert data baru
        // DB::insert('insert into m_level(level_kode, level_nama, created_at) values (?, ?, ?)', 
        //            ['CUS', 'Pelanggan', now()]); 

        // Update data
        // $row = DB::update("update m_level set level_nama = ? where level_kode = ?", 
        //                   ['Customer', 'CUS']); 
        // return "Update data berhasil. Jumlah data yang diupdate: $row baris."; 

        // Delete data
        // 
        
        $data = DB::select('select * from m_level');
        return view('level' , ['data' => $data]);
    } 
}
