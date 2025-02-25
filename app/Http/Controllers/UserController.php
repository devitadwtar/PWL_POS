<?php 
namespace App\Http\Controllers; 

use App\Models\UserModel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller 
{ 
    public function index() 
    { 
        // Data yang akan diperbarui
        $data = [ 
            'nama' => 'Pelanggan Pertama', 
        ]; 

        // Update data user
        UserModel::where('username', 'customer-1')->update($data);

        // Ambil semua data user setelah update
        $user = UserModel::all();

        // Tampilkan di view
        return view('user', ['data' => $user]); 
    } 
}
