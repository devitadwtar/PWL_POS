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
        $user = UserModel::findOr(20, ['username', 'nama'], function(){
            abort(404);
        }); 

        // Tampilkan ke view
        return view('user', ['data' => $user]); 
    } 
}
