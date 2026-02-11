<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Transaksi (Detail) - Admin</title>
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    {{-- COPY sidebar full dari halaman riwayat_transaksi kamu --}}
    <aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 h-screen
              w-[280px] md:w-[280px]
              -translate-x-full md:translate-x-0
              bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
              border-r border-white/5
              transition-[transform,width] duration-300 ease-out
              overflow-y-auto">

        {{-- ... sidebar kamu di sini ... --}}
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-30 hidden md:hidden"></div>

    {{-- ================= MAIN ================= --}}
    <div class="flex-1 md:ml-[280px]">

        {{-- Topbar sederhana (mirip mockup: area gelap) --}}
        <header class="sticky top-0 z-20">
            <div class="h-14 bg-slate-700/80 border-b border-slate-700 flex items-center justify-end px-4">
                {{-- icon dummy: notif & logout (optional) --}}
                <div class="flex items-center gap-3 text-white/80">
                    <button class="h-9 w-9 rounded-lg hover:bg-white/10 grid place-items-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0m6 0H9"/>
                        </svg>
                    </button>
                    <button class="h-9 w-9 rounded-lg hover:bg-white/10 grid place-items-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H9m9 0l-3-3m3 3l-3 3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="px-4 sm:px-6 py-8">

            {{-- CARD BESAR ABU-ABU (layout seperti mockup) --}}
            <section class="max-w-5xl mx-auto bg-slate-200 rounded-md border border-slate-300">
                <div class="p-8 sm:p-10">

                    {{-- TANGGAL --}}
                    <h2 class="text-xl font-semibold text-slate-800">
                        {{ isset($trx->created_at) ? $trx->created_at->translatedFormat('d F Y') : 'Tanggal' }}
                    </h2>

                    {{-- garis tebal --}}
                    <div class="mt-4 h-1 bg-slate-900"></div>

                    {{-- Bar user + nominal kanan --}}
                    <div class="mt-6 flex items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-full bg-slate-900"></div>
                            <div class="text-slate-800 font-medium">
                                {{ $trx->nama_pelanggan ?? 'User' }}
                            </div>
                        </div>

                        @php
                            // kalau transaksi masuk/keluar beda tanda, kamu bisa atur dari field tipe
                            $nominal = $trx->total ?? 0;
                            $isPlus = ($trx->tipe ?? 'masuk') === 'masuk';
                        @endphp

                        <div class="text-slate-900 font-bold">
                            {{ $isPlus ? '+' : '-' }}Rp.{{ number_format($nominal, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- JUDUL DETAIL --}}
                    <div class="mt-10 font-semibold text-slate-900">Detail Transaksi</div>
                    <div class="mt-4 h-1 bg-slate-900"></div>

                    {{-- BOX HITAM "Berisi Detail" --}}
                    <div class="mt-8 bg-slate-950 text-white rounded-sm border border-slate-900">
                        <div class="p-10 text-center text-2xl font-semibold">
                            Berisi Detail
                        </div>

                        {{-- Kalau kamu mau isi detail asli, ganti bagian ini: --}}
                        <div class="px-6 pb-6">
                            <div class="bg-white/5 border border-white/10 rounded-md p-4 text-left text-sm text-white/90">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div><span class="text-white/60">Kode:</span> <span class="font-semibold">{{ $trx->kode ?? $trx->id ?? '-' }}</span></div>
                                    <div><span class="text-white/60">Metode:</span> <span class="font-semibold">{{ $trx->metode_pembayaran ?? '-' }}</span></div>
                                    <div><span class="text-white/60">Status:</span> <span class="font-semibold">{{ $trx->status ?? '-' }}</span></div>
                                    <div><span class="text-white/60">Catatan:</span> <span class="font-semibold">{{ $trx->catatan ?? '-' }}</span></div>
                                </div>

                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead class="text-white/70">
                                            <tr>
                                                <th class="py-2 pr-4 text-left font-semibold">Barang</th>
                                                <th class="py-2 pr-4 text-right font-semibold">Harga</th>
                                                <th class="py-2 pr-4 text-right font-semibold">Qty</th>
                                                <th class="py-2 text-right font-semibold">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-white/10">
                                        @forelse(($items ?? []) as $it)
                                            @php
                                                $nama  = $it->nama_barang ?? $it->barang->nama_barang ?? $it->nama ?? '-';
                                                $harga = $it->harga ?? $it->barang->harga ?? 0;
                                                $qty   = $it->qty ?? $it->jumlah ?? 0;
                                                $sub   = $harga * $qty;
                                            @endphp
                                            <tr class="text-white/90">
                                                <td class="py-2 pr-4">{{ $nama }}</td>
                                                <td class="py-2 pr-4 text-right">Rp{{ number_format($harga,0,',','.') }}</td>
                                                <td class="py-2 pr-4 text-right">{{ $qty }}</td>
                                                <td class="py-2 text-right font-semibold">Rp{{ number_format($sub,0,',','.') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="py-6 text-center text-white/60">Tidak ada item.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- tombol cetak kanan bawah --}}
                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('transaksi.nota', $trx->id) }}"
                           class="inline-flex items-center justify-center
                                  px-6 py-3 bg-slate-300 hover:bg-slate-400
                                  text-slate-900 font-semibold rounded-sm border border-slate-400">
                            Cetak Sebagai<br class="hidden sm:block">Nota
                        </a>
                    </div>

                </div>
            </section>
        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const btnOpen = document.getElementById('btnOpenSidebar');
    const btnClose = document.getElementById('btnCloseSidebar');

    function openSidebar(){ sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
    function closeSidebar(){ sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }

    btnOpen && btnOpen.addEventListener('click', openSidebar);
    btnClose && btnClose.addEventListener('click', closeSidebar);
    overlay && overlay.addEventListener('click', closeSidebar);
</script>
</body>
</html>
