@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')
<h1>Edit alat</h1>
<form action="{{ route('alat.update', ['id_alat' => $datal->id_alat]) }}" method="POST" class="d-flex flex-column gap-2">
    @csrf
    @method('PUT')

    <label>Nama alat</label>
    <input type="text" name="nama_alat" id="nama_alat" class="form-control"  value="{{ $datal->nama_alat }}" required>
    
    <label>Kode alat</label>
    <input type="text" name="kode_alat" id="kode_alat" class="form-control" value="{{ $datal->kode_alat }}" required>

    <label>Stok tersedia</label>
    <input type="number" name="stok" id="stok" class="form-control" min="0" value="{{ $datal->stok ?? 0 }}" required>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection