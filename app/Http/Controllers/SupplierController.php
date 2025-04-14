<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Supplier', 'list' => ['Home', 'Supplier']];
        $page = (object) ['title' => 'Daftar Supplier'];
        $activeMenu = 'supplier';

        return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    
    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'alamat', 'telepon');
        
        // Filter data berdasarkan level_id (jika ada parameter level_id)
        if ($request->level_id) {
            $suppliers->where('level_id', $request->level_id);
        }

        return DataTables::of($suppliers)
            ->addIndexColumn()  // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($supplier) {
                $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Supplier', 'list' => ['Home', 'Supplier', 'Tambah']];
        $page = (object) ['title' => 'Tambah Supplier'];
        $activeMenu = 'supplier';

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
        ]);

        SupplierModel::create($request->all());
        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) ['title' => 'Detail Supplier', 'list' => ['Home', 'Supplier', 'Detail']];
        $page = (object) ['title' => 'Detail Supplier'];
        $activeMenu = 'supplier';

        return view('supplier.show', compact('breadcrumb', 'page', 'activeMenu', 'supplier'));
    }

    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) ['title' => 'Edit Supplier', 'list' => ['Home', 'Supplier', 'Edit']];
        $page = (object) ['title' => 'Edit Supplier'];
        $activeMenu = 'supplier';

        return view('supplier.edit', compact('breadcrumb', 'page', 'activeMenu', 'supplier'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_kode' => 'required|string|max:10',
            'supplier_nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
        ]);

        $supplier = SupplierModel::find($id);
        $supplier->update($request->all());

        return redirect('/supplier')->with('success', 'Data supplier berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        try {
            SupplierModel::destroy($id);
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Throwable $e) {
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena terkait dengan data lain');
        }
    }
    public function create_ajax()
{
    return view('supplier.create_ajax');
}

public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'telepon'       => 'nullable|string|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $supplier = new SupplierModel;
        $supplier->supplier_kode = $request->supplier_kode;
        $supplier->supplier_nama = $request->supplier_nama;
        $supplier->alamat        = $request->alamat;
        $supplier->telepon       = $request->telepon;
        $supplier->save();

        return response()->json([
            'status'  => true,
            'message' => 'Data supplier berhasil disimpan',
            'data'    => $supplier,  // Mengirim data baru ke DataTable
        ]);
    }

    return redirect('/supplier')->with('error', 'Permintaan tidak valid');
}


    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', compact('supplier'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|max:10|unique:m_supplier,supplier_kode,'.$id.',supplier_id',
                'supplier_nama' => 'required|max:100',
                'alamat'        => 'nullable|max:255',
                'telepon'       => 'nullable|max:20'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
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

    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', compact('supplier'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);

            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
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
