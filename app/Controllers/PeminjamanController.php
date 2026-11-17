<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Models\ActivityLog; // TAMBAHAN

class PeminjamanController extends Controller
{
    // ===================== TAMBAHAN: fungsi bantu untuk log =====================

    // TAMBAHAN: ambil nama alat dari id
    private function namaAlat($id_alat): string
    {
        try {
            $a = Alat::findOrFail($id_alat);
            return (string) ($a->nama_alat ?? ('#' . $id_alat));
        } catch (\Throwable $e) {
            return '#' . $id_alat;
        }
    }

    // TAMBAHAN: ambil nama peminjam dari id
    private function namaUser($id_user): string
    {
        try {
            $u = User::findOrFail($id_user);
            return (string) ($u->username ?? $u->name ?? ('#' . $id_user));
        } catch (\Throwable $e) {
            return '#' . $id_user;
        }
    }

    // TAMBAHAN: ubah data model jadi array (aman kalau method tidak ada)
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

    // ===================== STOK ALAT (BARU) =====================

    // BARU: status yang sedang "memegang" stok (alat sedang dipinjam)
    private function menahanStok($status): bool
    {
        return in_array(strtolower((string) $status), ['disetujui', 'dipinjam'], true);
    }

    // BARU: kurangi / tambah stok sesuai perpindahan status.
    // Mengembalikan false kalau stok tidak cukup untuk dipinjam.
    private function sesuaikanStok($idAlat, int $jumlah, $statusLama, $statusBaru): bool
    {
        $lama = $this->menahanStok($statusLama);
        $baru = $this->menahanStok($statusBaru);

        if ($lama === $baru) {
            return true; // tidak ada perubahan stok
        }

        $alat = Alat::findOrFail($idAlat);
        $stok = (int) ($alat->stok ?? 0);

        if ($baru) {
            // mulai dipinjam -> stok dikurangi
            if ($stok < $jumlah) {
                return false;
            }
            $alat->update(['stok' => $stok - $jumlah]);
        } else {
            // dikembalikan / dibatalkan / dihapus -> stok dikembalikan
            $alat->update(['stok' => $stok + $jumlah]);
        }

        return true;
    }

    // ===================== HAK AKSES =====================

    // role yang boleh membuka halaman kelola peminjaman (sesuaikan dengan nama role di aplikasimu)
    private const ROLE_PENGELOLA = ['admin', 'petugas'];

    // true kalau user yang login punya salah satu role pengelola
    private function adalahAdmin(): bool
    {
        $user = User::current();
        if (!$user) {
            return false;
        }
        return $user->hasRole(...self::ROLE_PENGELOLA);
    }

    // dipanggil di awal method khusus admin; kalau bukan admin -> dilempar ke dashboard
    private function wajibAdmin()
    {
        if (!$this->adalahAdmin()) {
            return redirect(route('dashboard'))->with('error', 'Halaman ini hanya untuk admin.');
        }
        return null;
    }

    // BARU: khusus petugas (admin tidak boleh) -> dipakai untuk halaman Pemantauan
    private function wajibPetugas(string $pesan = 'Halaman ini hanya untuk petugas.')
    {
        $user = User::current();
        if (!$user || $user->role !== 'petugas') {
            return redirect(route('admin.dashboard'))->with('error', $pesan);
        }
        return null;
    }

    // BARU: setelah setujui/tolak, petugas kembali ke halaman Persetujuan, admin ke halaman Peminjaman
    private function kembaliSetelahKeputusan()
    {
        $user = User::current();
        return ($user && $user->role === 'petugas')
            ? route('peminjaman.persetujuan')
            : route('peminjaman.index');
    }

    // BARU: admin/petugas tidak boleh meminjam alat -> dilempar ke dashboard admin
    private function blokirAdmin()
    {
        if ($this->adalahAdmin()) {
            return redirect(route('admin.dashboard'))->with('error', 'Admin/petugas tidak bisa meminjam alat.');
        }
        return null;
    }

    // ===================== DENDA OTOMATIS =====================
    // Aturan: telat mengembalikan 2 hari atau lebih -> denda Rp 50.000
    private const LAMA_PINJAM_HARI = 3;      // lama pinjam normal (ubah sesuai kebutuhan)
    private const BATAS_TELAT_HARI = 2;      // telat >= 2 hari kena denda
    private const NOMINAL_DENDA    = 50000;

    // hitung denda: tenggat = tanggal pinjam + lama pinjam
    // $tanggalKembali = tanggal kembali aktual; kalau kosong dipakai tanggal hari ini
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

    // DENDA OTOMATIS: peminjaman yang masih berjalan dan sudah telat 2 hari atau lebih
    // langsung diberi denda, jadi terlihat di halaman admin maupun riwayat peminjam
    private function perbaruiDendaTerlambat(): void
    {
        try {
            foreach (Peminjaman::all() as $p) {
                if (!in_array((string) $p->status, ['Disetujui', 'Dipinjam'])) {
                    continue;
                }
                $denda = $this->hitungDenda($p->tanggal_pinjam, $p->tanggal_kembali); // kosong = dihitung sampai hari ini
                if ((int) $p->denda !== $denda) {
                    $p->update(['denda' => $denda]);
                }
            }
        } catch (\Throwable $e) {
            // gagal menghitung denda tidak boleh merusak halaman
        }
    }

    // ===================== KODE ASLI =====================

    public function index(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $this->perbaruiDendaTerlambat(); // DENDA OTOMATIS

        // Mengambil data peminjaman dengan pagination
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(4);
        // BARU: daftar nama peminjam & nama alat (id => nama) supaya tabel menampilkan nama asli
        $namaUser = [];
        foreach (User::all() as $u) {
            $namaUser[$u->id] = (string) ($u->username ?? $u->name ?? ('#' . $u->id));
        }

        $namaAlat = [];
        foreach (Alat::all() as $a) {
            $namaAlat[$a->id_alat] = (string) ($a->nama_alat ?? ('#' . $a->id_alat));
        }

        return view('peminjaman.index', compact('datap', 'namaUser', 'namaAlat'));
    }

    // BARU: halaman persetujuan, menampilkan pengajuan yang masih Pending
    public function persetujuan(Request $request)
    {
        // DIUBAH: halaman Persetujuan hanya untuk petugas (admin mengelola Peminjaman & Pengembalian)
        if ($bukanPetugas = $this->wajibPetugas('Halaman Persetujuan hanya untuk petugas.')) {
            return $bukanPetugas;
        }

        $datap = Peminjaman::where('status', 'Pending')
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        return view('peminjaman.persetujuan', compact('datap'));
    }

    // BARU: halaman pemantauan, menampilkan alat yang sedang dipinjam (status Disetujui)
    public function pemantauan(Request $request)
    {
        // DIUBAH: hanya petugas yang boleh membuka halaman ini (admin tidak)
        if ($bukanPetugas = $this->wajibPetugas()) {
            return $bukanPetugas;
        }

        $this->perbaruiDendaTerlambat(); // DENDA OTOMATIS

        $datap = Peminjaman::where('status', 'Disetujui')
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        // BARU: daftar nama peminjam & nama alat (id => nama), supaya tabel dan
        // laporan cetak menampilkan nama asli, bukan "User ID" / "Alat ID"
        $namaUser = [];
        foreach (User::all() as $u) {
            $namaUser[$u->id] = (string) ($u->username ?? $u->name ?? ('#' . $u->id));
        }

        $namaAlat = [];
        foreach (Alat::all() as $a) {
            $namaAlat[$a->id_alat] = (string) ($a->nama_alat ?? ('#' . $a->id_alat));
        }

        return view('peminjaman.pemantauan', compact('datap', 'namaUser', 'namaAlat'));
    }

    // BARU: cetak laporan pemantauan (khusus petugas)
    public function cetakPemantauan(Request $request)
    {
        if ($bukanPetugas = $this->wajibPetugas()) {
            return $bukanPetugas;
        }

        $this->perbaruiDendaTerlambat(); // DENDA OTOMATIS

        $datap = Peminjaman::where('status', 'Disetujui')
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        $namaUser = [];
        foreach (User::all() as $u) {
            $namaUser[$u->id] = (string) ($u->username ?? $u->name ?? ('#' . $u->id));
        }

        $namaAlat = [];
        foreach (Alat::all() as $a) {
            $namaAlat[$a->id_alat] = (string) ($a->nama_alat ?? ('#' . $a->id_alat));
        }

        return view('peminjaman.cetak_pemantauan', compact('datap', 'namaUser', 'namaAlat'));
    }

    public function create(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        // Biasanya butuh data user dan alat untuk pilihan dropdown di form
        $users = User::all();
        $alat = Alat::all();
        return view('peminjaman.create', compact('users', 'alat'));
    }

    public function store(Request $request)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = $request->validate([
            'id_user'        => 'required|integer',
            'id_alat'        => 'required|integer',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        // Set status awal peminjaman
        // (Pending belum menahan stok, stok baru berkurang saat disetujui)
        $datap['status'] = 'Pending';
        $datap['denda'] = 0;

        $baru = Peminjaman::create($datap); // TAMBAHAN: hasilnya ditampung ke $baru

        // TAMBAHAN: catat log
        ActivityLog::catat(
            'Peminjaman baru',
            'Alat "' . $this->namaAlat($datap['id_alat']) . '" (' . $datap['jumlah'] . ' unit) dipinjam oleh '
                . $this->namaUser($datap['id_user']) . ' pada ' . $datap['tanggal_pinjam'] . '. Status: Pending.',
            'Peminjaman',
            $baru->id_peminjaman ?? null
        );

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function edit(Request $request, $id_peminjaman)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = Peminjaman::findOrFail($id_peminjaman);
        $users = User::all();
        $alat = Alat::all();
        return view('peminjaman.edit', compact('datap', 'users', 'alat'));
    }

    public function update(Request $request, $id_peminjaman)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $datap = $request->all();

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        $lama = $this->keArray($peminjaman); // TAMBAHAN: simpan data sebelum diubah

        // DENDA OTOMATIS: kalau status baru berubah jadi dikembalikan, hitung denda
        $kembaliList = ['dikembalikan', 'kembali', 'selesai'];
        $statusLama  = strtolower((string) $peminjaman->status);
        $statusBaru  = strtolower((string) ($datap['status'] ?? ''));
        $tglPinjam  = $datap['tanggal_pinjam'] ?? $peminjaman->tanggal_pinjam;
        $tglKembali = $datap['tanggal_kembali'] ?? $peminjaman->tanggal_kembali;

        if (!empty($tglKembali)) {
            // tanggal kembali aktual diisi: denda dihitung otomatis dari tenggat
            $datap['denda'] = $this->hitungDenda($tglPinjam, $tglKembali);
        } elseif (in_array($statusBaru, $kembaliList) && !in_array($statusLama, $kembaliList)) {
            // status jadi dikembalikan tapi tanggal kembali kosong: pakai hari ini
            $datap['tanggal_kembali'] = date('Y-m-d');
            $datap['denda'] = $this->hitungDenda($tglPinjam);
        }

        // STOK: batalkan efek stok data lama, lalu terapkan efek data baru
        // (alat, jumlah, dan status bisa berubah sekaligus di form edit)
        $statusStok = $datap['status'] ?? $peminjaman->status;
        $idAlatBaru = $datap['id_alat'] ?? $peminjaman->id_alat;
        $jumlahBaru = (int) ($datap['jumlah'] ?? $peminjaman->jumlah);

        $this->sesuaikanStok($peminjaman->id_alat, (int) $peminjaman->jumlah, $peminjaman->status, 'Batal');
        if (!$this->sesuaikanStok($idAlatBaru, $jumlahBaru, '', $statusStok)) {
            // stok tidak cukup: pulihkan efek data lama
            $this->sesuaikanStok($peminjaman->id_alat, (int) $peminjaman->jumlah, '', $peminjaman->status);
            return redirect(route('peminjaman.index'))->with('error', 'Stok alat tidak cukup.');
        }

        $peminjaman->update($datap);

        // TAMBAHAN: catat log
        $namaAlat   = $this->namaAlat($peminjaman->id_alat);

        if (in_array($statusBaru, ['dikembalikan', 'kembali', 'selesai'])) {
            // pengembalian: bedakan tepat waktu atau kena denda
            $denda = (int) ($datap['denda'] ?? $peminjaman->denda ?? 0);
            if ($denda > 0) {
                $ket = 'Alat "' . $namaAlat . '" sudah dikembalikan dengan keterlambatan. Denda Rp '
                    . number_format($denda, 0, ',', '.') . '.';
            } else {
                $ket = 'Alat "' . $namaAlat . '" sudah dikembalikan tepat waktu. Denda Rp 0.';
            }
            ActivityLog::catat('Alat dikembalikan', $ket, 'Peminjaman', $id_peminjaman, $lama);
        } else {
            ActivityLog::catat(
                'Edit peminjaman',
                'Data peminjaman alat "' . $namaAlat . '" oleh ' . $this->namaUser($peminjaman->id_user) . ' diubah.',
                'Peminjaman',
                $id_peminjaman,
                $lama
            );
        }

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil diubah.');
    }

    public function delete(Request $request, $id_peminjaman)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // TAMBAHAN: ambil info sebelum data dihapus
        $lama     = $this->keArray($peminjaman);
        $namaAlat = $this->namaAlat($peminjaman->id_alat);
        $namaUser = $this->namaUser($peminjaman->id_user);

        // STOK: kalau peminjaman yang dihapus sedang menahan stok, kembalikan stoknya
        $this->sesuaikanStok($peminjaman->id_alat, (int) $peminjaman->jumlah, $peminjaman->status, 'Dihapus');

        $peminjaman->delete();

        // TAMBAHAN: catat log
        ActivityLog::catat(
            'Hapus peminjaman',
            'Data peminjaman alat "' . $namaAlat . '" oleh ' . $namaUser . ' dihapus.',
            'Peminjaman',
            $id_peminjaman,
            $lama
        );

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // ===================== BARU: katalog untuk peminjam =====================

    // BARU: katalog alat (bisa dicari, menampilkan tersedia / habis berdasarkan stok)
    public function katalog(Request $request)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $q = strtolower(trim($_GET['q'] ?? ''));

        $daftar = [];
        foreach (Alat::all() as $a) {
            if ($q !== '' && strpos(strtolower($a->nama_alat . ' ' . $a->kode_alat), $q) === false) {
                continue;
            }
            // STOK: alat tersedia kalau stoknya masih ada
            $a->tersedia = (int) ($a->stok ?? 0) > 0;
            $daftar[] = $a;
        }

        return view('peminjaman.katalog', ['daftar' => $daftar, 'q' => $q]);
    }

    // ===================== BARU: pengajuan oleh peminjam =====================

    // DIPERBARUI: tampilkan form pengajuan, alat dari katalog otomatis terpilih
    public function ajukan(Request $request)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $alat = Alat::all();
        $dipilih = $_GET['id_alat'] ?? '';
        return view('peminjaman.ajukan', compact('alat', 'dipilih'));
    }

    // BARU: simpan pengajuan (id_user diambil dari user yang login, bukan dari form)
    public function simpan(Request $request)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $datap = $request->validate([
            'id_alat'        => 'required|integer',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
        ]);

        // STOK: jumlah yang diajukan tidak boleh melebihi stok tersedia
        $alatDipilih = Alat::findOrFail($datap['id_alat']);
        $stokTersedia = (int) ($alatDipilih->stok ?? 0);
        if ((int) $datap['jumlah'] > $stokTersedia) {
            return redirect(route('peminjaman.ajukan'))
                ->with('error', 'Jumlah melebihi stok tersedia (' . $stokTersedia . ' unit).');
        }

        $user = User::current();

        $datap['id_user'] = $user->id;
        $datap['status']  = 'Pending';
        $datap['denda']   = 0;

        $baru = Peminjaman::create($datap); // TAMBAHAN: hasilnya ditampung ke $baru

        // TAMBAHAN: catat log
        ActivityLog::catat(
            'Pengajuan peminjaman',
            ($user->username ?? $user->name ?? 'Peminjam') . ' mengajukan peminjaman alat "'
                . $this->namaAlat($datap['id_alat']) . '" (' . $datap['jumlah'] . ' unit) pada '
                . $datap['tanggal_pinjam'] . '. Menunggu persetujuan admin.',
            'Peminjaman',
            $baru->id_peminjaman ?? null
        );

        return redirect(route('dashboard'))->with('success', 'Pengajuan peminjaman terkirim, menunggu persetujuan admin.');
    }

    // ===================== BARU: persetujuan oleh admin =====================

    // BARU: admin menyetujui (stok alat dikurangi)
    public function setujui(Request $request, $id_peminjaman)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // STOK: hanya pengajuan Pending yang boleh disetujui (mencegah stok terpotong dua kali)
        if (strtolower((string) $peminjaman->status) !== 'pending') {
            return redirect($this->kembaliSetelahKeputusan())->with('error', 'Pengajuan ini sudah diproses.');
        }

        // STOK: kurangi stok, tolak kalau stok tidak cukup
        if (!$this->sesuaikanStok($peminjaman->id_alat, (int) $peminjaman->jumlah, $peminjaman->status, 'Disetujui')) {
            return redirect($this->kembaliSetelahKeputusan())->with('error', 'Stok alat tidak cukup.');
        }

        $peminjaman->update(['status' => 'Disetujui']);

        // TAMBAHAN: catat log
        ActivityLog::catat(
            'Peminjaman disetujui',
            'Peminjaman alat "' . $this->namaAlat($peminjaman->id_alat) . '" oleh '
                . $this->namaUser($peminjaman->id_user) . ' disetujui.',
            'Peminjaman',
            $id_peminjaman
        );

        return redirect($this->kembaliSetelahKeputusan())->with('success', 'Peminjaman disetujui.');
    }

    // BARU: admin menolak
    public function tolak(Request $request, $id_peminjaman)
    {
        if ($bukanAdmin = $this->wajibAdmin()) {
            return $bukanAdmin;
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update(['status' => 'Ditolak']);

        // TAMBAHAN: catat log
        ActivityLog::catat(
            'Peminjaman ditolak',
            'Peminjaman alat "' . $this->namaAlat($peminjaman->id_alat) . '" oleh '
                . $this->namaUser($peminjaman->id_user) . ' ditolak.',
            'Peminjaman',
            $id_peminjaman
        );

        return redirect($this->kembaliSetelahKeputusan())->with('success', 'Peminjaman ditolak.');
    }

    // proses pengembalian (dipakai admin maupun peminjam): isi tanggal kembali, hitung denda, catat log
    private function prosesKembali($peminjaman): string
    {
        $lama = $this->keArray($peminjaman);

        // DENDA OTOMATIS: telat 2 hari atau lebih -> Rp 50.000
        // kalau peminjam sudah mengajukan pengembalian, pakai tanggal pengajuannya (bukan tanggal admin klik)
        $tglKembali = !empty($peminjaman->tanggal_kembali)
            ? date('Y-m-d', strtotime((string) $peminjaman->tanggal_kembali))
            : date('Y-m-d');
        $denda = $this->hitungDenda($peminjaman->tanggal_pinjam, $tglKembali);

        // STOK: alat kembali, stok ditambah lagi (tidak berubah kalau sudah berstatus Dikembalikan)
        $this->sesuaikanStok($peminjaman->id_alat, (int) $peminjaman->jumlah, $peminjaman->status, 'Dikembalikan');

        $peminjaman->update(['status' => 'Dikembalikan', 'tanggal_kembali' => $tglKembali, 'denda' => $denda]);

        $ket = 'Alat "' . $this->namaAlat($peminjaman->id_alat) . '" dari '
            . $this->namaUser($peminjaman->id_user) . ' sudah dikembalikan dan tersedia lagi.';
        $pesan = 'Alat sudah dikembalikan dan tersedia lagi.';

        if ($denda > 0) {
            $infoDenda = ' Terlambat, denda Rp ' . number_format($denda, 0, ',', '.') . '.';
            $ket   .= $infoDenda;
            $pesan .= $infoDenda;
        }

        ActivityLog::catat('Alat dikembalikan', $ket, 'Peminjaman', $peminjaman->id_peminjaman, $lama);

        return $pesan;
    }

    // ADMIN: menandai alat sudah dikembalikan (alat jadi tersedia lagi di katalog)
    public function kembalikan(Request $request, $id_peminjaman)
    {
        // DIUBAH: konfirmasi pengembalian khusus admin
        $user = User::current();
        if (!$user || $user->role !== 'admin') {
            return redirect(route('dashboard'))->with('error', 'Konfirmasi pengembalian hanya untuk admin.');
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $pesan = $this->prosesKembali($peminjaman);

        return redirect(route('pengembalian.index'))->with('success', $pesan);
    }

    // PEMINJAM: halaman pengembalian, hanya menampilkan peminjaman milik sendiri
    public function pengembalian(Request $request)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $user = User::current();

        $this->perbaruiDendaTerlambat(); // DENDA OTOMATIS

        $datap = Peminjaman::where('id_user', $user->id)
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        return view('peminjaman.pengembalian', compact('datap'));
    }

    // PEMINJAM: mengajukan pengembalian alat miliknya sendiri (menunggu konfirmasi admin/petugas)
    public function kembalikanSaya(Request $request, $id_peminjaman)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $user = User::current();
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        // hanya boleh mengajukan pengembalian peminjaman milik sendiri
        if ((int) $peminjaman->id_user !== (int) $user->id) {
            return redirect(route('dashboard'))->with('error', 'Kamu hanya bisa mengembalikan alat yang kamu pinjam sendiri.');
        }

        // hanya yang belum dikembalikan (Disetujui atau Pending)
        if (!in_array(strtolower((string) $peminjaman->status), ['disetujui', 'pending'], true)) {
            return redirect(route('peminjaman.pengembalian'))->with('error', 'Peminjaman ini tidak bisa dikembalikan.');
        }

        // sudah pernah diajukan, tinggal menunggu admin
        if (!empty($peminjaman->tanggal_kembali)) {
            return redirect(route('peminjaman.pengembalian'))->with('error', 'Pengembalian sudah diajukan, tunggu konfirmasi admin.');
        }

        $lama = $this->keArray($peminjaman);

        // tanggal kembali = hari ini (saat peminjam menekan tombol); denda dihitung dari tanggal ini
        // (stok baru bertambah saat admin mengonfirmasi lewat kembalikan())
        $tglKembali = date('Y-m-d');
        $denda = $this->hitungDenda($peminjaman->tanggal_pinjam, $tglKembali);

        $peminjaman->update(['tanggal_kembali' => $tglKembali, 'denda' => $denda]);

        $pesan = 'Terima kasih! Pengembalian sudah diajukan. Mohon tunggu konfirmasi admin.';
        $ket   = $this->namaUser($peminjaman->id_user) . ' mengajukan pengembalian alat "'
            . $this->namaAlat($peminjaman->id_alat) . '". Menunggu konfirmasi admin.';

        if ($denda > 0) {
            $infoDenda = ' Terlambat, denda Rp ' . number_format($denda, 0, ',', '.') . '.';
            $pesan .= $infoDenda;
            $ket   .= $infoDenda;
        }

        ActivityLog::catat('Pengajuan pengembalian', $ket, 'Peminjaman', $id_peminjaman, $lama);

        return redirect(route('peminjaman.pengembalian'))->with('success', $pesan);
    }

    // ===================== BARU: riwayat peminjaman =====================

    public function riwayat(Request $request)
    {
        if ($blok = $this->blokirAdmin()) {
            return $blok;
        }

        $user = User::current();

        $this->perbaruiDendaTerlambat(); // DENDA OTOMATIS

        // Mengambil riwayat peminjaman khusus milik user yang sedang login
        $datap = Peminjaman::where('id_user', $user->id)
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        return view('peminjaman.riwayat', compact('datap'));
    }
}