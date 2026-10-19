<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Alat;
use App\Models\ActivityLog;

class alatController extends Controller
{
    public function index(Request $request)
    {
        $datal = alat::orderBy('id_alat', 'desc')->paginate(4);
       return view('alat.index', compact('datal'));
    }
    public function create(Request $request)
    {
        return view('alat.create');
    }
    public function store(Request $request)
    {
        $datal = $request->validate([
            'nama_alat' => 'required|min:3|max:100',
            'kode_alat' => 'required|varchar|min:3|max:100',
        ]);
        $alat = alat::create($datal);

        ActivityLog::catat(
            'Menambah alat',
            'Menambahkan alat "' . $datal['nama_alat'] . '" dengan kode "' . $datal['kode_alat'] . '".',
            'alat',
            $alat->id_alat ?? null,
            null,
            ['nama_alat' => $datal['nama_alat'], 'kode_alat' => $datal['kode_alat']]
        );

        return redirect(route('alat.index'))->with('success', 'Alat berhasil ditambahkan.');
    }

   public function edit(Request $request, $id_alat)
{
    $datal = alat::findOrFail($id_alat);
    return view('alat.edit', compact('datal'));
}

    public function update (Request $request, $id_alat)
    {
        $datal = $request->all();

        $alat = alat::FindOrfail($id_alat);

        // simpan data lama dulu sebelum diubah
        $lama = ['nama_alat' => $alat->nama_alat, 'kode_alat' => $alat->kode_alat];

        $alat->update($datal);

        ActivityLog::catat(
            'Mengubah alat',
            'Mengubah alat "' . $lama['nama_alat'] . '" (kode "' . $lama['kode_alat'] . '") menjadi "' . $alat->nama_alat . '" (kode "' . $alat->kode_alat . '").',
            'alat',
            $id_alat,
            $lama,
            ['nama_alat' => $alat->nama_alat, 'kode_alat' => $alat->kode_alat]
        );

        return redirect(route('alat.index'))->with('success', 'alat berhasil diubah');
    }
   public function delete(Request $request, $id_alat)
    {
        $alat = alat::findOrFail($id_alat);

        // ambil nama dulu sebelum dihapus
        $nama = $alat->nama_alat;
        $kode = $alat->kode_alat;

        $alat->delete();

        ActivityLog::catat(
            'Menghapus alat',
            'Menghapus alat "' . $nama . '" dengan kode "' . $kode . '".',
            'alat',
            $id_alat
        );

        return redirect()->route('alat.index')->with('success', 'alat berhasil dihapus.');
    }
}