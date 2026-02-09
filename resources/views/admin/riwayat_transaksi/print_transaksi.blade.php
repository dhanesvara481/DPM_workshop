<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Transaksi</title>
    @vite('resources/js/app.js')
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-slate-900">
    <div class="max-w-md mx-auto p-6">
        <div class="flex items-center justify-between no-print mb-4">
            <a href="{{ route('transaksi.detail', $trx->id) }}" class="px-3 py-2 border rounded">Kembali</a>
            <button onclick="window.print()" class="px-3 py-2 bg-slate-900 text-white rounded">Print</button>
        </div>

        <h1 class="text-lg font-bold text-center">NOTA TRANSAKSI</h1>
        <p class="text-sm text-center text-slate-600">DPM Workshop</p>

        <div class="mt-4 text-sm">
            <div class="flex justify-between"><span>Kode</span><span class="font-semibold">{{ $trx->kode ?? $trx->id }}</span></div>
            <div class="flex justify-between"><span>Tanggal</span><span>{{ $trx->created_at?->format('d/m/Y H:i') }}</span></div>
            <div class="flex justify-between"><span>Pelanggan</span><span>{{ $trx->nama_pelanggan ?? '-' }}</span></div>
        </div>

        <div class="my-4 border-t border-b py-3">
            <table class="w-full text-sm">
                <thead class="text-slate-600">
                    <tr>
                        <th class="text-left">Item</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Sub</th>
                    </tr>
                </thead>
                <tbody>
                @php $subtotal = 0; @endphp
                @foreach(($items ?? []) as $it)
                    @php
                        $nama  = $it->nama_barang ?? $it->barang->nama_barang ?? '-';
                        $harga = $it->harga ?? $it->barang->harga ?? 0;
                        $qty   = $it->qty ?? $it->jumlah ?? 0;
                        $sub   = $harga * $qty;
                        $subtotal += $sub;
                    @endphp
                    <tr>
                        <td class="py-1">{{ $nama }}</td>
                        <td class="py-1 text-right">{{ $qty }}</td>
                        <td class="py-1 text-right">Rp{{ number_format($sub,0,',','.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @php
            $diskon = $trx->diskon ?? 0;
            $pajak  = $trx->pajak ?? 0;
            $total  = $trx->total ?? (($subtotal - $diskon) + $pajak);
        @endphp

        <div class="text-sm space-y-1">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp{{ number_format($subtotal,0,',','.') }}</span></div>
            <div class="flex justify-between"><span>Diskon</span><span>- Rp{{ number_format($diskon,0,',','.') }}</span></div>
            <div class="flex justify-between"><span>Pajak</span><span>Rp{{ number_format($pajak,0,',','.') }}</span></div>
            <div class="flex justify-between font-bold text-base border-t pt-2"><span>Total</span><span>Rp{{ number_format($total,0,',','.') }}</span></div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-500">Terima kasih 🙏</p>
    </div>

    <script>
        // auto open print dialog saat halaman dibuka
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
