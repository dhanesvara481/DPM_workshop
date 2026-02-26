{{-- resources/views/admin/jadwal_kerja/tambah_jadwal_kerja.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Tambah Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Isi form untuk menambahkan kegiatan / shift.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button type="button"
              class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              data-tip="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </button>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  {{-- BACKGROUND --}}
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.12]"
         style="background-image:
            linear-gradient(to right, rgba(2,6,23,0.06) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,6,23,0.06) 1px, transparent 1px);
            background-size: 56px 56px;"></div>
    <div class="absolute inset-0 opacity-[0.20] mix-blend-screen animate-grid-scan"
         style="background-image:
            repeating-linear-gradient(90deg, transparent 0px, transparent 55px, rgba(255,255,255,0.95) 56px, transparent 57px, transparent 112px),
            repeating-linear-gradient(180deg, transparent 0px, transparent 55px, rgba(255,255,255,0.70) 56px, transparent 57px, transparent 112px);
            background-size: 112px 112px, 112px 112px;"></div>
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[720px] w-[720px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
    <div class="absolute -bottom-72 right-1/4 h-[720px] w-[720px] rounded-full blur-3xl opacity-08
                bg-gradient-to-tr from-blue-950/18 via-indigo-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[980px] mx-auto w-full">

    @php $prefillDate = $selectedDate ?? request('date') ?? null; @endphp

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg sm:text-xl font-semibold tracking-tight text-slate-900">Form Tambah Jadwal</div>
            <div class="text-xs text-slate-500 mt-1">Minimal isi: Nama, Tanggal, Jam Mulai & Jam Selesai.</div>
          </div>
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 text-xs text-slate-600">
              <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Aktif
            </span>
            <span class="inline-flex items-center gap-2 text-xs text-slate-600">
              <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Catatan
            </span>
            <span class="inline-flex items-center gap-2 text-xs text-slate-600">
              <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Tutup
            </span>
          </div>
        </div>
      </div>

      <div class="p-5 sm:p-6">
        <form id="createForm" action="{{ route('simpan_jadwal_kerja') }}" method="POST" class="space-y-5">
          @csrf

          {{-- Auth info untuk JS --}}
          <input type="hidden" id="defaultStatus" value="Aktif">
          <input type="hidden" id="authUserId"    value="{{ $authUser->user_id ?? '' }}">
          <input type="hidden" id="authUserRole"  value="{{ $authUser->role ?? '' }}">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Nama --}}
            <div>
              <label class="block text-sm font-semibold text-slate-800 mb-1">Nama</label>
              <div class="relative">
                <select name="user_id" id="userSelect"
                        class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 pr-10
                               focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300
                               transition appearance-none">
                  <option value="">Pilih user</option>
                  @foreach(($users ?? []) as $u)
                    <option value="{{ $u->user_id }}"
                            data-role="{{ $u->role }}"
                            @selected(old('user_id') == $u->user_id)>
                      {{ $u->username ?? 'User' }}
                    </option>
                  @endforeach
                </select>
                <span id="lockIcon" class="hidden absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                  <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </span>
              </div>
              <p class="text-[11px] mt-1" id="userSelectHint" style="color:#64748b">Pilih admin/staff yang dijadwalkan.</p>
            </div>

            {{-- Tanggal --}}
            <div>
              <label class="block text-sm font-semibold text-slate-800 mb-1">Tanggal Kerja</label>
              <input type="date" name="tanggal_kerja"
                     value="{{ old('tanggal_kerja', $prefillDate) }}"
                     class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
              <p class="text-[11px] text-slate-500 mt-1">Otomatis terisi jika datang dari kalender.</p>
            </div>

            {{-- Jam Mulai — disembunyikan saat status Tutup --}}
            <div id="jamMulaiWrapper">
              <label class="block text-sm font-semibold text-slate-800 mb-1">Jam Mulai</label>
              <input type="time" name="jam_mulai" id="jamMulaiInput"
                     value="{{ old('jam_mulai') }}"
                     class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
            </div>

            {{-- Jam Selesai — disembunyikan saat status Tutup --}}
            <div id="jamSelesaiWrapper">
              <label class="block text-sm font-semibold text-slate-800 mb-1">Jam Selesai</label>
              <input type="time" name="jam_selesai" id="jamSelesaiInput"
                     value="{{ old('jam_selesai') }}"
                     class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                            focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
            </div>

            {{-- Shift --}}
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-slate-800 mb-1">Waktu Shift</label>
              <select name="waktu_shift"
                      class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                             focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                <option value="">Pilih shift</option>
                <option value="Pagi"  @selected(old('waktu_shift') === 'Pagi')>Pagi</option>
                <option value="Siang" @selected(old('waktu_shift') === 'Siang')>Siang</option>
                <option value="Sore"  @selected(old('waktu_shift') === 'Sore')>Sore</option>
                <option value="Malam" @selected(old('waktu_shift') === 'Malam')>Malam</option>
              </select>
              <p class="text-[11px] text-slate-500 mt-1">Pilih sesuai jam kerja yang diinput.</p>
            </div>

            {{-- Status --}}
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-slate-800 mb-1">Status</label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                {{-- AKTIF --}}
                <label class="group cursor-pointer">
                  <input type="radio" name="status" value="Aktif" class="peer sr-only"
                         @checked(old('status', 'Aktif') === 'Aktif')>
                  <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 hover:bg-emerald-100 transition
                              peer-checked:ring-2 peer-checked:ring-emerald-400 peer-checked:border-emerald-400
                              peer-checked:[&_.radio-dot]:bg-emerald-500">
                    <div class="flex items-center gap-3">
                      <span class="radio-dot h-4 w-4 rounded-full border-2 border-emerald-500 bg-transparent transition"></span>
                      <div>
                        <div class="font-semibold text-emerald-900">Aktif</div>
                        <div class="text-[11px] text-emerald-700">Jadwal kerja normal.</div>
                      </div>
                    </div>
                  </div>
                </label>

                {{-- CATATAN --}}
                <label class="group cursor-pointer">
                  <input type="radio" name="status" value="Catatan" class="peer sr-only"
                         @checked(old('status') === 'Catatan')>
                  <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 hover:bg-amber-100 transition
                              peer-checked:ring-2 peer-checked:ring-amber-400 peer-checked:border-amber-400
                              peer-checked:[&_.radio-dot]:bg-amber-500">
                    <div class="flex items-center gap-3">
                      <span class="radio-dot h-4 w-4 rounded-full border-2 border-amber-500 bg-transparent transition"></span>
                      <div>
                        <div class="font-semibold text-amber-900">Catatan</div>
                        <div class="text-[11px] text-amber-700">Info / reminder penting.</div>
                      </div>
                    </div>
                  </div>
                </label>

                {{-- TUTUP --}}
                <label class="group cursor-pointer">
                  <input type="radio" name="status" value="Tutup" class="peer sr-only"
                         @checked(old('status') === 'Tutup')>
                  <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 hover:bg-rose-100 transition
                              peer-checked:ring-2 peer-checked:ring-rose-400 peer-checked:border-rose-400
                              peer-checked:[&_.radio-dot]:bg-rose-500">
                    <div class="flex items-center gap-3">
                      <span class="radio-dot h-4 w-4 rounded-full border-2 border-rose-500 bg-transparent transition"></span>
                      <div>
                        <div class="font-semibold text-rose-900">Tutup</div>
                        <div class="text-[11px] text-rose-700">Libur / tidak operasional.</div>
                      </div>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            {{-- Deskripsi --}}
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-slate-800 mb-1">Deskripsi</label>
              <textarea name="deskripsi" rows="5"
                        placeholder="Contoh: Pekerjaan service rutin, booking pelanggan, dll..."
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3
                               focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">{{ old('deskripsi') }}</textarea>
            </div>

          </div>

          <div class="flex flex-col sm:flex-row gap-2 sm:justify-end pt-2">
            <a id="btnBatal" href="{{ route('kelola_jadwal_kerja') }}"
               class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold inline-flex items-center justify-center">
              Batal
            </a>
            <button type="submit"
                    class="h-11 px-5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold
                           shadow-[0_12px_24px_rgba(2,6,23,0.14)]">
              Simpan
            </button>
          </div>
        </form>
      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        Tips: dari kalender, klik tanggal → Tambah Jadwal (otomatis bawa query <span class="font-semibold">?date=YYYY-MM-DD</span>).
      </div>
    </div>
  </div>
</section>

@endsection

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) { .animate-grid-scan { animation: none !important; } }
  @keyframes gridScan {
    0%   { background-position: 0 0, 0 0; opacity: 0.10; }
    40%  { opacity: 0.22; }
    60%  { opacity: 0.18; }
    100% { background-position: 220px 220px, -260px 260px; opacity: 0.10; }
  }
  .animate-grid-scan { animation: gridScan 8.5s ease-in-out infinite; }

  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip);
    position: absolute; right: 0; top: calc(100% + 10px);
    background: rgba(15,23,42,.92); color: rgba(255,255,255,.92);
    font-size: 11px; padding: 6px 10px; border-radius: 10px;
    white-space: nowrap; opacity: 0; transform: translateY(-4px);
    pointer-events: none; transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translateY(0); }

  /* ✅ Visual dropdown terkunci */
  select.is-locked {
    background-color: #f1f5f9 !important;
    border-color:     #94a3b8 !important;
    color:            #475569 !important;
    cursor: not-allowed !important;
    pointer-events: none;
    opacity: 1 !important;
  }

  /* ✅ Animasi smooth hide/show field jam */
  .field-hidden {
    display: none !important;
  }
</style>
@endpush

@push('scripts')
<script>
  // ─── Auth admin yang login ─────────────────────────────────────────────────
  const authUserId    = document.getElementById('authUserId')?.value  ?? '';
  const authUserRole  = document.getElementById('authUserRole')?.value ?? '';
  const defaultStatus = document.getElementById('defaultStatus')?.value ?? 'Aktif';

  // ─── Elemen ────────────────────────────────────────────────────────────────
  const userSelect       = document.getElementById('userSelect');
  const userSelectHint   = document.getElementById('userSelectHint');
  const lockIcon         = document.getElementById('lockIcon');
  const jamMulaiWrapper  = document.getElementById('jamMulaiWrapper');
  const jamSelesaiWrapper= document.getElementById('jamSelesaiWrapper');
  const jamMulaiInput    = document.getElementById('jamMulaiInput');
  const jamSelesaiInput  = document.getElementById('jamSelesaiInput');

  // ─── 1. Kunci / buka dropdown Nama ────────────────────────────────────────
  function filterUserDropdown(statusValue) {
    if (!userSelect) return;
    const isRestricted = ['Catatan', 'Tutup'].includes(statusValue);

    if (isRestricted) {
      userSelect.value    = authUserId;
      userSelect.disabled = true;
      userSelect.classList.add('is-locked');
      lockIcon?.classList.remove('hidden');

      // Hidden input agar user_id tetap terkirim (disabled tidak ikut submit)
      let h = document.getElementById('hiddenUserId');
      if (!h) {
        h = document.createElement('input');
        h.type = 'hidden'; h.name = 'user_id'; h.id = 'hiddenUserId';
        userSelect.parentNode.appendChild(h);
      }
      h.value = authUserId;

      if (userSelectHint) {
        userSelectHint.textContent = '';
      }
    } else {
      userSelect.disabled = false;
      userSelect.classList.remove('is-locked');
      lockIcon?.classList.add('hidden');
      document.getElementById('hiddenUserId')?.remove();

      if (userSelectHint) {
        userSelectHint.textContent = 'Pilih admin/staff yang dijadwalkan.';
        userSelectHint.style.color = '#64748b';
      }
    }
  }

  // ─── 2. Sembunyikan / tampilkan field Jam saat status Tutup ───────────────
  function filterTimeFields(statusValue) {
    const isTutup = statusValue === 'Tutup';

    if (jamMulaiWrapper) jamMulaiWrapper.classList.toggle('field-hidden', isTutup);
    if (jamSelesaiWrapper) jamSelesaiWrapper.classList.toggle('field-hidden', isTutup);

    if (isTutup) {
      // Kosongkan value agar tidak ikut terkirim / tidak gagal validasi
      if (jamMulaiInput)  jamMulaiInput.value  = '';
      if (jamSelesaiInput) jamSelesaiInput.value = '';
    }
  }

  // ─── Bind radio status ────────────────────────────────────────────────────
  document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.checked) {
        filterUserDropdown(radio.value);
        filterTimeFields(radio.value);
      }
    });
  });

  // Jalankan saat halaman load (termasuk old() dari validasi gagal)
  const checkedStatus = document.querySelector('input[name="status"]:checked');
  if (checkedStatus) {
    filterUserDropdown(checkedStatus.value);
    filterTimeFields(checkedStatus.value);
  }

  // ─── Confirm modal ─────────────────────────────────────────────────────────
  function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg: "bg-slate-50", noteBr: "border-slate-200", noteTx: "text-slate-600" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700",  noteBg: "bg-rose-50",  noteBr: "border-rose-200",  noteTx: "text-rose-700"  },
    };
    const t = toneMap[tone] || toneMap.neutral;

    const wrap = document.createElement('div');
    wrap.className = "fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3";
    wrap.innerHTML = `
      <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-[0_30px_80px_rgba(2,6,23,0.30)] overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
          <div>
            <div class="text-lg font-semibold text-slate-900">${title}</div>
            <div class="text-sm text-slate-600 mt-1">${message}</div>
          </div>
          <button type="button" class="btn-x h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 grid place-items-center">
            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <div class="p-5">
          <div class="rounded-xl border ${t.noteBr} ${t.noteBg} p-4 text-xs ${t.noteTx}">
            ${note || 'Pastikan data yang kamu isi sudah benar.'}
          </div>
          <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t.btn} text-white text-sm font-semibold">${confirmText}</button>
          </div>
        </div>
      </div>
    `;
    function close() { wrap.remove(); }
    wrap.addEventListener('click', (e) => { if (e.target === wrap) close(); });
    wrap.querySelector('.btn-x')?.addEventListener('click', close);
    wrap.querySelector('.btn-cancel')?.addEventListener('click', close);
    wrap.querySelector('.btn-ok')?.addEventListener('click', () => { close(); onConfirm?.(); });
    document.body.appendChild(wrap);
  }

  // ─── Submit ────────────────────────────────────────────────────────────────
  const createForm = document.getElementById('createForm');
  createForm?.addEventListener('submit', (e) => {
    if (createForm.dataset.confirmed === "1") return;
    e.preventDefault();
    showConfirmModal({
      title: "Simpan jadwal baru?",
      message: "Jadwal akan ditambahkan ke sistem.",
      confirmText: "Ya, Simpan",
      cancelText: "Batal",
      note: "Cek lagi Nama, Tanggal, dan jam-nya. Kalau sudah benar, lanjut simpan.",
      onConfirm: () => { createForm.dataset.confirmed = "1"; createForm.submit(); }
    });
  });

  // ─── Tombol Batal ─────────────────────────────────────────────────────────
  const btnBatal    = document.getElementById('btnBatal');
  const prefillDate = "{{ $prefillDate ?? '' }}";

  btnBatal?.addEventListener('click', (e) => {
    e.preventDefault();
    const go = btnBatal.getAttribute('href');

    const hasAnyValue = Array.from(createForm.querySelectorAll('input, select, textarea'))
      .some(el => {
        if (!el.name || el.type === 'hidden' || el.type === 'submit' || el.type === 'button') return false;
        if (el.type === 'radio') return el.checked && el.value !== defaultStatus;
        if (el.name === 'tanggal_kerja' && prefillDate && el.value === prefillDate) return false;
        return String(el.value || '').trim().length > 0;
      });

    if (!hasAnyValue) { window.location.href = go; return; }

    showConfirmModal({
      title: "Batalkan pembuatan jadwal?",
      message: "Kalau kamu keluar sekarang, data yang sudah diisi akan hilang.",
      confirmText: "Ya, Keluar",
      cancelText: "Tetap di sini",
      note: "Kalau mau lanjut isi, klik \"Tetap di sini\".",
      onConfirm: () => window.location.href = go
    });
  });
</script>
@endpush