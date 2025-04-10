<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman daftar level
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Level', 'list' => ['Home', 'Level']];
        $page = (object) ['title' => 'Daftar level pengguna'];
        $activeMenu = 'level';

        return view('level.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Mengambil data untuk DataTables
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) {
                return '
                    <a href="'.url('/level/'.$item->level_id).'" class="btn btn-info btn-sm">Detail</a>
                    <a href="'.url('/level/'.$item->level_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a>
                    <form action="'.url('/level/'.$item->level_id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin hapus data?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) ['title' => 'Tambah level baru'];
        $activeMenu = 'level';

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data level baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    // Menghapus data level
    public function destroy(string $id)
    {
        $check = LevelModel::find($id);

        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terkait dengan data lain');
        }
    }

    // Menampilkan detail level
    public function show(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) ['title' => 'Detail level pengguna'];
        $activeMenu = 'level';

        return view('level.show', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    // Menampilkan form edit level
    public function edit(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) ['title' => 'Edit data level'];
        $activeMenu = 'level';

        return view('level.edit', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    // Menyimpan hasil edit data level
    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10',
            'level_nama' => 'required|string|max:100',
        ]);

        $level = LevelModel::find($id);

        if (!$level) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diperbarui');
    }
}