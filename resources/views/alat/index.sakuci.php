```php
@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')

<style>
    .alat-page {
        padding: 25px 10px;
    }

    .alat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .alat-title {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
        position: relative;
        display: inline-block;
    }

    .alat-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 55px;
        height: 4px;
        border-radius: 10px;
        background: #ed8fbc;
    }

    .btn-tambah-alat {
        background: #d6b15c;
        border: 1px solid #d6b15c;
        color: white;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-tambah-alat:hover {
        background: #c39e48;
        border-color: #c39e48;
        color: white;
        transform: translateY(-2px);
    }

    .alat-table-wrapper {
        border-radius: 15px;
        overflow-x: auto;
        border: 1px solid rgba(214, 177, 92, 0.35);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
    }

    .alat-table {
        margin: 0;
        min-width: 650px;
    }

    .alat-table thead th,
    .alat-table th {
        background: rgba(214, 177, 92, 0.15);
        color: inherit;
        font-weight: 700;
        border-bottom: 2px solid #d6b15c;
        padding: 14px;
    }

    .alat-table td {
        padding: 14px;
        vertical-align: middle;
    }

    .alat-table tbody tr {
        transition: 0.2s ease;
    }

    .alat-table tbody tr:hover {
        background: rgba(237, 143, 188, 0.07);
    }

    .kode-alat {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 8px;
        background: rgba(237, 143, 188, 0.13);
        color: #d47f9e;
        font-weight: 600;
        font-size: 13px;
    }

    .btn-edit-alat {
        background: #d6b15c;
        border: 1px solid #d6b15c;
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s ease;
    }

    .btn-edit-alat:hover {
        background: #c39e48;
        border-color: #c39e48;
        color: white;
    }

    .btn-hapus-alat {
        background: #ed8fbc;
        border: 1px solid #ed8fbc;
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .btn-hapus-alat:hover {
        background: #d47f9e;
        border-color: #d47f9e;
        color: white;
    }

    .alat-pagination {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .alat-page {
            padding: 15px 5px;
        }

        .alat-header {
            align-items: flex-start;
        }

        .alat-title {
            font-size: 25px;
        }

        .btn-tambah-alat {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="alat-page">

    <div class="alat-header">
        <h1 class="alat-title">Daftar alat</h1>

        <a href="{{ route('alat.create') }}" class="btn-tambah-alat">
            Tambah Alat
        </a>
    </div>

    <div class="alat-table-wrapper">
        <table class="table table-sm align-middle alat-table">
            <tr>
                <th>No</th>
                <th>nama alat</th>
                <th>kode alat</th>
                <th>Aksi</th>
            </tr>

            @php $no = 1; @endphp

            @foreach ($datal as $alats)
            <tr>
                <td> {{ $no++ }} </td>

                <td> {{ $alats->nama_alat }} </td>

                <td>
                    <span class="kode-alat">
                        {{ $alats->kode_alat }}
                    </span>
                </td>

                <td>
                    <a href="{{ route('alat.edit', ['alat' => $alats->id_alat]) }}" class="btn-edit-alat">
                        Edit
                    </a>

                    <form action="{{ route('alat.delete', ['id' => $alats->id_alat]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn-hapus-alat"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach

        </table>
    </div>

    <div class="alat-pagination">
        {!! $datal->links() !!}
    </div>

</div>

@endsection
```
