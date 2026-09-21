<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $data = Kategori::orderBy('id_kategori', 'desc')->paginate(4);
       return view('kategori.index', compact('data'));
    }
    public function create(Request $request)
    {
        return view('kategori.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|min:3|max:100',
            'kode_kategori' => 'required|varchar|min:3|max:100',
            'keterangan' => 'required|min:3|max:255',
        ]);
        Kategori::create($data);
        return redirect(route('kategori.index'))->with('success', 'Kategori berhasil ditambahkan.');
    }

   public function edit(Request $request, $id_kategori)
{
    $data = Kategori::findOrFail($id_kategori);
    return view('kategori.edit', compact('data'));
}

    public function update (Request $request, $id_kategori)
    {
        $data = $request->all();

        $kategori = kategori::FindOrfail($id_kategori);
        $kategori->update($data);
        return redirect(route('kategori.index'))->with('success', 'kategori berhasil diubah');
    }
   public function delete(Request $request, $id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}