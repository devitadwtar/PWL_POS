<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    // KategoriController.php
    public function list(Request $request) 
    {
        // Mengambil data kategori dengan relasi jika diperlukan (misalnya relasi dengan tabel lain)
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter berdasarkan kategori_id jika ada
        if ($request->kategori_id) {
            $kategori->where('kategori_id', $request->kategori_id);
        }

        // Mengembalikan data dalam format JSON untuk DataTables
        return DataTables::of($kategori)
            ->addIndexColumn()  // Menambahkan kolom index (DT_RowIndex) 
            ->addColumn('aksi', function ($kategori) {  // Menambahkan kolom aksi
                // Tombol aksi untuk detail, edit, dan hapus kategori
                $btn  = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn; 
            })
            ->rawColumns(['aksi'])  // Menandakan bahwa kolom aksi adalah HTML
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
        // Menampilkan form tambah kategori AJAX
        public function create_ajax()
        {
            return view('kategori.create_ajax');
        }
    
        // Menyimpan kategori menggunakan AJAX
        public function store_ajax(Request $request)
        {
            // Cek apakah request adalah AJAX atau ingin JSON
            if ($request->ajax() || $request->wantsJson()) {
                // Validasi input
                $rules = [
                    'kategori_kode' => 'required|string|max:20|unique:m_kategori,kategori_kode',
                    'kategori_nama' => 'required|string|max:100',
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
    
                // Simpan data kategori
                $kategori = new KategoriModel;
                $kategori->kategori_kode = $request->kategori_kode;
                $kategori->kategori_nama = $request->kategori_nama;
                $kategori->save();
    
                // Kirim respon sukses
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kategori berhasil disimpan',
                ]);
            }
    
            // Kalau bukan AJAX, redirect
            return redirect('/kategori')->with('error', 'Permintaan tidak valid');
        }
    
        // Menampilkan halaman form edit kategori AJAX
        public function edit_ajax(string $id)
        {
            $kategori = KategoriModel::find($id);
            return view('kategori.edit_ajax', ['kategori' => $kategori]);
        }
    
        // Mengupdate kategori menggunakan AJAX
        public function update_ajax(Request $request, $id)
        {
            // Cek apakah request dari AJAX
            if ($request->ajax() || $request->wantsJson()) {
                // Validasi input
                $rules = [
                    'kategori_kode' => 'required|string|max:20|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                    'kategori_nama' => 'required|string|max:100',
                ];
    
                $validator = Validator::make($request->all(), $rules);
    
                // Kalau gagal validasi
                if ($validator->fails()) {
                    return response()->json([
                        'status'   => false,
                        'message'  => 'Validasi gagal',
                        'msgField' => $validator->errors(),
                    ]);
                }
    
                // Mencari kategori berdasarkan ID
                $kategori = KategoriModel::find($id);
                if ($kategori) {
                    $kategori->update($request->all());
    
                    return response()->json([
                        'status'  => true,
                        'message' => 'Data kategori berhasil diperbarui',
                    ]);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Data tidak ditemukan',
                    ]);
                }
            }
    
            return redirect('/');
        }
    
        // Menampilkan halaman konfirmasi hapus kategori AJAX
        public function confirm_ajax(string $id)
        {
            $kategori = KategoriModel::find($id);
            return view('kategori.confirm_ajax', ['kategori' => $kategori]);
        }
    
        // Menghapus kategori menggunakan AJAX
        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request dari AJAX
            if ($request->ajax() || $request->wantsJson()) {
                $kategori = KategoriModel::find($id);
    
                if ($kategori) {
                    $kategori->delete();
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Data kategori berhasil dihapus',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data kategori tidak ditemukan',
                    ]);
                }
            }
    
            return redirect('/');
        }
}
