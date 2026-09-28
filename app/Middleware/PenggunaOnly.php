<?php

namespace App\Middleware;

/** Dibuat otomatis oleh RoleController -- hanya role "pengguna" yang boleh lewat. */
class PenggunaOnly extends EnsureRole
{
    protected function roles(): array
    {
        return ['pengguna'];
    }
}
