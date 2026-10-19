<style>
    .log-header {
        background: #fff;
        border: 2px solid #6d2b52;
        border-radius: 20px;
        padding: 20px;
        margin: 16px;
        box-shadow: 0 6px 0 #f3a9c8;
    }
    .log-badge {
        display: inline-block;
        background: #fce4ef;
        color: #6d2b52;
        border: 2px solid #6d2b52;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: .5px;
        padding: 6px 14px;
        border-radius: 999px;
    }
    .log-header p { margin: 0 16px 8px; color: #7a5a6b; line-height: 1.5; }
    .log-search { margin: 16px; display: flex; gap: 10px; }
    .log-search input {
        flex: 1;
        box-sizing: border-box;
        padding: 12px 18px;
        border: 2px solid #6d2b52;
        border-radius: 999px;
        font-size: 16px;
        outline: none;
        background: #fff;
    }
    .log-search button {
        padding: 12px 28px;
        background: #6d2b52;
        color: #fff;
        border: 2px solid #6d2b52;
        border-radius: 999px;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
    }
    .log-table-wrap {
        border: 2px solid #6d2b52;
        border-radius: 16px;
        overflow-x: auto;
        margin: 16px;
    }
    #tabel-log { width: 100%; min-width: 800px; border-collapse: collapse; background: #fff; }
    #tabel-log th, #tabel-log td { padding: 12px; border: 1px solid #f3d3e2; vertical-align: top; text-align: left; }
    #tabel-log thead tr { background: #6d2b52; color: #fff; }
    #tabel-log tbody tr:nth-child(even) { background: #fff7fb; }
</style>

<div class="log-header">
    <span class="log-badge">AKTIVITAS SISTEM</span>
    <h2 style="color:#6d2b52; font-weight:800; margin:16px;">Log Aktivitas</h2>
    <p>Catatan aktivitas pengguna yang dilakukan di dalam sistem.</p>
</div>

<form class="log-search" method="get" onsubmit="return false;">
    <input type="text" id="cari-log" name="q"
           placeholder="Cari berdasarkan username atau aksi..."
           value="<?= htmlspecialchars((string) ($_GET['q'] ?? '')) ?>">
    <button type="button" onclick="cariLog()">Cari</button>
</form>

<div class="log-table-wrap">
<table id="tabel-log">
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Aktivitas</th>
            <th>Keterangan</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $i => $log): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars((string) (($usernames ?? [])[$log->user_id] ?? ($log->user_id ?? '-'))) ?></td>
                <td><b><?= htmlspecialchars((string) $log->action) ?></b></td>
                <td><?= htmlspecialchars($log->keterangan()) ?></td>
                <td><?= htmlspecialchars((string) $log->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<script>
function cariLog() {
    var kata = document.getElementById('cari-log').value.toLowerCase();
    document.querySelectorAll('#tabel-log tbody tr').forEach(function (tr) {
        tr.style.display = tr.innerText.toLowerCase().indexOf(kata) > -1 ? '' : 'none';
    });
}
document.getElementById('cari-log').addEventListener('keyup', cariLog);
</script>