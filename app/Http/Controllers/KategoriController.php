<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\DataTables\KategoriDataTable;

class KategoriController extends Controller
{
    public function index(KategoriDataTable $dataTable)
    {
        return $dataTable->render('kategori.index');
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function edit($id) 
    { 
        $data = KategoriModel::find($id);
        return view('kategori.edit', ['kategori' => $data]); 
    } 

    public function update(Request $request, $id) 
    { 
        KategoriModel::where('kategori_id', $id)->update([
            'kategori_kode' => $request->kodekategori, 
            'kategori_nama' => $request->namakategori 
        ]);
    }

    public function delete($id) 
    {
        KategoriModel::where('kategori_id', $id)->delete();
        return redirect('/kategori');
    }    

    public function store(Request $request)
    {
        KategoriModel::create([
            'kategori_kode' => $request->kodeKategori,
            'kategori_nama' => $request->namaKategori,
        ]);
        return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }
}
