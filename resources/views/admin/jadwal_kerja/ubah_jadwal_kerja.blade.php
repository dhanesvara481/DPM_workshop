{{-- resources/views/admin/jadwal_kerja/ubah_jadwal_kerja.blade.php --}}
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Ubah Jadwal Kerja</h1>
        <p class="text-xs text-slate-500">Pilih jadwal yang ada, lalu edit datanya.</p>
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

    @php
      $date       = $date ?? request('date') ?? now()->format('Y-m-d');
      $selectedId = request('jadwal_id') ?? old('jadwal_id');

      $jadwals = collect($jadwalKerjas ?? [])->map(fn($j) => [
        'id'          => $j->jadwal_id,
        'tanggal'     => $j->tanggal_kerja->format('Y-m-d'),
        'user_id'     => $j->user_id,
        'title'       => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
        'status'      => strtolower($j->status),
        'jam_mulai'   => substr($j->jam_mulai ?? '', 0, 5),
        'jam_selesai' => substr($j->jam_selesai ?? '', 0, 5),
        'waktu_shift' => $j->waktu_shift,
        'deskripsi'   => $j->deskripsi,
      ])->toArray();

      if (!$selectedId && count($jadwals) > 0) $selectedId = $jadwals[0]['id'];

      $selected = null;
      foreach ($jadwals as $it) {
        if ((string)($it['id'] ?? '') === (string)$selectedId) { $selected = $it; break; }
      }
      if (!$selected && count($jadwals) > 0) $selected = $jadwals[0];

      $prefillDate    = $selected['tanggal']     ?? $date;
      $prefillUser    = $selected['user_id']     ?? old('user_id');
      $prefillMulai   = $selected['jam_mulai']   ?? old('jam_mulai');
      $prefillSelesai = $selected['jam_selesai'] ?? old('jam_selesai');
      $prefillShift   = $selected['waktu_shift'] ?? old('waktu_shift');
      $prefillStatus  = $selected['status']      ?? old('status', 'aktif');
      $prefillDesc    = $selected['deskripsi']   ?? old('deskripsi');
    @endphp

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg sm:text-xl font-semibold tracking-tight text-slate-900">Form Ubah Jadwal</div>
            <div class="text-xs text-slate-500 mt-1">Step 1: pilih jadwal yang mau diubah. Step 2: edit form lalu simpan.</div>
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

      <div class="p-5 sm:p-6 space-y-5">

        {{-- STEP 1: PILIH JADWAL --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div class="min-w-0">
              <div class="text-sm font-semibold text-slate-900">Pilih Jadwal yang Mau Diubah</div>
              <div class="text-xs text-slate-500 mt-1">
                Tanggal: <span class="font-semibold">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
              </div>
            </div>

            <form id="pickForm" method="GET" action="{{ route('ubah_jadwal_kerja') }}" class="w-full sm:w-[420px]">
              <input type="hidden" name="date" value="{{ $date }}">
              <label class="block text-[11px] font-semibold text-slate-600 mb-1">Jadwal di tanggal ini</label>
              <div class="flex gap-2">
                <select id="jadwalSelect" name="jadwal_id"
                        class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                               focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                  @if(count($jadwals) === 0)
                    <option value="">(Belum ada jadwal)</option>
                  @else
                    @foreach($jadwals as $j)
                      @php
                        $st       = strtolower($j['status'] ?? 'aktif');
                        $badge    = strtoupper($st);
                        $title    = $j['title'] ?? 'Jadwal';
                        $range    = ($st !== 'tutup')
                          ? trim(($j['jam_mulai'] ?? '') . (($j['jam_selesai'] ?? '') ? ' - ' . $j['jam_selesai'] : ''))
                          : '';
                        $rangeTxt = $range ? " • {$range}" : '';
                      @endphp
                      <option value="{{ $j['id'] }}"
                              @selected((string)$selectedId === (string)$j['id'])
                              data-id="{{ $j['id'] }}"
                              data-title="{{ $title }}"
                              data-status="{{ $st }}"
                              data-tanggal="{{ $j['tanggal'] ?? $date }}"
                              data-user_id="{{ $j['user_id'] ?? '' }}"
                              data-jam_mulai="{{ $st !== 'tutup' ? ($j['jam_mulai'] ?? '') : '' }}"
                              data-jam_selesai="{{ $st !== 'tutup' ? ($j['jam_selesai'] ?? '') : '' }}"
                              data-waktu_shift="{{ $st !== 'tutup' ? ($j['waktu_shift'] ?? '') : '' }}"
                              data-deskripsi="{{ $st !== 'tutup' ? ($j['deskripsi'] ?? '') : '' }}">
                        [{{ $badge }}] {{ $title }}{{ $rangeTxt }}
                      </option>
                    @endforeach
                  @endif
                </select>
                <button type="submit"
                        class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                  Pilih
                </button>
              </div>
              <div class="text-[11px] text-slate-500 mt-2">Tip: ganti dropdown → auto prefill form + preview</div>
            </form>
          </div>

          @if(count($jadwals) === 0)
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
              Belum ada jadwal di tanggal ini, jadi tidak ada yang bisa diubah.
            </div>
          @else
            @php
              $st      = strtolower($selected['status'] ?? 'aktif');
              $stLabel = strtoupper($st);
              $stClass = $st === 'tutup'
                ? 'bg-rose-50 border-rose-200 text-rose-700'
                : ($st === 'catatan'
                  ? 'bg-amber-50 border-amber-200 text-amber-700'
                  : 'bg-emerald-50 border-emerald-200 text-emerald-700');
            @endphp
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="text-[11px] text-slate-500">ID</div>
                <div id="pvId" class="font-semibold text-slate-900">#{{ $selected['id'] ?? '-' }}</div>
              </div>
              <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="text-[11px] text-slate-500">Judul</div>
                <div id="pvTitle" class="font-semibold text-slate-900 truncate">{{ $selected['title'] ?? 'Jadwal' }}</div>
              </div>
              <div id="pvStatusCard" class="rounded-xl border {{ $stClass }} p-3">
                <div class="text-[11px] opacity-70">Status</div>
                <div id="pvStatus" class="font-extrabold">{{ $stLabel }}</div>
              </div>
            </div>
          @endif
        </div>

        {{-- STEP 2: FORM EDIT --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">

          {{-- Auth info untuk JS --}}
          <input type="hidden" id="authUserId"   value="{{ $authUser->user_id ?? '' }}">
          <input type="hidden" id="authUserRole" value="{{ $authUser->role ?? '' }}">

          <form id="editForm"
                action="{{ $selectedId ? route('perbarui_jadwal_kerja', $selectedId) : '#' }}"
                method="POST" class="space-y-5"
                @if(count($jadwals) === 0) style="opacity:.55; pointer-events:none;" @endif>
            @csrf
            @method('PUT')

            <input type="hidden" name="jadwal_id" id="jadwalIdHidden" value="{{ $selectedId }}">
            <input type="hidden" name="date" value="{{ $date }}">

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
                              @selected((string)$prefillUser === (string)$u->user_id)>
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
                <input type="date" name="tanggal_kerja" id="tanggalInput" value="{{ $prefillDate }}"
                       readonly
                       class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-4
                              text-slate-500 cursor-not-allowed
                              focus:outline-none transition">
                <p class="text-[11px] text-slate-500 mt-1">Tanggal mengikuti jadwal yang dipilih.</p>
              </div>

              {{-- Jam Mulai — disembunyikan saat status Tutup --}}
              <div id="jamMulaiWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                <label class="block text-sm font-semibold text-slate-800 mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jamMulaiInput"
                       value="{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillMulai }}"
                       class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                              focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
              </div>

              {{-- Jam Selesai — disembunyikan saat status Tutup --}}
              <div id="jamSelesaiWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                <label class="block text-sm font-semibold text-slate-800 mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jamSelesaiInput"
                       value="{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillSelesai }}"
                       class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                              focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
              </div>

              {{-- Shift — disembunyikan saat status Tutup --}}
              <div class="md:col-span-2" id="shiftWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                <label class="block text-sm font-semibold text-slate-800 mb-1">Waktu Shift</label>
                <select name="waktu_shift" id="shiftSelect"
                        class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4
                               focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                  <option value="">Pilih shift</option>
                  <option value="Pagi"  @selected($prefillShift === 'Pagi')>Pagi</option>
                  <option value="Siang" @selected($prefillShift === 'Siang')>Siang</option>
                  <option value="Sore"  @selected($prefillShift === 'Sore')>Sore</option>
                  <option value="Malam" @selected($prefillShift === 'Malam')>Malam</option>
                </select>
              </div>

              {{-- Status --}}
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-800 mb-1">Status</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                  {{-- AKTIF --}}
                  <label class="group cursor-pointer">
                    <input type="radio" name="status" value="Aktif" class="peer sr-only"
                           @checked(strtolower($prefillStatus) === 'aktif')>
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
                           @checked(strtolower($prefillStatus) === 'catatan')>
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
                           @checked(strtolower($prefillStatus) === 'tutup')>
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

              {{-- Deskripsi — disembunyikan saat status Tutup --}}
              <div class="md:col-span-2" id="descWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                <label class="block text-sm font-semibold text-slate-800 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="descArea" rows="5"
                          placeholder="Contoh: Pekerjaan service rutin, booking pelanggan, dll..."
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3
                                 focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillDesc }}</textarea>
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
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>

      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        Route ubah menerima <span class="font-semibold">?date=</span> + <span class="font-semibold">?jadwal_id=</span> untuk prefill data.
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

  select.is-locked {
    background-color: #f1f5f9 !important;
    border-color:     #94a3b8 !important;
    color:            #475569 !important;
    cursor: not-allowed !important;
    pointer-events: none;
    opacity: 1 !important;
  }
</style>
@endpush

@push('scripts')
<script>
  // ─── Auth admin yang login ─────────────────────────────────────────────────
  const authUserId   = document.getElementById('authUserId')?.value  ?? '';
  const authUserRole = document.getElementById('authUserRole')?.value ?? '';

  // ─── Elemen ────────────────────────────────────────────────────────────────
  const userSelect        = document.getElementById('userSelect');
  const userSelectHint    = document.getElementById('userSelectHint');
  const lockIcon          = document.getElementById('lockIcon');
  const jamMulaiWrapper   = document.getElementById('jamMulaiWrapper');
  const jamSelesaiWrapper = document.getElementById('jamSelesaiWrapper');
  const shiftWrapper      = document.getElementById('shiftWrapper');
  const descWrapper       = document.getElementById('descWrapper');
  const jamMulaiInput     = document.getElementById('jamMulaiInput');
  const jamSelesaiInput   = document.getElementById('jamSelesaiInput');
  const shiftSelect       = document.getElementById('shiftSelect');
  const descArea          = document.getElementById('descArea');

  // ─── 1. Kunci / buka dropdown Nama ────────────────────────────────────────
  function filterUserDropdown(statusValue) {
    if (!userSelect) return;
    const isRestricted = ['Catatan', 'Tutup'].includes(statusValue);

    if (isRestricted) {
      userSelect.value    = authUserId;
      userSelect.disabled = true;
      userSelect.classList.add('is-locked');
      lockIcon?.classList.remove('hidden');

      let h = document.getElementById('hiddenUserId');
      if (!h) {
        h = document.createElement('input');
        h.type = 'hidden'; h.name = 'user_id'; h.id = 'hiddenUserId';
        userSelect.parentNode.appendChild(h);
      }
      h.value = authUserId;

      if (userSelectHint) userSelectHint.textContent = '';
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

  // ─── 2. Sembunyikan / tampilkan field saat status Tutup ───────────────────
  function filterFields(statusValue) {
    const isTutup = statusValue === 'Tutup';

    [jamMulaiWrapper, jamSelesaiWrapper, shiftWrapper, descWrapper].forEach(el => {
      if (el) el.style.display = isTutup ? 'none' : '';
    });

    if (isTutup) {
      if (jamMulaiInput)   jamMulaiInput.value   = '';
      if (jamSelesaiInput) jamSelesaiInput.value  = '';
      if (shiftSelect)     shiftSelect.value      = '';
      if (descArea)        descArea.value         = '';
    }
  }

  // ─── Bind radio status ────────────────────────────────────────────────────
  document.querySelectorAll('#editForm input[name="status"]').forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.checked) {
        filterUserDropdown(radio.value);
        filterFields(radio.value);
      }
    });
  });

  // Jalankan saat load sesuai status prefill
  const checkedStatus = document.querySelector('#editForm input[name="status"]:checked');
  if (checkedStatus) {
    filterUserDropdown(checkedStatus.value);
    filterFields(checkedStatus.value);
  }

  // ─── Sinkronisasi dropdown pilih jadwal → form edit ───────────────────────
  const sel             = document.getElementById('jadwalSelect');
  const pvId            = document.getElementById('pvId');
  const pvTitle         = document.getElementById('pvTitle');
  const pvStatus        = document.getElementById('pvStatus');
  const pvStatusCard    = document.getElementById('pvStatusCard');
  const jadwalIdHidden  = document.getElementById('jadwalIdHidden');
  const tanggalInput    = document.getElementById('tanggalInput');
  const editForm        = document.getElementById('editForm');

  const routeBase  = "{{ rtrim(url(route('perbarui_jadwal_kerja', 0, false)), '/0') }}/";
  const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1).toLowerCase() : '';

  const setStatusPreview = (stRaw) => {
    const st = (stRaw || 'aktif').toLowerCase();
    if (pvStatus) pvStatus.textContent = st.toUpperCase();
    if (!pvStatusCard) return;
    pvStatusCard.className = "rounded-xl border p-3 " + (
      st === 'tutup'     ? 'bg-rose-50 border-rose-200 text-rose-700'
      : st === 'catatan' ? 'bg-amber-50 border-amber-200 text-amber-700'
      : 'bg-emerald-50 border-emerald-200 text-emerald-700'
    );
  };

  const setRadioStatus = (stRaw) => {
    const value = capitalize(stRaw || 'aktif');
    const radio = document.querySelector(`#editForm input[name="status"][value="${value}"]`);
    if (radio) {
      radio.checked = true;
      filterUserDropdown(value);
      filterFields(value);
    }
  };

  const syncFromOption = () => {
    if (!sel) return;
    const opt = sel.options[sel.selectedIndex];
    if (!opt) return;

    const isTutup = (opt.dataset.status || '') === 'tutup';

    if (pvId)    pvId.textContent    = '#' + (opt.dataset.id || opt.value || '-');
    if (pvTitle) pvTitle.textContent = opt.dataset.title || '-';
    setStatusPreview(opt.dataset.status);

    if (jadwalIdHidden) jadwalIdHidden.value = opt.value || '';
    if (editForm && opt.value) editForm.setAttribute('action', routeBase + opt.value);

    if (tanggalInput)    tanggalInput.value    = opt.dataset.tanggal || tanggalInput.value || '';

    // Field yang dikosongkan kalau Tutup
    if (jamMulaiInput)   jamMulaiInput.value   = isTutup ? '' : (opt.dataset.jam_mulai   || '');
    if (jamSelesaiInput) jamSelesaiInput.value  = isTutup ? '' : (opt.dataset.jam_selesai || '');
    if (shiftSelect)     shiftSelect.value      = isTutup ? '' : (opt.dataset.waktu_shift || '');
    if (descArea)        descArea.value         = isTutup ? '' : (opt.dataset.deskripsi   || '');
    if (userSelect)      userSelect.value       = opt.dataset.user_id || '';

    setRadioStatus(opt.dataset.status);
  };

  sel?.addEventListener('change', syncFromOption);
  syncFromOption();

  sel?.addEventListener('change', () => document.getElementById('pickForm')?.submit());

  // ─── Confirm Modal ─────────────────────────────────────────────────────────
  function showConfirmModal({ title, message, confirmText, cancelText, tone = "neutral", onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700"  },
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

  editForm?.addEventListener('submit', (e) => {
    if (editForm.dataset.confirmed === "1") return;
    e.preventDefault();
    showConfirmModal({
      title: "Simpan perubahan?",
      message: "Perubahan jadwal akan disimpan ke sistem.",
      confirmText: "Ya, Simpan",
      cancelText: "Batal",
      onConfirm: () => { editForm.dataset.confirmed = "1"; editForm.submit(); }
    });
  });

  const btnBatal = document.getElementById('btnBatal');
  btnBatal?.addEventListener('click', (e) => {
    e.preventDefault();
    const go = btnBatal.getAttribute('href');
    showConfirmModal({
      title: "Batalkan perubahan?",
      message: "Kalau kamu keluar sekarang, perubahan yang belum disimpan akan hilang.",
      confirmText: "Ya, Keluar",
      cancelText: "Tetap di sini",
      onConfirm: () => window.location.href = go
    });
  });
</script>

<script>
document.querySelectorAll('input[type="date"][readonly]').forEach(el => {
    el.addEventListener('keydown', e => e.preventDefault());
    el.addEventListener('mousedown', e => e.preventDefault());
});
</script>
@endpush