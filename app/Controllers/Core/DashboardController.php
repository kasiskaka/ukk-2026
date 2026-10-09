<?php

namespace App\Controllers\Core;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Alat;
use Sakuci\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::current();

        if ($user->role === 'admin') {
            return redirect('/admin');
        } elseif ($user->role === 'petugas') {
            return redirect('/petugas');
        }

        $peminjamanSaya = Peminjaman::where('id_user', $user->id)
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        foreach ($peminjamanSaya as $p) {
            $alat = Alat::find($p->id_alat);
            $p->nama_alat = $alat ? $alat->nama_alat : '-';
        }

        return view('core.dashboard', [
            'user'           => $user,
            'peminjamanSaya' => $peminjamanSaya,
        ]);
    }

    public function admin()
    {
        return view('core.admin.dashboard', ['user' => User::current()]);
    }

    public function petugas()
    {
        $user = User::current();

        $totalPending   = Peminjaman::where('status', 'Pending')->count();
        $totalDisetujui = Peminjaman::where('status', 'Disetujui')->count();
        $totalAlat      = Alat::count();

        return view('core.petugas.dashboard', [
            'user'           => $user,
            'totalPending'   => $totalPending,
            'totalDisetujui' => $totalDisetujui,
            'totalAlat'      => $totalAlat,
        ]);
    }
}