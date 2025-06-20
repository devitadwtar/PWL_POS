<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        // Ambil data user yang sedang login
        $user = UserModel::where('user_id', Auth::id())->first();

        return view('profile.edit', [
            'user' => $user,
            'breadcrumb' => (object)[
                'title' => 'Profil Pengguna',
                'list' => ['Home', 'Profil']
            ],
            'activeMenu' => 'profile'
        ]);
    }

    /**
     * Memproses update foto profil
     */
    public function update(Request $request)
    {
        // Ambil user login
        $user = UserModel::where('user_id', Auth::id())->first();

        // Validasi input
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek apakah ada file foto di-upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/foto', $filename);

            // Update nama file di database
            $user->foto = $filename;
        }

        // Simpan perubahan user
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
