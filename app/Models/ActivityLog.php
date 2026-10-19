<?php

namespace App\Models;

use Sakuci\Database\Model;

class ActivityLog extends Model
{
    protected static ?string $table = 'activity_logs';

    // matikan pengisian otomatis created_at / updated_at
    public bool $timestamps = false;

    // KODE ASLI (tetap)
    protected array $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    // TAMBAHAN: panggil dari controller mana pun untuk mencatat log
    public static function catat(string $action, string $keterangan, string $model = '', $modelId = null, $lama = null, array $baru = []): void
    {
        try {
            // keterangan disimpan di dalam new_values (kolom yang sudah ada)
            $baru['keterangan'] = $keterangan;

            static::create([
                'user_id'    => static::idUserLogin(), // DIUBAH: ambil dari user yang login
                'action'     => $action,
                'model'      => $model,
                'model_id'   => $modelId,
                'old_values' => $lama !== null ? json_encode($lama, JSON_UNESCAPED_UNICODE) : null,
                'new_values' => json_encode($baru, JSON_UNESCAPED_UNICODE),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // gagal mencatat log tidak boleh merusak proses utama
        }
    }

    // TAMBAHAN: ambil id user yang sedang login
    private static function idUserLogin()
    {
        try {
            $u = \App\Models\User::current();
            if ($u && isset($u->id)) {
                return $u->id;
            }
        } catch (\Throwable $e) {
        }
        return $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? null;
    }

    // TAMBAHAN: baca isi new_values sebagai array
    private function dataBaru(): array
    {
        $d = json_decode((string) $this->new_values, true);
        return is_array($d) ? $d : [];
    }

    // TAMBAHAN: isi kolom Sesudah tanpa kalimat keterangan
    public function sesudah(): string
    {
        $d = $this->dataBaru();
        if (!$d) {
            return (string) $this->new_values;
        }
        unset($d['keterangan']);
        return $d ? json_encode($d, JSON_UNESCAPED_UNICODE) : '-';
    }

    // TAMBAHAN: pakai teks yang tersimpan, kalau tidak ada pakai versi otomatis
    public function keterangan(): string
    {
        $d = $this->dataBaru();
        if (!empty($d['keterangan'])) {
            return (string) $d['keterangan'];
        }

        $aksi  = strtolower((string) $this->action);
        $objek = (string) $this->model;
        $id    = (string) $this->model_id;

        $kata = [
            'create'  => 'menambahkan',
            'created' => 'menambahkan',
            'update'  => 'mengubah',
            'updated' => 'mengubah',
            'delete'  => 'menghapus',
            'deleted' => 'menghapus',
            'login'   => 'masuk ke sistem',
            'logout'  => 'keluar dari sistem',
        ];

        $teks = $kata[$aksi] ?? $aksi;

        if ($objek === '') {
            return ucfirst($teks) . '.';
        }

        return ucfirst($teks) . ' data ' . $objek . ($id !== '' ? ' #' . $id : '') . '.';
    }
}