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
                    <select name="id_alat" id="pilihAlat" class="form-select" required>
                        <option value="" data-stok="0">-- Pilih alat --</option>
                        @foreach ($alat as $a)
                            @php $stok = (int) ($a->stok ?? 0); @endphp
                            <option value="{{ $a->id_alat }}"
                                    data-stok="{{ $stok }}"
                                    {{ (string) $dipilih === (string) $a->id_alat ? 'selected' : '' }}
                                    {{ $stok <= 0 ? 'disabled' : '' }}>
                                {{ $a->nama_alat }} ({{ $a->kode_alat }}) - {{ $stok > 0 ? 'sisa ' . $stok : 'habis' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="inputJumlah" value="1" min="1" class="form-control" required>
                    <div class="form-text" id="infoStok"></div>
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

    <script>
        (function () {
            var pilih  = document.getElementById('pilihAlat');
            var jumlah = document.getElementById('inputJumlah');
            var info   = document.getElementById('infoStok');

            // Batasi jumlah sesuai stok alat yang dipilih
            function aturBatas() {
                var opsi = pilih.options[pilih.selectedIndex];
                var stok = parseInt(opsi.getAttribute('data-stok'), 10) || 0;

                if (pilih.value === '' || stok <= 0) {
                    jumlah.removeAttribute('max');
                    info.textContent = '';
                    return;
                }

                jumlah.setAttribute('max', stok);
                if (parseInt(jumlah.value, 10) > stok) {
                    jumlah.value = stok;
                }
                info.textContent = 'Stok tersedia: ' + stok + ' unit';
            }

            pilih.addEventListener('change', aturBatas);
            aturBatas();
        })();
    </script>

@endsection