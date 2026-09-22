```php
@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')

<style>
    .kategori-page {
        position: relative;
        padding: 35px 10px 50px;
        overflow: hidden;
    }

    /* Dekorasi tema */
    .kategori-page::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(237, 143, 188, .06);
        top: -110px;
        left: -90px;
        pointer-events: none;
    }

    .kategori-page::after {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(214, 177, 92, .06);
        bottom: -130px;
        right: -100px;
        pointer-events: none;
    }

    /* Header */
    .kategori-header {
        position: relative;
        z-index: 2;
        margin-bottom: 25px;
    }

    .kategori-title {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 6px;
        letter-spacing: -.5px;
    }

    .kategori-title::after {
        content: "";
        display: block;
        width: 55px;
        height: 3px;
        margin-top: 10px;
        border-radius: 10px;
        background: #d6b15c;
    }

    /* Tombol tambah */
    .btn-tambah-kategori {
        color: #ffffff !important;
        background: #d6b15c;
        border: 1px solid #d6b15c;
        border-radius: 9px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .25s ease;
        display: inline-block;
    }

    .btn-tambah-kategori:hover {
        color: #ffffff !important;
        background: #c39e48;
        border-color: #c39e48;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(214, 177, 92, .20);
    }

    /* Table */
    .kategori-table-box {
        position: relative;
        z-index: 2;
        overflow-x: auto;
        border: 1px solid rgba(128, 128, 128, .18);
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .04);
    }

    .kategori-table {
        width: 100%;
        min-width: 700px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .kategori-table th {
        padding: 15px 18px;
        color: #b28a32;
        background: rgba(214, 177, 92, .08);
        border-bottom: 1px solid rgba(214, 177, 92, .25);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .kategori-table td {
        padding: 15px 18px;
        border-bottom: 1px solid rgba(128, 128, 128, .12);
        font-size: 14px;
        vertical-align: middle;
    }

    .kategori-table tr:last-child td {
        border-bottom: none;
    }

    .kategori-table tbody tr {
        transition: .2s ease;
    }

    .kategori-table tbody tr:hover {
        background: rgba(237, 143, 188, .04);
    }

    /* Nomor */
    .nomor {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        height: 31px;
        border-radius: 8px;
        color: #b28a32;
        background: rgba(214, 177, 92, .10);
        font-size: 12px;
        font-weight: 700;
    }

    /* Kode */
    .kode {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        color: #ed8fbc;
        background: rgba(237, 143, 188, .08);
        border: 1px solid rgba(237, 143, 188, .20);
        font-size: 12px;
        font-weight: 700;
    }

    /* Tombol aksi */
    .btn-edit {
        color: #b28a32 !important;
        background: rgba(214, 177, 92, .08);
        border: 1px solid rgba(214, 177, 92, .30);
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .btn-edit:hover {
        color: #ffffff !important;
        background: #d6b15c;
        border-color: #d6b15c;
    }

    .btn-hapus {
        color: #d47f9e !important;
        background: rgba(237, 143, 188, .07);
        border: 1px solid rgba(237, 143, 188, .25);
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .btn-hapus:hover {
        color: #ffffff !important;
        background: #ed8fbc;
        border-color: #ed8fbc;
    }

    /* Responsive */
    @media (max-width: 768px) {

        .kategori-page {
            padding: 25px 5px 40px;
        }

        .kategori-title {
            font-size: 25px;
        }

        .btn-tambah-kategori {
            width: 100%;
            text-align: center;
        }
    }
</style>


<div class="kategori-page">

    <div class="kategori-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h1 class="kategori-title">
                    Daftar Kategori
                </h1>
            </div>

            <div>
                <a href="{{ route('kategori.create') }}"
                   class="btn-tambah-kategori">
                    Tambah Alat
                </a>
            </div>

        </div>

    </div>


    <div class="kategori-table-box">

        <table class="kategori-table">

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

                <td>
                    <span class="nomor">
                        {{ $no++ }}
                    </span>
                </td>

                <td>
                    {{ $kategoris->nama_kategori }}
                </td>

                <td>
                    <span class="kode">
                        {{ $kategoris->kode_kategori }}
                    </span>
                </td>

                <td>
                    {{ $kategoris->keterangan }}
                </td>

                <td>

                    <a href="{{ route('kategori.edit', ['kategori' => $kategoris->id_kategori]) }}"
                       class="btn btn-info btn-sm btn-edit">
                        Edit
                    </a>

                    <form action="{{ route('kategori.delete', ['id' => $kategoris->id_kategori]) }}"
                          method="POST"
                          style="display: inline-block;">

                        @csrf

                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger btn-sm btn-hapus"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                            Hapus
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </table>

    </div>


    {!! $data->links() !!}

</div>

@endsection
```
