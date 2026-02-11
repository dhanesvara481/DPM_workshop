<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Staf</title>
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 h-screen
                  w-[280px] md:w-[280px]
                  -translate-x-full md:translate-x-0
                  bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
                  border-r border-white/5
                  transition-[transform,width] duration-300 ease-out
                  overflow-y-auto">

        <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain" alt="Logo">
                </div>
                <div class="leading-tight">
                    <p class="font-semibold tracking-tight">DPM Workshop</p>
                </div>
            </div>

            <button id="btnCloseSidebar"
                    type="button"
                    class="md:hidden h-10 w-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition grid place-items-center"
                    aria-label="Tutup menu">
                <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-5 py-5">
            {{-- Profile --}}
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
                <div class="h-10 w-10 rounded-full bg-white/10 border border-white/15"></div>
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">{{ $userName ?? 'User' }}</p>
                    <p class="text-[11px] text-white/60">{{ $role ?? 'Admin' }}</p>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="mt-5 space-y-1">
                <a href="/tampilan_dashboard"
                   data-nav
                   class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                    <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                        <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
                        </svg>
                    </span>
                    Dashboard
                </a>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">BARANG</p>
                    <a href="/tampilan_barang" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
                            </svg>
                        </span>
                        Kelola Barang
                    </a>
                    <a href="/barang_keluar" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
                            </svg>
                        </span>
                        Barang Keluar
                    </a>
                    <a href="/barang_masuk" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
                            </svg>
                        </span>
                        Barang Masuk
                    </a>
                </div>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>
                    <a href="/riwayat_perubahan_stok" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Riwayat Perubahan Stok
                    </a>
                    <a href="/riwayat_transaksi" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                        </span>
                        Riwayat Transaksi
                    </a>
                    <a href="/laporan_penjualan" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17v-6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17v-3"/>
                            </svg>
                        </span>
                        Laporan Penjualan
                    </a>
                </div>

                <div class="mt-3">
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

                    <a href="/kelola_jadwal_kerja"
                       data-nav
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Kelola Jadwal Kerja
                    </a>

                    {{-- ACTIVE: Manajemen Staf --}}
                    <a href="/tampilan_manajemen_staf"
                       data-nav data-active="true"
                       class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                              bg-white/12 text-white border border-white/10
                              hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20c0-2.2-2.7-4-5-4s-5 1.8-5 4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 20c0-1.7-1.4-3.1-3.3-3.7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7a2.5 2.5 0 01-1.5 2.3"/>
                            </svg>
                        </span>
                        Manajemen Staf
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t border-white/10">
                    <a href="#"
                       class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21V3a2 2 0 00-2-2h-6"/>
                            </svg>
                        </span>
                        Logout
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

    {{-- ================= MAIN ================= --}}
    <main id="main" class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

        {{-- TOPBAR --}}
        <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
            <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="btnSidebar" type="button"
                            class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                            aria-label="Buka menu">
                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Manajemen Staf</h1>
                        <p class="text-xs text-slate-500">Kelola data staf: tambah, ubah, hapus, dan lihat status.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/tambah_staf"
                       class="h-10 px-4 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold inline-flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        Tambah Staf
                    </a>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <section class="relative p-4 sm:p-6">
            <div class="max-w-[1280px] mx-auto w-full">

                @php
                    // DUMMY DATA (nanti ganti dari DB)
                    $stafs = $stafs ?? [
                        ['id'=>1, 'nama'=>'Asep', 'role'=>'Staf',  'hp'=>'0812-1111-2222', 'status'=>'Aktif', 'gabung'=>'2025-01-10', 'note'=>'Mekanik'],
                        ['id'=>2, 'nama'=>'Rina', 'role'=>'Admin', 'hp'=>'0812-3333-4444', 'status'=>'Aktif', 'gabung'=>'2025-03-22', 'note'=>'Owner • Bisa servis'],
                        ['id'=>3, 'nama'=>'Budi', 'role'=>'Staf',  'hp'=>'0812-5555-6666', 'status'=>'Nonaktif', 'gabung'=>'2024-11-02', 'note'=>'Kasir'],
                    ];
                @endphp

                <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-slate-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-lg font-semibold text-slate-900">Daftar Staf</div>
                                <div class="text-xs text-slate-500 mt-1">Cari nama / filter role / filter status.</div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="relative">
                                    <input id="q"
                                           type="text"
                                           placeholder="Cari nama / no HP..."
                                           class="h-10 w-full sm:w-64 rounded-xl border border-slate-200 bg-white px-4 pr-10 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                                    <svg class="h-5 w-5 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>
                                </div>

                               <select id="filterRole"
                                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                                    <option value="">Semua Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Staf">Staf</option>
                                </select>


                                <select id="filterStatus"
                                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="text-left font-semibold px-5 py-3">Nama</th>
                                    <th class="text-left font-semibold px-5 py-3">Role</th>
                                    <th class="text-left font-semibold px-5 py-3">No HP</th>
                                    <th class="text-left font-semibold px-5 py-3">Status</th>
                                    <th class="text-left font-semibold px-5 py-3">Tgl Gabung</th>
                                    <th class="text-right font-semibold px-5 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody" class="divide-y divide-slate-100">
                                @foreach($stafs as $s)
                                    <tr class="bg-white">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-slate-900 staff-nama">{{ $s['nama'] }}</div>
                                            <div class="text-xs text-slate-500">ID: {{ $s['id'] }}</div>
                                        </td>
                                        <td class="px-5 py-4 staff-role">{{ $s['role'] }}</td>
                                        <td class="px-5 py-4 staff-hp">{{ $s['hp'] }}</td>
                                        <td class="px-5 py-4 staff-status">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                                {{ $s['status']==='Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                {{ $s['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-slate-700">{{ $s['gabung'] }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- DETAIL (boleh semua) --}}
                                                <a href="/detail_staf?id={{ $s['id'] }}"
                                                    class="inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap">
                                                    Detail
                                                </a>

                                                {{-- kalau yang dilihat adalah STAF, baru boleh UBah & Nonaktifkan --}}
                                                @if($s['role'] === 'Staf')
                                                    <a href="/ubah_staf?id={{ $s['id'] }}"
                                                        class="inline-flex h-9 items-center justify-center px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold whitespace-nowrap">
                                                        Ubah
                                                    </a>

                                                    <a href="/nonaktifkan_staf?id={{ $s['id'] }}"
                                                        class="inline-flex h-9 items-center justify-center px-3 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition text-xs font-semibold whitespace-nowrap">
                                                        Nonaktifkan
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="emptyState" class="hidden p-8 text-center text-slate-600">
                        <div class="text-sm font-semibold">Tidak ada hasil</div>
                        <div class="text-xs text-slate-500 mt-1">Coba ubah kata kunci / filter.</div>
                    </div>

                    <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>
            </div>
        </section>

        <script>
            // mobile sidebar
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const btnSidebar = document.getElementById('btnSidebar');
            const btnCloseSidebar = document.getElementById('btnCloseSidebar');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            btnSidebar?.addEventListener('click', openSidebar);
            btnCloseSidebar?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            const syncOnResize = () => {
                if (window.innerWidth >= 768) {
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            };
            window.addEventListener('resize', syncOnResize);
            syncOnResize();

            // active indicator
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
            });

            // simple filter (client-side demo)
            const q = document.getElementById('q');
            const filterRole = document.getElementById('filterRole');
            const filterStatus = document.getElementById('filterStatus');
            const tbody = document.getElementById('tbody');
            const emptyState = document.getElementById('emptyState');

            function applyFilter(){
                const keyword = (q.value || '').toLowerCase().trim();
                const role = filterRole.value;
                const status = filterStatus.value;

                const rows = Array.from(tbody.querySelectorAll('tr'));
                let shown = 0;

                rows.forEach(tr => {
                    const nama = (tr.querySelector('.staff-nama')?.textContent || '').toLowerCase();
                    const hp = (tr.querySelector('.staff-hp')?.textContent || '').toLowerCase();
                    const r = (tr.querySelector('.staff-role')?.textContent || '');
                    const st = (tr.querySelector('.staff-status')?.textContent || '');

                    const matchKeyword = !keyword || nama.includes(keyword) || hp.includes(keyword);
                    const matchRole = !role || r === role;
                    const matchStatus = !status || st.includes(status);

                    const ok = matchKeyword && matchRole && matchStatus;
                    tr.classList.toggle('hidden', !ok);
                    if (ok) shown++;
                });

                emptyState.classList.toggle('hidden', shown !== 0);
            }

            q?.addEventListener('input', applyFilter);
            filterRole?.addEventListener('change', applyFilter);
            filterStatus?.addEventListener('change', applyFilter);
        </script>

        <style>
            .nav-item{ position: relative; overflow: hidden; }
            .nav-item::before{
                content:"";
                position:absolute;
                left:0; top:10px; bottom:10px;
                width:3px;
                background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.75), rgba(255,255,255,0));
                opacity:0;
                transform: translateX(-6px);
                transition: .25s ease;
                border-radius: 999px;
            }
            .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }
            #sidebar { -webkit-overflow-scrolling: touch; }
        </style>

    </main>
</div>
</body>
</html>
