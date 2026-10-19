<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;

class PengembalianController extends Controller
{
    private const ROLE_PENGELOLA = ['admin', 'petugas'];

    private function wajibAdmin()
    {
        $user = User::current();
        if (!$user || !$user->hasRole(...self::ROLE_PENGELOLA)) {
            return redirect(route('dashboard'))->with('error', 'Halaman ini hanya untuk admin.');
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(10);

        return view('pengembalian.index', compact('datap'));
    }

    public function create(Request $request)       { return $this->kembali(); }
    public function store(Request $request)        { return $this->kembali(); }
    public function edit(Request $request, $id)    { return $this->kembali(); }
    public function update(Request $request, $id)  { return $this->kembali(); }
    public function delete(Request $request, $id)  { return $this->kembali(); }

    private function kembali()
    {
        return redirect(route('pengembalian.index'));
    }
}