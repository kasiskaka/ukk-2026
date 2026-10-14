<h2 style="color:#6d2b52; font-weight:800; margin:16px;">Log Aktivitas</h2>

<div style="overflow-x:auto; margin:16px;">
<table style="width:100%; border-collapse:collapse; background:#fff;">
    <thead>
        <tr style="background:#fce4ef; color:#6d2b52; text-align:left;">
            <th style="padding:10px;">No</th>
            <th style="padding:10px;">Waktu</th>
            <th style="padding:10px;">User ID</th>
            <th style="padding:10px;">Aksi</th>
            <th style="padding:10px;">Data</th>
            <th style="padding:10px;">Sebelum</th>
            <th style="padding:10px;">Sesudah</th>
            <th style="padding:10px;">IP</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $i => $log): ?>
            <tr style="border-top:1px solid #f3d3e2;">
                <td style="padding:10px;"><?= $i + 1 ?></td>
                <td style="padding:10px;"><?= htmlspecialchars((string) $log->created_at) ?></td>
                <td style="padding:10px;"><?= htmlspecialchars((string) ($log->user_id ?? '-')) ?></td>
                <td style="padding:10px;"><?= htmlspecialchars((string) $log->action) ?></td>
                <td style="padding:10px;"><?= htmlspecialchars((string) $log->model) ?> #<?= htmlspecialchars((string) $log->model_id) ?></td>
                <td style="padding:10px;"><small><?= htmlspecialchars((string) $log->old_values) ?></small></td>
                <td style="padding:10px;"><small><?= htmlspecialchars((string) $log->new_values) ?></small></td>
                <td style="padding:10px;"><?= htmlspecialchars((string) $log->ip_address) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>