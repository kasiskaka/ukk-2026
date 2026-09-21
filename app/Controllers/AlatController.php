<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\alat; // Model alat sudah di-import di sini

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $alat = alat::orderBy::('id_alat','desc')->paginate(10);
        return view('alat.index', compact('alat'));
    }

    public function index (Request $request )
    {
        return view('alat.create')
    }

    public function store(Request $request)
    {
        //1.Ubah 'varchar' menjadi 'string' karena dalam PHP/Framework pada umumnya menggunakan 'string'
        $validateData = $request->validate([
            'nama_alat' => 'required|min:3|max:100',
            'kode_alat' => 'required|string|min:3|max:100',
        ])

        //2.Ubah 'data' menjadi 'alat' agar sesuai dengan data yang sedang diproses
        alat::create($validatedData);

        return redirect(route('alat.index'))->with('succes','alat berhasil ditambahkan.');   
    }

    public function edit (Request $request, $id_alat)
    { 
        //3.Ubah kategori menjadi alat
        $alat = alat::findOrFail($id_alat)

        // 4. Ubah 'data' menjadi 'alat' agar sesuai dengan nama variabel di atas
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, $id_alat)
    {
        // 5. Perbaiki konflik nama variabel agar data request tidak menimpa instance model
        $inputData = $request->all();

        // 6. Perbaiki penulisan FindOrFail menjadi findOrFail (case-sensitive)
        $alat = alat::findOrFail($id_alat);
        $alat->update($inputData);

        return redirect(route('alat.index'))->with('success', 'alat berhasil diubah');
    }

    public function destroy(Request $request, $id_alat)
    {
        // 7. Ubah Kategori menjadi alat
        $alat = alat::findOrFail($id_alat);
        $alat->delete();

        // 8. Sesuaikan format redirect agar seragam dengan method lainnya
        return redirect(route('alat.index'))->with('success', 'alat berhasil dihapus.');
    }
}