<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\UserModel; 
use Illuminate\Support\Facades\Hash; 


class UserController extends Controller 
{ 
    public function index() 
    {  

        // Ambil semua data user
        $user = UserModel::where('username', 'manager')->firstOrFail();

        // Tampilkan ke view
        return view('user', ['data' => $user]); 
    } 
}
