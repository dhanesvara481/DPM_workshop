<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota - {{ $trx->kode_transaksi ?? 'INV-'.$trx->id }}</title>
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
        <a href="{{ route('detail_riwayat_transaksi', $trx->id ?? 0) }}"
           class="px-3 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50 transition">
            Kembali
        </a>
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
                    $namaItem    = $it->nama_barang ?? '-';
                    $harga       = (int) $it->harga;
                    $qty         = (int) $it->qty;
                    $sub         = $harga * $qty;
                    $isJasaMurni = empty($it->barang_id);
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

    @php
        $subtotalBarang = (int)($trx->subtotal_barang ?? 0);
        $biayaJasa      = (int)($trx->biaya_jasa ?? 0);
        $total          = (int)($trx->total ?? 0);
    @endphp

    <div class="text-sm space-y-1">
        <div class="flex justify-between">
            <span class="text-slate-500">Subtotal Barang</span>
            <span>Rp{{ number_format($subtotalBarang, 0, ',', '.') }}</span>
        </div>
        @if($biayaJasa > 0)
        <div class="flex justify-between">
            <span class="text-slate-500">Biaya Jasa</span>
            <span>Rp{{ number_format($biayaJasa, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="flex justify-between font-bold text-base border-t pt-2 mt-2">
            <span>Total</span>
            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-500">Terima kasih telah menggunakan layanan kami 🙏</p>

</div>

<script>
    window.addEventListener('load', () => window.print());
</script>
</body>
</html>