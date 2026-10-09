@extends('layouts.app') 

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark mb-0">Riwayat Peminjaman Saya</h3>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4 text-center" style="width: 60px;">No</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-end">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datap) && count($datap) > 0): ?>
                            <?php $no = 1; foreach ($datap as $item): ?>
                                <tr>
                                    <td class="text-center fw-bold px-4"><?= $no++ ?></td>
                                    <td class="px-4"><?= htmlspecialchars($item->tanggal_pinjam) ?></td>
                                    <td class="text-center px-4"><?= htmlspecialchars($item->jumlah) ?></td>
                                    <td class="text-center px-4">
                                        <?php 
                                            $badge = 'bg-warning text-dark';
                                            if (strtolower($item->status) === 'disetujui') $badge = 'bg-success';
                                            if (strtolower($item->status) === 'ditolak') $badge = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badge ?> px-3 py-2 rounded-pill">
                                            <?= htmlspecialchars($item->status) ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold px-4">Rp <?= number_format($item->denda ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection