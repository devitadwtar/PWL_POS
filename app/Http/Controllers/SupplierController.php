<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $btn  = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
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
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:15',
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
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
            'supplier_nama' => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:15',
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

    // AJAX

    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
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

            $supplier = SupplierModel::create($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Data supplier berhasil disimpan',
                'data'    => $supplier,
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
        if ($request->ajax()) {
            $rules = [
                'supplier_kode' => 'required|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
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
        if ($request->ajax()) {
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

public function export_excel()
{
    // Ambil data supplier dari database
    $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'alamat', 'telepon')
        ->orderBy('supplier_kode')
        ->get();

    // Buat spreadsheet baru dan ambil sheet aktif
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header kolom di baris pertama
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Supplier');
    $sheet->setCellValue('C1', 'Nama Supplier');
    $sheet->setCellValue('D1', 'Alamat');
    $sheet->setCellValue('E1', 'Telepon');

    // Bold header
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);

    // Isi data dimulai dari baris ke-2
    $no = 1;
    $baris = 2;
    foreach ($supplier as $value) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $value->supplier_kode);
        $sheet->setCellValue('C' . $baris, $value->supplier_nama);
        $sheet->setCellValue('D' . $baris, $value->alamat);
        $sheet->setCellValue('E' . $baris, $value->telepon);

        $baris++;
        $no++;
    }

    // Set lebar kolom otomatis
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set nama sheet
    $sheet->setTitle('Data Supplier');

    // Buat writer dan nama file
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Supplier_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Set response agar browser langsung download file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');
    header('Expires: 0');
    header('Pragma: public');

    // Simpan file ke output (langsung download)
    $writer->save('php://output');
    exit;
}


    // IMPORT

    public function import()
    {
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'file_supplier' => 'required|mimes:xlsx'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_supplier');
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                foreach ($sheetData as $index => $row) {
                    if ($index == 1) continue;

                    $kode   = $row['A'];
                    $nama   = $row['B'];
                    $alamat = $row['C'];
                    $telp   = $row['D'];

                    if ($kode && $nama) {
                        SupplierModel::updateOrCreate(
                            ['supplier_kode' => $kode],
                            [
                                'supplier_nama' => $nama,
                                'alamat'        => $alamat,
                                'telepon'       => $telp
                            ]
                        );
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil diimport'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Import gagal: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Permintaan tidak valid'
        ]);
    }
}
