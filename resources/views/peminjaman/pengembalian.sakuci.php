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
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-end">Denda</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datap) && count($datap) > 0): ?>
                            <?php $no = 1; foreach ($datap as $item): ?>
                                <?php $alatnya = \App\Models\Alat::find($item->id_alat); ?>
                                <tr>
                                    <td class="text-center fw-bold px-4"><?= $no++ ?></td>
                                    <td class="px-4"><?= $alatnya->nama_alat ?? ('#' . $item->id_alat) ?></td>
                                    <td class="px-4"><?= $item->tanggal_pinjam ?></td>
                                    <td class="text-center px-4"><?= $item->jumlah ?></td>
                                    <td class="text-center px-4">
                                        <span class="badge rounded-pill bg-warning text-dark"><?= $item->status ?></span>
                                    </td>
                                    <td class="text-end px-4">Rp <?= number_format((int) $item->denda, 0, ',', '.') ?></td>
                                    <td class="text-center px-4">
                                        <?php if (strtolower((string) $item->status) === 'disetujui'): ?>
                                            <form method="POST"
                                                  action="/pengembalian-saya/<?= $item->id_peminjaman ?>/kembalikan"
                                                  onsubmit="return confirm('Kembalikan alat ini sekarang?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Kembalikan</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-muted small mt-2 mb-0">
        Tombol Kembalikan hanya muncul untuk peminjaman yang sudah disetujui admin.
    </p>
</div>
@endsection