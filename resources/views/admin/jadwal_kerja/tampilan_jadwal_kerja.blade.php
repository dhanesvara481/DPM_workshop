<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tampilan Jadwal Kerja - DPM Workshop</title>
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
                    <p class="text-[11px] text-white/55">Jadwal (View Only)</p>
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

            {{-- Menu (VIEW ONLY) --}}
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
                    <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">JADWAL</p>

                    {{-- ACTIVE: Tampilan Jadwal (VIEW ONLY) --}}
                    <a href="/tampilan_jadwal_kerja"
                       data-nav data-active="true"
                       class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                              bg-white/12 text-white border border-white/10
                              hover:bg-white/10 hover:text-white transition relative overflow-hidden">
                        <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
                            <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Tampilan Jadwal
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t border-white/10">
                    <a href="/logout"
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

        {{-- BACKGROUND --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
            <div class="absolute inset-0 opacity-[0.12]"
                 style="background-image:
                    linear-gradient(to right, rgba(2,6,23,0.06) 1a, transparent 1px),
                    linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
                    background-size: 56px 56px;">
            </div>
            <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                        bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
        </div>

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
                        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Tampilan Jadwal Kerja</h1>
                        <p class="text-xs text-slate-500">Halaman khusus lihat jadwal. Klik tanggal untuk detail.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/tampilan_dashboard"
                       class="inline-flex items-center justify-center h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <section class="relative p-4 sm:p-6">
            <div class="max-w-[1280px] mx-auto w-full">

                @php
                    // NOTE: nanti ganti ini dari DB (hasil kelola)
                    $events = $events ?? [
                        now()->format('Y-m-d') => [
                            ['id'=> 101, 'title'=>'Shift Pagi - Asep', 'status'=>'aktif', 'time'=>'08:00 - 16:00', 'desc'=>'Servis rutin / tune up'],
                            ['id'=> 102, 'title'=>'Catatan: Sparepart datang', 'status'=>'catatan', 'time'=>'10:30', 'desc'=>'Cek gudang + follow up supplier'],
                        ],
                        now()->addDay()->format('Y-m-d') => [
                            ['id'=> 103, 'title'=>'Tutup (Libur)', 'status'=>'tutup', 'time'=>'-', 'desc'=>'Hari libur operasional'],
                        ],
                    ];
                    $slots = $slots ?? [
                        now()->format('Y-m-d') => ['left'=> 3, 'status'=>'open'],
                        now()->addDay()->format('Y-m-d') => ['left'=> 0, 'status'=>'closed'],
                    ];
                @endphp

                <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                            shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

                    {{-- header kalender --}}
                    <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="min-w-0">
                                <div id="monthTitle" class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-900">—</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Jadwal tampil sesuai data yang sudah diinput admin (read-only).
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                {{-- NAV --}}
                                <div class="flex items-center gap-2">
                                    <button id="btnToday" type="button"
                                            class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                                        Today
                                    </button>

                                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white">
                                        <button id="btnPrev" type="button"
                                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition"
                                                aria-label="Prev">
                                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button id="btnNext" type="button"
                                                class="h-10 w-10 grid place-items-center hover:bg-slate-50 transition border-l border-slate-200"
                                                aria-label="Next">
                                            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="hidden sm:block w-px h-10 bg-slate-200 mx-1"></div>

                                {{-- LEGEND --}}
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-600">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Tutup
                                    </span>
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Catatan
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- kalender --}}
                    <div class="p-3 sm:p-4">
                        <div class="overflow-x-auto">
                            <div class="min-w-[980px]">
                                {{-- header hari --}}
                                <div class="grid grid-cols-7 gap-2 px-1 pb-2 text-[12px] font-semibold text-slate-600">
                                    <div class="px-2">Minggu</div>
                                    <div class="px-2">Senin</div>
                                    <div class="px-2">Selasa</div>
                                    <div class="px-2">Rabu</div>
                                    <div class="px-2">Kamis</div>
                                    <div class="px-2">Jumat</div>
                                    <div class="px-2">Sabtu</div>
                                </div>

                                {{-- grid days --}}
                                <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
                        © DPM Workshop 2025
                    </div>
                </div>

                {{-- MODAL DETAIL (VIEW ONLY) --}}
                <div id="detailModal" class="fixed inset-0 z-[60] hidden">
                    <div id="detailOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

                    <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
                        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">Detail Jadwal</div>
                                    <div id="modalDate" class="text-xs text-slate-500 mt-0.5">—</div>
                                </div>
                                <button id="btnCloseModal"
                                        type="button"
                                        class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                                        aria-label="Tutup">
                                    <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5">
                                <div id="modalSlot" class="mb-4"></div>
                                <div id="modalEvents" class="space-y-2"></div>

                                <div id="modalEmpty" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                    Belum ada jadwal di tanggal ini.
                                </div>

                                <div class="mt-5 flex justify-end">
                                    <button id="modalTutup"
                                            type="button"
                                            class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                                   border border-slate-200 bg-white hover:bg-slate-50 transition">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <style>
            /* sidebar active indicator */
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

            /* calendar day card */
            .day-card{
                border: 1px solid rgba(15,23,42,0.10);
                background: rgba(255,255,255,0.92);
                border-radius: 18px;
                min-height: 132px;
                overflow: hidden;
                transition: .15s ease;
            }
            .day-card:hover{
                border-color: rgba(2,6,23,0.18);
                box-shadow: 0 14px 34px rgba(2,6,23,0.10);
                transform: translateY(-1px);
            }
            .day-muted{ opacity: .45; background: rgba(248,250,252,0.85); }

            .day-top{
                display:flex; align-items:center; justify-content:space-between;
                padding: 10px 12px 6px 12px;
            }
            .day-top .right-slot { min-width: 86px; display:flex; justify-content:flex-end; }

            .day-num{
                width: 32px; height: 32px;
                display:grid; place-items:center;
                border-radius: 999px;
                font-weight: 800; font-size: 13px;
                color: rgba(15,23,42,0.92);
            }
            .day-num.today{ background: rgba(2,6,23,0.92); color:#fff; }

            .pill{
                display:inline-flex; align-items:center;
                font-size: 11px;
                padding: 6px 10px;
                border-radius: 12px;
                border: 1px solid rgba(15,23,42,0.10);
                background: rgba(255,255,255,0.75);
                white-space: nowrap; overflow:hidden; text-overflow: ellipsis;
                max-width: 100%;
            }
            .pill.aktif   { background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.25); color: rgba(6,95,70,0.95); }
            .pill.catatan { background: rgba(245,158,11,0.12); border-color: rgba(245,158,11,0.25); color: rgba(120,53,15,0.95); }
            .pill.tutup   { background: rgba(244,63,94,0.12); border-color: rgba(244,63,94,0.25); color: rgba(136,19,55,0.95); }

            .day-body{ padding: 8px 12px 12px 12px; display:flex; flex-direction:column; gap:6px; }
            .has-data{ outline: 2px solid rgba(2,6,23,0.10); }
        </style>

        <script>
            // sidebar active indicator
            document.querySelectorAll('[data-nav]').forEach(a => {
                if (a.dataset.active === "true") a.classList.add('is-active');
            });

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

            // ===== Calendar Rendering (VIEW ONLY) =====
            const monthTitle = document.getElementById('monthTitle');
            const grid = document.getElementById('calendarGrid');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const btnToday = document.getElementById('btnToday');

            // modal elements
            const detailModal = document.getElementById('detailModal');
            const detailOverlay = document.getElementById('detailOverlay');
            const btnCloseModal = document.getElementById('btnCloseModal');
            const modalTutup = document.getElementById('modalTutup');

            const modalDate = document.getElementById('modalDate');
            const modalSlot = document.getElementById('modalSlot');
            const modalEvents = document.getElementById('modalEvents');
            const modalEmpty = document.getElementById('modalEmpty');

            const EVENTS = @json($events);
            const SLOTS  = @json($slots);

            const pad2 = (n) => String(n).padStart(2, '0');
            const ymd = (d) => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
            const sameDay = (a,b) => a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();

            const fmtMonth = (d) => d.toLocaleDateString('id-ID', { month:'long', year:'numeric' });
            const fmtLong = (iso) => {
                try {
                    const [y,m,dd] = iso.split('-').map(Number);
                    const obj = new Date(y, m-1, dd);
                    return obj.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
                } catch(e) { return iso; }
            };

            let current = new Date();
            current.setDate(1);

            function showModal(dateStr){
                const ev = EVENTS?.[dateStr] || [];
                const slot = SLOTS?.[dateStr];

                modalDate.textContent = fmtLong(dateStr);

                // slot UI
                modalSlot.innerHTML = '';
                if (slot) {
                    const isClosed = slot.status === 'closed';
                    modalSlot.innerHTML = `
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-sm font-semibold text-slate-900">Ketersediaan</div>
                                <span class="pill ${isClosed ? 'tutup' : 'aktif'}">
                                    ${isClosed ? 'TUTUP' : 'AKTIF'}
                                </span>
                            </div>
                            <div class="text-sm text-slate-600 mt-2">
                                ${isClosed ? 'Hari ini tidak tersedia.' : `Sisa slot: <span class="font-semibold">${slot.left ?? 0}</span>`}
                            </div>
                        </div>
                    `;
                }

                // events UI
                modalEvents.innerHTML = '';
                if (ev.length > 0) {
                    ev.forEach((e) => {
                        const status = (e.status || 'aktif');
                        const time = e.time ? `<div class="text-xs text-slate-500 mt-0.5">${e.time}</div>` : '';
                        const desc = e.desc ? `<div class="text-xs text-slate-600 mt-1">${e.desc}</div>` : '';
                        modalEvents.innerHTML += `
                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-slate-900 truncate">${e.title || 'Jadwal'}</div>
                                        ${time}
                                    </div>
                                    <span class="pill ${status}">${status.toUpperCase()}</span>
                                </div>
                                ${desc}
                            </div>
                        `;
                    });
                    modalEmpty.classList.add('hidden');
                } else {
                    modalEmpty.classList.remove('hidden');
                }

                detailModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(){
                detailModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            detailOverlay?.addEventListener('click', hideModal);
            btnCloseModal?.addEventListener('click', hideModal);
            modalTutup?.addEventListener('click', hideModal);

            function render() {
                grid.innerHTML = '';
                monthTitle.textContent = fmtMonth(current);

                const today = new Date();
                const year = current.getFullYear();
                const month = current.getMonth();

                const first = new Date(year, month, 1);
                const startDay = first.getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // empty cells
                for (let i = 0; i < startDay; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'day-card day-muted';
                    empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
                    grid.appendChild(empty);
                }

                // days
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateObj = new Date(year, month, day);
                    const key = ymd(dateObj);
                    const isToday = sameDay(dateObj, today);

                    const ev = EVENTS?.[key] || [];
                    const slot = SLOTS?.[key];
                    const hasData = (ev.length > 0) || !!slot;

                    const card = document.createElement('button');
                    card.type = 'button';
                    card.className = `day-card text-left ${hasData ? 'has-data' : ''}`;
                    card.dataset.date = key;

                    const top = document.createElement('div');
                    top.className = 'day-top';

                    const num = document.createElement('div');
                    num.className = `day-num ${isToday ? 'today' : ''}`;
                    num.textContent = String(day);

                    const right = document.createElement('div');
                    right.className = 'right-slot';

                    if (slot) {
                        const statusPill = document.createElement('div');
                        const isClosed = slot.status === 'closed';
                        statusPill.className = `pill ${isClosed ? 'tutup' : 'aktif'}`;
                        statusPill.textContent = isClosed ? 'N/A' : `Slot · ${slot.left ?? 0}`;
                        right.appendChild(statusPill);
                    }

                    top.appendChild(num);
                    top.appendChild(right);

                    const body = document.createElement('div');
                    body.className = 'day-body';

                    const take = ev.slice(0, 3);
                    take.forEach(e => {
                        const pill = document.createElement('div');
                        const status = (e.status || 'aktif');
                        pill.className = `pill ${status}`;
                        pill.title = e.title || '';
                        pill.textContent = e.title || 'Jadwal';
                        body.appendChild(pill);
                    });

                    if (take.length === 0 && !slot) {
                        const hint = document.createElement('div');
                        hint.className = 'text-[11px] text-slate-500/80';
                        hint.textContent = '—';
                        body.appendChild(hint);
                    }

                    card.appendChild(top);
                    card.appendChild(body);

                    card.addEventListener('click', () => showModal(key));
                    grid.appendChild(card);
                }

                // trailing padding
                const totalCells = startDay + daysInMonth;
                const remaining = (7 - (totalCells % 7)) % 7;
                for (let i = 0; i < remaining; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'day-card day-muted';
                    empty.setAttribute('aria-hidden', 'true');
                    empty.innerHTML = `<div class="day-top"><div class="day-num"></div><div class="right-slot"></div></div>`;
                    grid.appendChild(empty);
                }
            }

            btnPrev?.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()-1, 1);
                render();
            });
            btnNext?.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()+1, 1);
                render();
            });
            btnToday?.addEventListener('click', () => {
                const t = new Date();
                current = new Date(t.getFullYear(), t.getMonth(), 1);
                render();
                showModal(ymd(t));
            });

            // sidebar resize handling
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

            render();
        </script>

    </main>
</div>
</body>
</html>
