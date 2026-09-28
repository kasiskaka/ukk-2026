@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Data Peminjaman</h2>
    
    <form action="{{ route('peminjaman.update', $data->id_peminjaman) }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="id_user" class="form-label">Pilih User / Peminjam</label>
            <select name="id_user" id="id_user" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $data->id_user == $user->id ? 'selected' : '' }}>
                        {{ $user->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_alat" class="form-label">Pilih Alat</label>
            <select name="id_alat" id="id_alat" class="form-control" required>
                @foreach($alat as $item)
                    <option value="{{ $item->id }}" {{ $data->id_alat == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_alat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $data->jumlah }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="{{ $data->tanggal_pinjam }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_kembali" class="form-label">Tanggal Kembali (Aktual)</label>
            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ $data->tanggal_kembali }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Pending" {{ $data->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Disetujui" {{ $data->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ $data->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="Dikembalikan" {{ $data->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="denda" class="form-label">Denda (Rp)</label>
            <input type="number" name="denda" id="denda" class="form-control" value="{{ $data->denda }}" min="0">
        </div>

        <button type="submit" class="btn btn-success">Update Data</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection