<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;  // Mengimpor BarangModel
use App\Models\KategoriModel; // Mengimpor KategoriModel
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    // Menampilkan halaman daftar barang
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Barang', 'list' => ['Home', 'Barang']];
        $page = (object) ['title' => 'Daftar Barang'];
        $activeMenu = 'barang';

        return view('barang.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Mengambil data untuk DataTables
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = BarangModel::select('m_barang.barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'm_kategori.kategori_nama')
                ->join('m_kategori', 'm_barang.kategori_id', '=', 'm_kategori.kategori_id')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $editUrl = url('barang/' . $row->barang_id . '/edit');
                    $deleteUrl = url('barang/' . $row->barang_id);
                    $csrfToken = csrf_token();
                    $methodField = method_field('DELETE');

                    return '
                        <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                        <a href="' . url('barang/' . $row->barang_id) . '" class="btn btn-info btn-sm">Detail</a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="' . $csrfToken . '">
                            ' . $methodField . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    // Menampilkan form tambah barang
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) ['title' => 'Tambah barang baru'];
        $activeMenu = 'barang';

        // Ambil data kategori dari model KategoriModel
        $kategori = KategoriModel::all(); // Ambil data kategori

        return view('barang.create', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    // Menyimpan data barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
        ]);

        BarangModel::create($request->all());
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    // Menampilkan detail barang
    public function show(string $id)
    {
        $barang = BarangModel::findOrFail($id);
        $breadcrumb = (object) ['title' => 'Detail Barang', 'list' => ['Home', 'Barang', 'Detail']];
        $page = (object) ['title' => 'Detail Barang'];
        $activeMenu = 'barang';

        return view('barang.show', compact('breadcrumb', 'page', 'activeMenu', 'barang'));
    }

    // Menampilkan form edit barang
    public function edit(string $id)
    {
        $barang = BarangModel::findOrFail($id);
        $breadcrumb = (object) ['title' => 'Edit Barang', 'list' => ['Home', 'Barang', 'Edit']];
        $page = (object) ['title' => 'Edit Barang'];
        $activeMenu = 'barang';

        // Ambil data kategori untuk pilihan kategori saat edit
        $kategori = KategoriModel::all();  // Ambil data kategori dari model KategoriModel

        return view('barang.edit', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'kategori'));
    }

    // Menyimpan hasil edit data barang
    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_kode' => 'required|string|max:10',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
        ]);

        $barang = BarangModel::findOrFail($id);
        $barang->update($request->all());

        return redirect('/barang')->with('success', 'Data barang berhasil diperbarui');
    }

    // Menghapus data barang
    public function destroy(string $id)
    {
        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Throwable $e) {
            return redirect('/barang')->with('error', 'Data gagal dihapus');
        }
    }
}
