<?php

namespace App\Controllers;

use Sakuci\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest('id')->limit(200)->get();

        // peta user_id => username
        $usernames = [];
        try {
            if (class_exists('App\Models\User')) {
                foreach (\App\Models\User::all() as $u) {
                    $usernames[$u->id] = $u->username ?? $u->name ?? null;
                }
            }
        } catch (\Throwable $e) {
            // kalau gagal, kolom Username menampilkan user_id saja
        }

        // pencarian (?q=kata) di username, aksi, dan keterangan
        $q = strtolower(trim((string) ($_GET['q'] ?? '')));
        if ($q !== '') {
            $hasil = [];
            foreach ($logs as $log) {
                $nama = (string) ($usernames[$log->user_id] ?? '');
                $teks = strtolower($nama . ' ' . $log->action . ' ' . $log->keterangan());
                if (strpos($teks, $q) !== false) {
                    $hasil[] = $log;
                }
            }
            $logs = $hasil;
        }

        return view('log/index', ['logs' => $logs, 'usernames' => $usernames]);
    }
}