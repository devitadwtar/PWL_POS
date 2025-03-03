<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\UserModel; 
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller 
{ 
    public function index() 
    { 
        $data = [ 
            'level_id' => 2, 
            'username' => 'manager_tiga', 
            'nama' => 'Manager 3', 
            'password' => Hash::make('12345') 
        ]; 

        // Simpan data ke database
        UserModel::create($data); 

        // Ambil semua data user
        $user = UserModel::all(); 

        // Tampilkan ke view
        return view('user', ['data' => $user]); 
    } 
}
