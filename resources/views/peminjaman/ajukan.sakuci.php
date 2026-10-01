@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Ajukan Peminjaman</h1>

            <form method="POST" action="{{ route('peminjaman.simpan') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Alat</label>
                    <select name="id_alat" class="form-select" required>
                        <option value="">-- Pilih alat --</option>
                        @foreach ($alat as $a)
                            <option value="{{ $a->id_alat }}">{{ $a->nama_alat }} ({{ $a->kode_alat }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" value="1" min="1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-gold">Kirim Pengajuan</button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>

@endsection