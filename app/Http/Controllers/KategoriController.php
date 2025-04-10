<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    // Menampilkan halaman index
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Kategori', 'list' => ['Home', 'Kategori']];
        $page = (object) ['title' => 'Daftar kategori barang'];
        $activeMenu = 'kategori';

        return view('kategori.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Digunakan oleh DataTables
    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
    
        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) {
                return '
                    <a href="'.url('/kategori/'.$item->kategori_id).'" class="btn btn-info btn-sm">Detail</a>
                    <a href="'.url('/kategori/'.$item->kategori_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a>
                    <form action="'.url('/kategori/'.$item->kategori_id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin hapus data?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }    

    // Menampilkan form tambah
    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Kategori', 'list' => ['Home', 'Kategori', 'Tambah']];
        $page = (object) ['title' => 'Tambah kategori baru'];
        $activeMenu = 'kategori';

        return view('kategori.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);

        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    // Menampilkan detail kategori
    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);
        $breadcrumb = (object) ['title' => 'Detail Kategori', 'list' => ['Home', 'Kategori', 'Detail']];
        $page = (object) ['title' => 'Detail data kategori'];
        $activeMenu = 'kategori';

        return view('kategori.show', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    // Menampilkan form edit
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);
        $breadcrumb = (object) ['title' => 'Edit Kategori', 'list' => ['Home', 'Kategori', 'Edit']];
        $page = (object) ['title' => 'Edit data kategori'];
        $activeMenu = 'kategori';

        return view('kategori.edit', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    // Menyimpan perubahan data kategori
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10',
            'kategori_nama' => 'required|string|max:100',
        ]);

        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        $kategori->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diperbarui');
    }

    // Menghapus data kategori
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);

        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena terkait dengan data lain');
        }
    }
    
}
