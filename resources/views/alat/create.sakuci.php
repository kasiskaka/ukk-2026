@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')
<h1>Tambahkan Alat</h1>
<form action="{{ route('alat.store') }}" method="POST" class="d-flex flex-column gap-2">
    @csrf
    
    <label>Nama alat</label>
    <input type="text" name="nama_alat" id="nama_kategori" class="form-control"  value="{{ old('nama_alat') }}" required>
    <label>Kode alat</label>
    <input type="text" name="kode_alat" id="kode_kategori" class="form-control" value="{{ old('kode_alat') }}" required>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection