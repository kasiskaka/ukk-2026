@extends('layouts.app')

@section('title', 'Katalog Alat')

@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Ajukan Peminjaman</h1>
            <p class="text-secondary">Cari alat yang kamu butuhkan, lalu klik <strong>Pinjam</strong>.</p>

            <form method="GET" action="{{ route('peminjaman.katalog') }}" class="d-flex gap-2 mb-3">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama atau kode alat...">
                <button type="submit" class="btn btn-gold">Cari</button>
            </form>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                    @php $no = 1; @endphp
                    @forelse ($daftar as $a)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $a->nama_alat }}</td>
                        <td>{{ $a->kode_alat }}</td>
                        <td>
                            @if ($a->tersedia)
                                <span class="badge text-bg-success">Tersedia</span>
                            @else
                                <span class="badge text-bg-secondary">Sedang dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if ($a->tersedia)
                                <a href="{{ route('peminjaman.ajukan') }}?id_alat={{ $a->id_alat }}" class="btn btn-gold btn-sm">Pinjam</a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Tidak tersedia</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Alat tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>

@endsection