@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark mb-0">Pengembalian Alat Saya</h3>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4 text-center" style="width: 60px;">No</th>
                            <th class="py-3 px-4">Alat</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Tanggal Kembali</th>
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-end">Denda</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datap) && count($datap) > 0): ?>
                            <?php $no = 1; foreach ($datap as $item): ?>
                                <?php
                                    $alatnya   = \App\Models\Alat::find($item->id_alat);
                                    $status    = strtolower((string) $item->status);
                                    $belumSelesai = in_array($status, ['disetujui', 'pending'], true);
                                    $diajukan    = ($belumSelesai && !empty($item->tanggal_kembali)); // sudah menekan Kembalikan, menunggu admin
                                    $bisaKembali = ($belumSelesai && empty($item->tanggal_kembali));
                                ?>
                                <tr data-peminjaman="<?= $item->id_peminjaman ?>" data-diajukan="<?= $diajukan ? '1' : '0' ?>">
                                    <td class="text-center fw-bold px-4"><?= $no++ ?></td>
                                    <td class="px-4"><?= $alatnya->nama_alat ?? ('#' . $item->id_alat) ?></td>
                                    <td class="px-4"><?= $item->tanggal_pinjam ?></td>
                                    <td class="px-4"><?= !empty($item->tanggal_kembali) ? $item->tanggal_kembali : '-' ?></td>
                                    <td class="text-center px-4"><?= $item->jumlah ?></td>
                                    <td class="text-center px-4">
                                        <?php if ($diajukan): ?>
                                            <span class="badge rounded-pill bg-info text-dark">Menunggu konfirmasi admin</span>
                                        <?php elseif ($status === 'dikembalikan'): ?>
                                            <span class="badge rounded-pill bg-success">Dikembalikan</span>
                                        <?php elseif ($status === 'ditolak'): ?>
                                            <span class="badge rounded-pill bg-danger">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-warning text-dark"><?= $item->status ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end px-4">Rp <?= number_format((int) $item->denda, 0, ',', '.') ?></td>
                                    <td class="text-center px-4">
                                        <?php if ($bisaKembali): ?>
                                            <form method="POST"
                                                  action="/pengembalian-saya/<?= $item->id_peminjaman ?>/kembalikan"
                                                  onsubmit="if (!confirm('Kembalikan alat ini sekarang? Pengembalian akan dikonfirmasi admin.')) { return false; } try { sessionStorage.setItem('kembali_id', '<?= $item->id_peminjaman ?>'); } catch (e) {} return true;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Kembalikan</button>
                                            </form>
                                        <?php elseif ($diajukan): ?>
                                            <span class="text-muted small">Menunggu admin</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-muted small mt-2 mb-0">
        Tekan Kembalikan untuk mengajukan pengembalian alat.
        Pengembalian baru dianggap selesai setelah dikonfirmasi (di-ACC) oleh admin.
    </p>
</div>

<!-- Pemberitahuan setelah peminjam menekan Kembalikan -->
<div class="modal fade" id="modalTerimaKasih" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-center p-3">
            <div class="modal-body">
                <div class="fs-1 text-success mb-2">&#10003;</div>
                <h5 class="fw-bold mb-2">Terima kasih!</h5>
                <p class="text-muted mb-3">
                    Pengembalian alat sudah diajukan.<br>
                    Mohon tunggu konfirmasi (ACC) dari admin.
                </p>
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        var id = null;
        try {
            id = sessionStorage.getItem('kembali_id');
            sessionStorage.removeItem('kembali_id');
        } catch (e) {}

        if (!id) return;

        // tampilkan hanya kalau pengajuannya benar-benar tercatat (baris sudah "menunggu admin")
        var baris = document.querySelector('tr[data-peminjaman="' + id + '"]');
        if (!baris || baris.getAttribute('data-diajukan') !== '1') return;

        var el = document.getElementById('modalTerimaKasih');
        if (window.bootstrap && el) {
            new bootstrap.Modal(el).show();
        } else {
            alert('Terima kasih! Pengembalian alat sudah diajukan. Mohon tunggu konfirmasi dari admin.');
        }
    });
</script>
@endsection