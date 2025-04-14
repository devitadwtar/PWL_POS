<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;  // Mengimpor BarangModel
use App\Models\KategoriModel; // Mengimpor KategoriModel
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        // Mengambil data kategori
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        
        // Mengirim data kategori dan judul halaman ke view
        $page = (object) ['title' => 'Data Barang'];
        $activeMenu = 'barang';
        $breadcrumb = (object) [
            'title' => 'Data Barang',
            'list' => ['Home', 'Barang']
        ];

        return view('barang.index', compact('kategori', 'page', 'breadcrumb', 'activeMenu'));
    }


    // Mengambil data untuk DataTables
    public function list(Request $request) 
    { 
    $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')
                ->with('kategori'); // Relasi ke model kategori

    // Filter berdasarkan kategori jika diperlukan
    if ($request->kategori_id) {
        $barang->where('kategori_id', $request->kategori_id);
    }

    return DataTables::of($barang) 
        ->addIndexColumn()
        ->addColumn('kategori_nama', function ($row) {
            return $row->kategori->kategori_nama ?? '-';
        })
        ->addColumn('aksi', function ($row) {
            $btn  = '<button onclick="modalAction(\''.url('/barang/' . $row->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $row->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $row->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';

            return $btn;
        })
        ->rawColumns(['aksi']) // agar HTML tombol bisa ditampilkan
        ->make(true);
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
        // Menampilkan halaman form create barang ajax
        public function create_ajax() 
        {
            // Ambil semua kategori untuk ditampilkan di form
            $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
            return view('barang.create_ajax')->with('kategori', $kategori); 
        }
    
        // Menyimpan data barang melalui AJAX
        public function store_ajax(Request $request) 
        {
            // Cek apakah request adalah AJAX atau ingin JSON
            if ($request->ajax() || $request->wantsJson()) {
                // Validasi input
                $rules = [
                    'kategori_id' => 'required|integer',
                    'barang_kode' => 'required|string|min:3|max:20|unique:m_barang,barang_kode',
                    'barang_nama' => 'required|string|max:100',
                    'harga_beli'  => 'required|numeric',
                    'harga_jual'  => 'required|numeric',
                ];
    
                $validator = Validator::make($request->all(), $rules);
    
                // Kalau gagal validasi
                if ($validator->fails()) {
                    return response()->json([
                        'status'   => false,
                        'message'  => 'Validasi Gagal',
                        'msgField' => $validator->errors(),
                    ]);
                }
    
                // Simpan data barang
                $barang = new BarangModel;
                $barang->kategori_id = $request->kategori_id;
                $barang->barang_kode = $request->barang_kode;
                $barang->barang_nama = $request->barang_nama;
                $barang->harga_beli  = $request->harga_beli;
                $barang->harga_jual  = $request->harga_jual;
                $barang->save();
    
                // Kirim respon sukses
                return response()->json([
                    'status'  => true,
                    'message' => 'Data barang berhasil disimpan',
                ]);
            }
    
            // Kalau bukan AJAX, redirect
            return redirect('/barang')->with('error', 'Permintaan tidak valid');
        }
    
        // Menampilkan halaman form edit barang ajax
        public function edit_ajax(string $id)
        {
            $barang = BarangModel::find($id);
            $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
    
            return view('barang.edit_ajax', [
                'barang' => $barang,
                'kategori' => $kategori
            ]);
        }
    
        // Mengupdate data barang melalui AJAX
        public function update_ajax(Request $request, $id)
        {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kategori_id' => 'required|integer',
                    'barang_kode' => 'required|max:20|unique:m_barang,barang_kode,' . $id . ',barang_id',
                    'barang_nama' => 'required|max:100',
                    'harga_beli'  => 'required|numeric',
                    'harga_jual'  => 'required|numeric',
                ];
    
                // Validator
                $validator = Validator::make($request->all(), $rules);
    
                if ($validator->fails()) {
                    return response()->json([
                        'status'   => false,    
                        'message'  => 'Validasi gagal.',
                        'msgField' => $validator->errors()  
                    ]);
                }
    
                $barang = BarangModel::find($id);
                if ($barang) {
                    $barang->update($request->all());
                    return response()->json([
                        'status'  => true,
                        'message' => 'Data barang berhasil diupdate'
                    ]);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            }
            return redirect('/');
        }
    
        // Menampilkan konfirmasi hapus barang ajax
        public function confirm_ajax(string $id)
        {
            $barang = BarangModel::find($id);
            return view('barang.confirm_ajax', [
                'barang' => $barang
            ]);
        }
    
        // Menghapus data barang melalui AJAX
        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request dari AJAX
            if ($request->ajax() || $request->wantsJson()) {
                $barang = BarangModel::find($id);
    
                if ($barang) {
                    $barang->delete();
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Data barang berhasil dihapus'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            }
    
            return redirect('/');
        }
}
