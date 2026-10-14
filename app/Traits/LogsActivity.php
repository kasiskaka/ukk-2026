<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public function save(): bool
    {
        $isNew = ! $this->exists;
        $old   = $this->original;

        $result = parent::save();

        if ($result) {
            $new = $this->attributes;

            if ($isNew) {
                $this->tulisLog('created', null, $new);
            } else {
                $before = [];
                $after  = [];

                foreach ($new as $key => $value) {
                    if (in_array($key, ['created_at', 'updated_at'], true)) {
                        continue;
                    }

                    if (($old[$key] ?? null) != $value) {
                        $before[$key] = $old[$key] ?? null;
                        $after[$key]  = $value;
                    }
                }

                if ($after !== []) {
                    $this->tulisLog('updated', $before, $after);
                }
            }
        }

        return $result;
    }

    public function delete(): bool
    {
        $old = $this->attributes;

        $result = parent::delete();

        if ($result) {
            $this->tulisLog('deleted', $old, null);
        }

        return $result;
    }

    protected function tulisLog(string $action, ?array $old, ?array $new): void
    {
        // jangan simpan data sensitif
        foreach (['password', 'remember_token'] as $hidden) {
            unset($old[$hidden], $new[$hidden]);
        }

        try {
            ActivityLog::create([
                'user_id'    => null, // nanti kita isi bareng
                'action'     => $action,
                'model'      => class_basename(static::class),
                'model_id'   => $this->getKey(),
                'old_values' => $old ? json_encode($old, JSON_UNESCAPED_UNICODE) : null,
                'new_values' => $new ? json_encode($new, JSON_UNESCAPED_UNICODE) : null,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // kalau gagal nulis log, jangan sampai bikin aplikasi error
        }
    }
}