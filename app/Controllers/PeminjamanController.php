<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;

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
        return view('peminjaman.edit', compact('datap', 'users', 'alat'));
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

    // ===================== BARU: katalog untuk peminjam =====================

    // BARU: katalog alat (bisa dicari, menampilkan tersedia / sedang dipinjam)
    public function katalog(Request $request)
    {
        $q = strtolower(trim($_GET['q'] ?? ''));

        // id alat yang sedang dipinjam (sudah disetujui)
        $dipinjam = [];
        foreach (Peminjaman::all() as $p) {
            if (strtolower($p->status) === 'disetujui') {
                $dipinjam[] = $p->id_alat;
            }
        }

        $daftar = [];
        foreach (Alat::all() as $a) {
            if ($q !== '' && strpos(strtolower($a->nama_alat . ' ' . $a->kode_alat), $q) === false) {
                continue;
            }
            $a->tersedia = !in_array($a->id_alat, $dipinjam);
            $daftar[] = $a;
        }

        return view('peminjaman.katalog', ['daftar' => $daftar, 'q' => $q]);
    }

    // ===================== BARU: pengajuan oleh peminjam =====================

    // DIPERBARUI: tampilkan form pengajuan, alat dari katalog otomatis terpilih
    public function ajukan(Request $request)
    {
        // Jika tabel alat sudah punya kolom status, ganti jadi:
        // $alat = Alat::where('status', 'tersedia')->get();
        $alat = Alat::all();
        $dipilih = $_GET['id_alat'] ?? '';
        return view('peminjaman.ajukan', compact('alat', 'dipilih'));
    }

    // BARU: simpan pengajuan (id_user diambil dari user yang login, bukan dari form)
    public function simpan(Request $request)
    {
        $datap = $request->validate([
            'id_alat'        => 'required|integer',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        $user = User::current();

        $datap['id_user'] = $user->id;
        $datap['status']  = 'Pending';
        $datap['denda']   = 0;

        Peminjaman::create($datap);
        return redirect(route('dashboard'))->with('success', 'Pengajuan peminjaman terkirim, menunggu persetujuan admin.');
    }

    // ===================== BARU: persetujuan oleh admin =====================

    // BARU: admin menyetujui
    public function setujui(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update(['status' => 'Disetujui']);

        return redirect(route('peminjaman.index'))->with('success', 'Peminjaman disetujui.');
    }

    // BARU: admin menolak
    public function tolak(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update(['status' => 'Ditolak']);

        return redirect(route('peminjaman.index'))->with('success', 'Peminjaman ditolak.');
    }
}