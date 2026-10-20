<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Models\ActivityLog;

class PengembalianController extends Controller
{
    // ===================== HAK AKSES =====================
    // DIUBAH: CRUD Pengembalian khusus admin (petugas tidak boleh masuk)
    private function wajibAdmin()
    {
        $user = User::current();
        if (!$user || $user->role !== 'admin') {
            return redirect(route('dashboard'))->with('error', 'Halaman Pengembalian hanya untuk admin.');
        }
        return null;
    }

    // ===================== DENDA (samakan dengan PeminjamanController) =====================
    private const LAMA_PINJAM_HARI = 3;
    private const BATAS_TELAT_HARI = 2;
    private const NOMINAL_DENDA    = 50000;

    private const STATUS_VALID = ['Pending', 'Disetujui', 'Dipinjam', 'Dikembalikan', 'Ditolak'];

    private function hitungDenda($tanggalPinjam, $tanggalKembali = null): int
    {
        if (empty($tanggalPinjam)) {
            return 0;
        }

        $tenggat = strtotime(date('Y-m-d', strtotime((string) $tanggalPinjam)) . ' +' . self::LAMA_PINJAM_HARI . ' days');
        $akhir   = !empty($tanggalKembali)
            ? strtotime(date('Y-m-d', strtotime((string) $tanggalKembali)))
            : strtotime(date('Y-m-d'));

        if (!$tenggat || !$akhir) {
            return 0;
        }

        $telat = (int) round(($akhir - $tenggat) / 86400);

        return $telat >= self::BATAS_TELAT_HARI ? self::NOMINAL_DENDA : 0;
    }

    // ===================== FUNGSI BANTU =====================
    private function namaAlat($id_alat): string
    {
        try {
            $a = Alat::findOrFail($id_alat);
            return (string) ($a->nama_alat ?? ('#' . $id_alat));
        } catch (\Throwable $e) {
            return '#' . $id_alat;
        }
    }

    private function namaUser($id_user): string
    {
        try {
            $u = User::findOrFail($id_user);
            return (string) ($u->username ?? $u->name ?? ('#' . $id_user));
        } catch (\Throwable $e) {
            return '#' . $id_user;
        }
    }

    private function keArray($model): ?array
    {
        try {
            if (method_exists($model, 'toArray')) {
                return $model->toArray();
            }
        } catch (\Throwable $e) {
        }
        return null;
    }

    // rapikan input form: status valid, tanggal kembali (boleh kosong), denda otomatis
    private function rapikan(array $data, array $input): array
    {
        $status = (string) ($input['status'] ?? 'Dikembalikan');
        if (!in_array($status, self::STATUS_VALID, true)) {
            $status = 'Dikembalikan';
        }

        $tglKembali = trim((string) ($input['tanggal_kembali'] ?? ''));

        // status Dikembalikan tapi tanggal kembali kosong: pakai hari ini
        if ($status === 'Dikembalikan' && $tglKembali === '') {
            $tglKembali = date('Y-m-d');
        }

        if ($tglKembali !== '') {
            $denda = $this->hitungDenda($data['tanggal_pinjam'], $tglKembali);
        } elseif (in_array($status, ['Disetujui', 'Dipinjam'], true)) {
            $denda = $this->hitungDenda($data['tanggal_pinjam']); // belum kembali: dihitung sampai hari ini
        } else {
            $denda = 0;
        }

        $data['status']          = $status;
        $data['tanggal_kembali'] = $tglKembali !== '' ? $tglKembali : null;
        $data['denda']           = $denda;

        return $data;
    }

    private function aturan(): array
    {
        return [
            'id_user'        => 'required|integer',
            'id_alat'        => 'required|integer',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ];
    }

    // ===================== CRUD =====================

    public function index(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(10);

        // daftar nama (id => nama) supaya tabel menampilkan nama asli, bukan "User ID" / "Alat ID"
        $namaUser = [];
        foreach (User::all() as $u) {
            $namaUser[$u->id] = (string) ($u->username ?? $u->name ?? ('#' . $u->id));
        }

        $namaAlat = [];
        foreach (Alat::all() as $a) {
            $namaAlat[$a->id_alat] = (string) ($a->nama_alat ?? ('#' . $a->id_alat));
        }

        return view('pengembalian.index', compact('datap', 'namaUser', 'namaAlat'));
    }

    public function create(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $users = User::all();
        $alat  = Alat::all();

        return view('pengembalian.create', compact('users', 'alat'));
    }

    public function store(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $data = $request->validate($this->aturan());
        $data = $this->rapikan($data, $request->all());

        $baru = Peminjaman::create($data);

        ActivityLog::catat(
            'Tambah data pengembalian',
            'Data pengembalian alat "' . $this->namaAlat($data['id_alat']) . '" oleh '
                . $this->namaUser($data['id_user']) . ' ditambahkan. Status: ' . $data['status'] . '.',
            'Pengembalian',
            $baru->id_peminjaman ?? null
        );

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = Peminjaman::findOrFail($id);
        $users = User::all();
        $alat  = Alat::all();

        return view('pengembalian.edit', compact('datap', 'users', 'alat'));
    }

    public function update(Request $request, $id)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $peminjaman = Peminjaman::findOrFail($id);
        $lama = $this->keArray($peminjaman);

        $data = $request->validate($this->aturan());
        $data = $this->rapikan($data, $request->all());

        $peminjaman->update($data);

        ActivityLog::catat(
            'Edit data pengembalian',
            'Data pengembalian alat "' . $this->namaAlat($data['id_alat']) . '" oleh '
                . $this->namaUser($data['id_user']) . ' diubah. Status: ' . $data['status'] . '.',
            'Pengembalian',
            $id,
            $lama
        );

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil diubah.');
    }

    public function delete(Request $request, $id)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $peminjaman = Peminjaman::findOrFail($id);

        $lama     = $this->keArray($peminjaman);
        $namaAlat = $this->namaAlat($peminjaman->id_alat);
        $namaUser = $this->namaUser($peminjaman->id_user);

        $peminjaman->delete();

        ActivityLog::catat(
            'Hapus data pengembalian',
            'Data pengembalian alat "' . $namaAlat . '" oleh ' . $namaUser . ' dihapus.',
            'Pengembalian',
            $id,
            $lama
        );

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil dihapus.');
    }
}