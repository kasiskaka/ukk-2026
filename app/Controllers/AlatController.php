<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Alat;

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
        alat::create($datal);
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
        $alat->update($datal);
        return redirect(route('alat.index'))->with('success', 'alat berhasil diubah');
    }
   public function delete(Request $request, $id_alat)
    {
        $alat = alat::findOrFail($id_alat);
        $alat->delete();

        return redirect()->route('alat.index')->with('success', 'alat berhasil dihapus.');
    }
}