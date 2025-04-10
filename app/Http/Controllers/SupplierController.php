<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
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
        if ($request->ajax()) {
            $data = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'alamat', 'telepon');

    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '<a href="' . url('supplier/' . $row->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                    $btn .= '<a href="' . url('supplier/' . $row->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                    $btn .= '<form action="' . url('supplier/' . $row->supplier_id) . '" method="POST" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data?\')">Hapus</button>
                             </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    
        return response()->json(['message' => 'Not AJAX'], 400);
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
            'no_telp' => 'nullable|string|max:15',
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
}
