<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    // Menampilkan halaman index kategori
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) ['title' => 'Data Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Mengambil data untuk DataTables
    public function list(Request $request)
    {
        $kategori = KategoriModel::all();

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function($row){
                $detailBtn = '<a href="' . url('kategori/' . $row->kategori_id) . '" class="btn btn-info btn-sm">Detail</a>';
                $editBtn = '<a href="' . url('kategori/' . $row->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $deleteBtn = '<form action="' . url('kategori/' . $row->kategori_id) . '" method="POST" style="display:inline;">
                                ' . method_field('DELETE') . csrf_field() . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus kategori ini?\')">Hapus</button>
                            </form>';
                return $detailBtn . ' ' . $editBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan form untuk membuat kategori baru
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) ['title' => 'Tambah kategori baru'];
        $activeMenu = 'kategori';

        return view('kategori.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|unique:m_kategori',
            'kategori_nama' => 'required',
        ]);

        KategoriModel::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil disimpan.');
    }

    // Menampilkan detail kategori
    public function show($id)
    {
        // Ambil data kategori berdasarkan ID
        $kategori = KategoriModel::findOrFail($id);

        // Breadcrumb dan halaman
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) ['title' => 'Detail Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.show', compact('kategori', 'breadcrumb', 'page', 'activeMenu'));
    }

    // Menampilkan form untuk mengedit kategori
    public function edit($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) ['title' => 'Edit kategori'];
        $activeMenu = 'kategori';

        return view('kategori.edit', compact('kategori', 'breadcrumb', 'page', 'activeMenu'));
    }

    // Mengupdate kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_kode' => 'required|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required',
        ]);

        $kategori = KategoriModel::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
