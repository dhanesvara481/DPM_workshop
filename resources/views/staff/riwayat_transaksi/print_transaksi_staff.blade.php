<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota - {{ $trx->kode_transaksi ?? 'INV-' . $trx->id }}</title>
    @vite('resources/js/app.js')
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-slate-900">
<div class="max-w-md mx-auto p-6">

    <div class="no-print flex items-center justify-between mb-4">
        <button onclick="window.close()"
                style="padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;font-weight:600;color:#0f172a;background:#fff;cursor:pointer;">
            ← Kembali
        </button>
        <button onclick="window.print()"
                class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-800 transition">
            Print
        </button>
    </div>

    <h1 class="text-lg font-bold text-center">NOTA TRANSAKSI</h1>
    <p class="text-sm text-center text-slate-500">DPM Workshop</p>

    <div class="mt-4 text-sm space-y-1">
        <div class="flex justify-between">
            <span class="text-slate-500">Kode</span>
            <span class="font-semibold">{{ $trx->kode_transaksi ?? ('INV-' . $trx->id) }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-500">Tanggal</span>
            <span>{{ \Carbon\Carbon::parse($trx->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-500">Pelanggan</span>
            <span class="font-semibold">{{ $trx->nama_pengguna ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-500">Kontak</span>
            <span>{{ $trx->kontak ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-500">Status</span>
            <span class="font-semibold uppercase">{{ $trx->status ?? 'Pending' }}</span>
        </div>
    </div>

    {{-- Tabel item --}}
    <div class="my-4 border-t border-b py-3">
        <table class="w-full text-sm">
            <thead class="text-slate-500">
                <tr>
                    <th class="text-left pb-2">Item</th>
                    <th class="text-right pb-2">Qty</th>
                    <th class="text-right pb-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @foreach(($items ?? collect()) as $it)
                @php
                    $namaItem    = $it->nama_barang ?? $it->deskripsi ?? '-';
                    $harga       = (int) ($it->harga ?? $it->total ?? 0);
                    $qty         = (int) ($it->jumlah ?? $it->qty ?? 0);
                    $sub         = (int) ($it->total ?? ($harga * $qty));
                    $isJasaMurni = ($it->tipe_transaksi ?? '') === 'Jasa';
                @endphp
                <tr>
                    <td class="py-1 text-slate-900">
                        {{ $namaItem }}
                        @if($isJasaMurni)
                            <span class="text-xs text-slate-400">(Jasa)</span>
                        @endif
                    </td>
                    <td class="py-1 text-right text-slate-700">{{ $isJasaMurni ? '-' : $qty }}</td>
                    <td class="py-1 text-right text-slate-700">Rp{{ number_format($sub, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ringkasan keuangan --}}
    @php
        $subtotalBarang = (int)   ($trx->subtotal_barang ?? 0);
        $biayaJasa      = (int)   ($trx->biaya_jasa      ?? 0);
        $subtotal       = (int)   ($trx->subtotal        ?? 0);
        $diskon         = (float) ($trx->diskon          ?? 0);
        $pajakPct       = (int)   ($trx->pajak           ?? 0);
        $pajakNominal   = (int)   ($trx->pajak_nominal   ?? 0);
        $grandTotal     = (int)   ($trx->grand_total     ?? $subtotal);
    @endphp

    <div class="text-sm space-y-1">
        @if($subtotalBarang > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Subtotal Barang</span>
            <span>Rp{{ number_format($subtotalBarang, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($biayaJasa > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Biaya Jasa</span>
            <span>Rp{{ number_format($biayaJasa, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($diskon > 0 || $pajakPct > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Subtotal</span>
            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($diskon > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Diskon</span>
            <span class="text-rose-600">− Rp{{ number_format($diskon, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($pajakPct > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Pajak ({{ $pajakPct }}%)</span>
            <span>+ Rp{{ number_format($pajakNominal, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="flex justify-between font-bold text-base border-t pt-2 mt-2">
            <span>Grand Total</span>
            <span>Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
    </div>

    @if(($trx->catatan ?? '-') !== '-')
    <div class="mt-4 text-xs text-slate-500 border-t pt-3">
        <span class="font-semibold text-slate-700">Catatan:</span> {{ $trx->catatan }}
    </div>
    @endif

    <p class="mt-6 text-center text-xs text-slate-500">Terima kasih telah menggunakan layanan kami 🙏</p>

</div>

<script>
    window.addEventListener('load', () => window.print());
</script>
</body>
</html>