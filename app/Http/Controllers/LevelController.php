<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Level', 'list' => ['Home', 'Level']];
        $page = (object) ['title' => 'Daftar level pengguna'];
        $activeMenu = 'level';

        return view('level.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request) 
    { 
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
        if ($request->level_kode) {
            $levels->where('level_kode', $request->level_kode);
        }

        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Level', 'list' => ['Home', 'Level', 'Tambah']];
        $page = (object) ['title' => 'Tambah level baru'];
        $activeMenu = 'level';

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

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

public function export_excel()
{
    // Ambil data level dari database
    $level = LevelModel::select('level_kode', 'level_nama')
        ->orderBy('level_kode')
        ->get();

    // Buat spreadsheet dan ambil sheet aktif
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header di baris pertama
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Level');
    $sheet->setCellValue('C1', 'Nama Level');

    // Bold header
    $sheet->getStyle('A1:C1')->getFont()->setBold(true);

    // Isi data dari baris ke-2
    $no = 1;
    $baris = 2;
    foreach ($level as $value) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $value->level_kode);
        $sheet->setCellValue('C' . $baris, $value->level_nama);
        $baris++;
        $no++;
    }

    // Set lebar kolom otomatis
    foreach (range('A', 'C') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Set nama sheet
    $sheet->setTitle('Data Level');

    // Siapkan writer dan nama file
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Level_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Set response header agar bisa langsung didownload
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');
    header('Expires: 0');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    $level = LevelModel::select('level_kode', 'level_nama')
        ->orderBy('level_nama')
        ->get();

    $pdf = Pdf::loadView('level.export_pdf', compact('level'));
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isRemoteEnabled', true);

    return $pdf->stream('Data_Level_' . now()->format('Y-m-d_H-i-s') . '.pdf');
}

    public function import()
    {
        return view('level.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'file_level' => 'required|mimes:xlsx'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_level');
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            unset($rows[0]); // buang baris header

            foreach ($rows as $row) {
                $kode = $row[0];
                $nama = $row[1];

                if ($kode && $nama) {
                    LevelModel::updateOrCreate(
                        ['level_kode' => $kode],
                        ['level_nama' => $nama]
                    );
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil diimport'
            ]);
        }

        return redirect('/level')->with('error', 'Permintaan tidak valid');
    }

    // Fungsi lainnya tetap sama (destroy, show, edit, update, ajax, dll)
    // ...
    
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
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terkait');
        }
    }

    public function show(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) ['title' => 'Detail Level', 'list' => ['Home', 'Level', 'Detail']];
        $page = (object) ['title' => 'Detail level pengguna'];
        $activeMenu = 'level';

        return view('level.show', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    public function edit(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) ['title' => 'Edit Level', 'list' => ['Home', 'Level', 'Edit']];
        $page = (object) ['title' => 'Edit data level'];
        $activeMenu = 'level';

        return view('level.edit', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

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

    public function create_ajax() 
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:3|max:20|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $level = new LevelModel;
            $level->level_kode = $request->level_kode;
            $level->level_nama = $request->level_nama;
            $level->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data level berhasil disimpan',
            ]);
        }

        return redirect('/level')->with('error', 'Permintaan tidak valid');
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_nama' => 'required|max:100',
                'level_kode' => 'required|max:50|unique:m_level,level_kode,' . $id . ',level_id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::find($id);
            if ($level) {
                $level->update($request->only(['level_nama', 'level_kode']));
                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data level tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json(['status' => true, 'message' => 'Data level berhasil dihapus']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data level tidak ditemukan']);
            }
        }

        return redirect('/');
    }
}
