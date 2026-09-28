@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Kategori</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($kategori as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->kode_kategori }}</td>
            <td>{{ $item->nama_kategori }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>
                <a href="{{ route('kategori.edit', ['id_kategori' => $item->id_kategori]) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('kategori.destroy', ['id_kategori' => $item->id_kategori]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{!! $kategori->links() !!}
@endsection