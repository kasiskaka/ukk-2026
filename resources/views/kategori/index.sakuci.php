@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')
<h1>Daftar Kategori</h1>
<a href="{{ route('kategori.create') }}" class="btn btn-success mb-3">Tambah Alat</a>

<table class = "table table-sm align-middle"> 
<tr>
    <th>No</th>
    <th>nama kategori</th>
    <th>kode kategori</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>
@php $no = 1; @endphp
@foreach ($data as $kategoris)
<tr>
    <td> {{ $no++ }} </td>
    <td> {{ $kategoris->nama_kategori }} </td>
    <td> {{ $kategoris->kode_kategori }} </td>
    <td> {{ $kategoris->keterangan }} </td>
    <td>
         <a href="{{ route('kategori.edit', ['kategori' => $kategoris->id_kategori]) }}" class="btn btn-info btn-sm">Edit</a>
         <form action="{{ route('kategori.delete', ['id' => $kategoris->id_kategori]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                        </form>
    </td>
</tr>
@endforeach
</table>
{!! $data->links() !!}
@endsection