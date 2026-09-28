<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\alat;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data peminjaman dengan pagination
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(4);
        return view('peminjaman.index', compact('datap'));
    }

    public function create(Request $request)
    {
        // Biasanya butuh data user dan alat untuk pilihan dropdown di form
        $users = User::all();
        $alat = Alat::all();
        return view('peminjaman.create', compact('users', 'alat'));
    }

    public function store(Request $request)
    {
        $datap = $request->validate([
            'id_user'        => 'required|integer',
            'id_alat'        => 'required|integer',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        // Set status awal peminjaman
        $datap['status'] = 'Pending';
        $datap['denda'] = 0;

        Peminjaman::create($datap);
        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function edit(Request $request, $id_peminjaman)
    {
        $datap = Peminjaman::findOrFail($id_peminjaman);
        $users = User::all();
        $alat = Alat::all();
        return view('peminjaman.edit', compact('data', 'users', 'alat'));
    }

    public function update(Request $request, $id_peminjaman)
    {
        $datap = $request->all();

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update($datap);
        
        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil diubah.');
    }

    public function delete(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}