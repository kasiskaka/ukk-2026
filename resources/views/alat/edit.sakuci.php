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

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection