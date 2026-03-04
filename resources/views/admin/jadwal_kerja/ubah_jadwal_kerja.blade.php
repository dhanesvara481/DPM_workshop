{{-- resources/views/admin/jadwal_kerja/ubah_jadwal_kerja.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Ubah Jadwal')

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
        <p class="text-xs text-slate-500">Klik bubble agenda → edit form → simpan.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('kelola_jadwal_kerja') }}"
         class="inline-flex items-center gap-2 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 text-sm font-medium">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
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
      $date      = $date ?? request('date') ?? now()->format('Y-m-d');
      $selectedId = request('jadwal_id') ?? old('jadwal_id');

      $jadwals = collect($jadwalKerjas ?? [])->map(fn($j) => [
        'id'          => $j->jadwal_id,
        'tanggal'     => $j->tanggal_kerja->format('Y-m-d'),
        'user_id'     => $j->user_id,
        'username'    => $j->user->username ?? 'Staf',
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

      $prefillDate    = $selected['tanggal']     ?? $date                  ?? '';
      $prefillUser    = $selected['user_id']     ?? old('user_id')          ?? '';
      $prefillMulai   = $selected['jam_mulai']   ?? old('jam_mulai')        ?? '';
      $prefillSelesai = $selected['jam_selesai'] ?? old('jam_selesai')      ?? '';
      $prefillShift   = $selected['waktu_shift'] ?? old('waktu_shift')      ?? '';
      $prefillStatus  = $selected['status']      ?? old('status', 'aktif')  ?? 'aktif';
      $prefillDesc    = $selected['deskripsi']   ?? old('deskripsi')        ?? '';
    @endphp

    {{-- hidden auth refs untuk JS --}}
    <input type="hidden" id="authUserId"   value="{{ $authUser->user_id ?? '' }}">
    <input type="hidden" id="authUserRole" value="{{ $authUser->role ?? '' }}">

    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      {{-- HEADER --}}
      <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="min-w-0">
            <div class="text-lg sm:text-xl font-semibold tracking-tight text-slate-900">Ubah Jadwal</div>
            <div class="text-xs text-slate-500 mt-1">
              Tanggal:
              <span class="font-semibold text-slate-900">
                {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}
              </span>
              <span class="mx-2 text-slate-300">•</span>
              <span class="font-semibold text-slate-700">{{ count($jadwals) }} agenda</span>
            </div>
          </div>
          <div class="flex items-center gap-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Aktif</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Catatan</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Tutup</span>
          </div>
        </div>
      </div>

      <div class="p-5 sm:p-6 space-y-5">

        {{-- flash messages --}}
        @if(session('success'))
          <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 text-sm flex items-center gap-3">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
          </div>
        @endif
        @if(session('error'))
          <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
          <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">
            <ul class="list-disc pl-5 space-y-1">
              @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
          </div>
        @endif

        @if(count($jadwals) === 0)
          {{-- EMPTY STATE --}}
          <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <div class="h-16 w-16 rounded-2xl bg-slate-100 grid place-items-center mx-auto mb-4">
              <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <div class="text-base font-semibold text-slate-900 mb-1">Belum ada jadwal</div>
            <div class="text-sm text-slate-500">Tidak ada jadwal di tanggal ini yang bisa diubah.</div>
            <a href="{{ route('tambah_jadwal_kerja') }}?date={{ $date }}"
               class="mt-5 inline-flex items-center gap-2 h-10 px-5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
              Tambah Jadwal Dulu
            </a>
          </div>

        @else

          {{-- ═══════════════════════════════════════════════════════
               STEP 1 — BUBBLE SELECTOR
          ═══════════════════════════════════════════════════════ --}}
          <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
              <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">1</span>
              <div>
                <p class="text-sm font-semibold text-slate-900">Pilih agenda yang mau diubah</p>
                <p class="text-xs text-slate-400">Klik bubble untuk memuat data ke form edit</p>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-center gap-2 flex-wrap" id="bubbleRow">
                @foreach($jadwals as $idx => $j)
                  @php
                    $st  = strtolower($j['status']);
                    $col = $st === 'tutup' ? 'rose' : ($st === 'catatan' ? 'amber' : 'emerald');
                    $isActive = (string)$j['id'] === (string)$selectedId;
                    $activeClass = $isActive
                      ? "bg-{$col}-600 border-{$col}-600 text-white shadow-lg shadow-{$col}-200/60 scale-105"
                      : "bg-{$col}-50 border-{$col}-300 text-{$col}-800 hover:bg-{$col}-100 hover:scale-[1.03]";
                  @endphp
                  <button type="button"
                          class="agenda-bubble inline-flex items-center gap-2 h-10 px-4 rounded-full border text-xs font-bold
                                 transition-all duration-150 cursor-pointer {{ $activeClass }}"
                          data-id="{{ $j['id'] }}"
                          data-idx="{{ $idx }}"
                          data-status="{{ $st }}"
                          data-username="{{ $j['username'] }}"
                          data-user_id="{{ $j['user_id'] }}"
                          data-tanggal="{{ $j['tanggal'] }}"
                          data-jam_mulai="{{ $j['jam_mulai'] }}"
                          data-jam_selesai="{{ $j['jam_selesai'] }}"
                          data-waktu_shift="{{ $j['waktu_shift'] ?? '' }}"
                          data-deskripsi="{{ $j['deskripsi'] ?? '' }}">
                    <span class="agenda-bubble-num h-5 w-5 rounded-full {{ $isActive ? 'bg-white/20' : "bg-{$col}-200" }} grid place-items-center text-[10px] font-black">{{ $idx + 1 }}</span>
                    <span class="max-w-[100px] truncate">{{ $j['username'] }}</span>
                    @if($st === 'tutup')
                      <span class="opacity-70">✕</span>
                    @endif
                  </button>
                @endforeach
              </div>

              {{-- Preview strip --}}
              @php
                $pvIsTutup  = isset($prefillStatus) && strtolower($prefillStatus) === 'tutup';
                $pvShiftTxt = $pvIsTutup
                  ? 'TUTUP'
                  : (($selected['waktu_shift'] ?? '-')
                    . ((!empty($prefillMulai) && !empty($prefillSelesai)) ? " · {$prefillMulai}–{$prefillSelesai}" : ''));
                $pvStatusTxt = strtoupper($prefillStatus ?? 'aktif');
                $pvStatusBg  = $pvIsTutup
                  ? 'bg-rose-50 border-rose-200'
                  : ((isset($prefillStatus) && strtolower($prefillStatus) === 'catatan')
                    ? 'bg-amber-50 border-amber-200'
                    : 'bg-emerald-50 border-emerald-200');
                $pvItems = [
                  ['id' => 'pvId',     'label' => 'id',     'val' => '#' . ($selected['id'] ?? '-'),    'bg' => 'bg-slate-50 border-slate-200'],
                  ['id' => 'pvNama',   'label' => 'nama',   'val' => $selected['username'] ?? '-',       'bg' => 'bg-slate-50 border-slate-200'],
                  ['id' => 'pvShift',  'label' => 'shift',  'val' => $pvShiftTxt,                        'bg' => 'bg-slate-50 border-slate-200'],
                  ['id' => 'pvStatus', 'label' => 'status', 'val' => $pvStatusTxt,                       'bg' => $pvStatusBg],
                ];
              @endphp
              <div id="previewStrip" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($pvItems as $pv)
                  <div class="rounded-xl border {{ $pv['bg'] }} p-3">
                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">{{ $pv['label'] }}</div>
                    <div id="{{ $pv['id'] }}" class="font-semibold text-slate-900 text-sm mt-0.5 truncate">{{ $pv['val'] }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- ═══════════════════════════════════════════════════════
               STEP 2 — FORM EDIT
          ═══════════════════════════════════════════════════════ --}}
          <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <span class="h-6 w-6 rounded-lg bg-slate-900 text-white grid place-items-center text-[11px] font-bold shrink-0">2</span>
                <div>
                  <p class="text-sm font-semibold text-slate-900">Edit & Simpan</p>
                  <p class="text-xs text-slate-400">Ubah field yang diinginkan lalu klik simpan</p>
                </div>
              </div>
              <div id="editingBadge"
                   class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[11px] font-bold">
                {{-- dinamis via JS --}}
              </div>
            </div>

            <form id="editForm"
                  action="{{ $selectedId ? route('perbarui_jadwal_kerja', $selectedId) : '#' }}"
                  method="POST" class="p-5 space-y-4">
              @csrf
              @method('PUT')

              <input type="hidden" name="jadwal_id" id="jadwalIdHidden" value="{{ $selectedId }}">
              <input type="hidden" name="date" value="{{ $date }}">

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nama --}}
                <div>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Nama</label>
                  <div class="relative">
                    <select name="user_id" id="userSelect"
                            class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 pr-10 text-sm
                                   focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition appearance-none">
                      <option value="">Pilih user</option>
                      @foreach(($users ?? []) as $u)
                        <option value="{{ $u->user_id }}"
                                @selected((string)$prefillUser === (string)$u->user_id)>
                          {{ $u->username }}
                        </option>
                      @endforeach
                    </select>

                  </div>
                </div>

                {{-- Tanggal --}}
                <div>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Tanggal Kerja</label>
                  <input type="date" name="tanggal_kerja" id="tanggalInput" value="{{ $prefillDate }}"
                         readonly
                         class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm
                                text-slate-500 cursor-not-allowed focus:outline-none transition">
                  <p class="text-[11px] text-slate-400 mt-1">Mengikuti jadwal yang dipilih (tidak bisa diubah).</p>
                </div>

                {{-- Waktu Shift --}}
                <div id="shiftWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Waktu Shift</label>
                  <select name="waktu_shift" id="shiftSelect"
                          class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm
                                 focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                    <option value="">Pilih shift</option>
                    @foreach(['Pagi','Siang','Sore','Malam'] as $s)
                      <option value="{{ $s }}" @selected($prefillShift === $s)>{{ $s }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Placeholder agar grid tetap 2 kolom kalau shift hidden --}}
                <div id="shiftSpacer" @if(strtolower($prefillStatus) !== 'tutup') style="display:none" @endif></div>

                {{-- Jam Mulai --}}
                <div id="jamMulaiWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Jam Mulai</label>
                  <input type="time" name="jam_mulai" id="jamMulaiInput"
                         value="{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillMulai }}"
                         class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm
                                focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                </div>

                {{-- Jam Selesai --}}
                <div id="jamSelesaiWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Jam Selesai</label>
                  <input type="time" name="jam_selesai" id="jamSelesaiInput"
                         value="{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillSelesai }}"
                         class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm
                                focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Status</label>
                  <div class="grid grid-cols-3 gap-3" id="statusRow">
                    @foreach([
                      ['val'=>'Aktif',   'col'=>'emerald', 'desc'=>'Jadwal kerja normal'],
                      ['val'=>'Catatan', 'col'=>'amber',   'desc'=>'Info / reminder'],
                      ['val'=>'Tutup',   'col'=>'rose',    'desc'=>'Libur / tidak operasional'],
                    ] as $s)
                      <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $s['val'] }}" class="peer sr-only"
                               @checked(strtolower($prefillStatus) === strtolower($s['val']))>
                        <div class="rounded-xl border border-{{ $s['col'] }}-200 bg-{{ $s['col'] }}-50 px-3 py-3
                                    hover:bg-{{ $s['col'] }}-100 transition text-center
                                    peer-checked:ring-2 peer-checked:ring-{{ $s['col'] }}-400 peer-checked:border-{{ $s['col'] }}-400">
                          <div class="text-sm font-bold text-{{ $s['col'] }}-900">{{ $s['val'] }}</div>
                          <div class="text-[10px] text-{{ $s['col'] }}-700 mt-0.5">{{ $s['desc'] }}</div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2" id="descWrapper" @if(strtolower($prefillStatus) === 'tutup') style="display:none" @endif>
                  <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">
                    Deskripsi <span class="font-normal normal-case opacity-60">(opsional, maks 100 karakter)</span>
                  </label>
                  <input type="text" name="deskripsi" id="descInput"
                         value="{{ strtolower($prefillStatus) === 'tutup' ? '' : $prefillDesc }}"
                         maxlength="100"
                         placeholder="Contoh: Servis rutin, booking pelanggan..."
                         class="w-full h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm
                                focus:outline-none focus:ring-4 focus:ring-slate-200/60 focus:border-slate-300 transition">
                </div>
              </div>

              <div class="pt-2 flex flex-col sm:flex-row gap-2 sm:justify-end border-t border-slate-100">
                <a id="btnBatal" href="{{ route('kelola_jadwal_kerja') }}"
                   class="h-11 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold
                          inline-flex items-center justify-center">
                  Batal
                </a>
                <button type="submit"
                        class="h-11 px-6 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold
                               shadow-[0_12px_24px_rgba(2,6,23,0.14)] inline-flex items-center gap-2">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>

        @endif

      </div>

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        Route menerima <span class="font-semibold">?date=</span> dari modal kalender.
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
  }

  .agenda-bubble { user-select: none; }

  /* Smooth bubble active transition */
  .agenda-bubble.is-active { transform: scale(1.06); }

  @keyframes formSlide {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  #editForm { animation: formSlide .18s ease; }
</style>
@endpush

@push('scripts')
<script>
  // ─── Data dari PHP ─────────────────────────────────────────────────────────
  const JADWALS     = @json($jadwals);
  const AUTH_USER_ID = document.getElementById('authUserId')?.value ?? '';

  // ─── Elemen ────────────────────────────────────────────────────────────────
  const userSelect        = document.getElementById('userSelect');
  const lockIcon          = document.getElementById('lockIcon');
  const tanggalInput      = document.getElementById('tanggalInput');
  const jamMulaiWrapper   = document.getElementById('jamMulaiWrapper');
  const jamSelesaiWrapper = document.getElementById('jamSelesaiWrapper');
  const shiftWrapper      = document.getElementById('shiftWrapper');
  const shiftSpacer       = document.getElementById('shiftSpacer');
  const descWrapper       = document.getElementById('descWrapper');
  const jamMulaiInput     = document.getElementById('jamMulaiInput');
  const jamSelesaiInput   = document.getElementById('jamSelesaiInput');
  const shiftSelect       = document.getElementById('shiftSelect');
  const descInput         = document.getElementById('descInput');
  const editForm          = document.getElementById('editForm');
  const jadwalIdHidden    = document.getElementById('jadwalIdHidden');
  const editingBadge      = document.getElementById('editingBadge');

  // Preview strip elements
  const pvId     = document.getElementById('pvId');
  const pvNama   = document.getElementById('pvNama');
  const pvShift  = document.getElementById('pvShift');
  const pvStatus = document.getElementById('pvStatus');

  const routeBase = "{{ rtrim(url(route('perbarui_jadwal_kerja', 0, false)), '/0') }}/";

  // ─── Helpers ───────────────────────────────────────────────────────────────
  const capitalize = s => s ? s.charAt(0).toUpperCase() + s.slice(1).toLowerCase() : '';

  function setStatusColorClass(el, st, palette) {
    // Tailwind dynamic classes tidak bisa dipakai langsung — pakai hardcoded map
    const map = {
      emerald: { bg: 'bg-emerald-50', border: 'border-emerald-200', text: 'text-emerald-800' },
      amber:   { bg: 'bg-amber-50',   border: 'border-amber-200',   text: 'text-amber-800'   },
      rose:    { bg: 'bg-rose-50',    border: 'border-rose-200',    text: 'text-rose-800'     },
      slate:   { bg: 'bg-slate-50',   border: 'border-slate-200',   text: 'text-slate-800'    },
    };
    const c = map[palette] || map.slate;
    ['bg-emerald-50','bg-amber-50','bg-rose-50','bg-slate-50',
     'border-emerald-200','border-amber-200','border-rose-200','border-slate-200',
     'text-emerald-800','text-amber-800','text-rose-800','text-slate-800'].forEach(cls => el.classList.remove(cls));
    el.classList.add(c.bg, c.border, c.text);
  }

  function colorForStatus(st) {
    return st === 'tutup' ? 'rose' : (st === 'catatan' ? 'amber' : 'emerald');
  }

  // ─── Bubble active style maps (karena Tailwind dynamic class tidak work) ───
  const BUBBLE_ACTIVE = {
    emerald: 'bg-emerald-600 border-emerald-600 text-white shadow-lg',
    amber:   'bg-amber-500   border-amber-500   text-white shadow-lg',
    rose:    'bg-rose-500    border-rose-500    text-white shadow-lg',
  };
  const BUBBLE_INACTIVE = {
    emerald: 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100',
    amber:   'bg-amber-50   border-amber-300   text-amber-800   hover:bg-amber-100',
    rose:    'bg-rose-50    border-rose-300    text-rose-800    hover:bg-rose-100',
  };
  const NUM_ACTIVE   = { emerald:'bg-white/20', amber:'bg-white/20', rose:'bg-white/20' };
  const NUM_INACTIVE = { emerald:'bg-emerald-200', amber:'bg-amber-200', rose:'bg-rose-200' };

  function updateBubbleStyles(activeId) {
    document.querySelectorAll('.agenda-bubble').forEach(btn => {
      const st  = btn.dataset.status || 'aktif';
      const col = colorForStatus(st);
      const isAct = String(btn.dataset.id) === String(activeId);

      // Strip all dynamic bubble classes
      Object.values(BUBBLE_ACTIVE).concat(Object.values(BUBBLE_INACTIVE)).forEach(cls =>
        cls.split(' ').forEach(c => btn.classList.remove(c))
      );
      btn.classList.remove('scale-105','scale-100');

      const classes = (isAct ? BUBBLE_ACTIVE[col] : BUBBLE_INACTIVE[col]).split(' ');
      classes.forEach(c => btn.classList.add(c));
      if (isAct) btn.classList.add('scale-105');

      // Update number pill bg
      const numEl = btn.querySelector('.agenda-bubble-num');
      if (numEl) {
        [NUM_ACTIVE[col], NUM_INACTIVE[col]].forEach(cls => numEl.classList.remove(cls));
        numEl.classList.add(isAct ? NUM_ACTIVE[col] : NUM_INACTIVE[col]);
      }
    });
  }

  // ─── Load jadwal data ke form ───────────────────────────────────────────────
  function loadJadwal(j) {
    const st     = (j.status || 'aktif').toLowerCase();
    const isTutup = st === 'tutup';
    const col    = colorForStatus(st);

    // Update form action
    if (editForm) editForm.setAttribute('action', routeBase + j.id);
    if (jadwalIdHidden) jadwalIdHidden.value = j.id;

    // Fill fields
    if (tanggalInput)    tanggalInput.value    = j.tanggal || '';
    if (userSelect)      userSelect.value      = j.user_id || '';
    if (jamMulaiInput)   jamMulaiInput.value   = isTutup ? '' : (j.jam_mulai   || '');
    if (jamSelesaiInput) jamSelesaiInput.value  = isTutup ? '' : (j.jam_selesai || '');
    if (shiftSelect)     shiftSelect.value      = isTutup ? '' : (j.waktu_shift || '');
    if (descInput)       descInput.value        = isTutup ? '' : (j.deskripsi   || '');

    // Status radio
    const capStatus = capitalize(st);
    const radioEl   = document.querySelector(`#editForm input[name="status"][value="${capStatus}"]`);
    if (radioEl) radioEl.checked = true;

    // Show/hide fields
    filterFields(capStatus);
    filterUserDropdown(capStatus);

    // Update preview strip
    if (pvId)    pvId.textContent    = '#' + j.id;
    if (pvNama)  pvNama.textContent  = j.username || '—';
    if (pvShift) pvShift.textContent = isTutup ? 'TUTUP'
      : ((j.waktu_shift || '—') + (j.jam_mulai && j.jam_selesai ? ` · ${j.jam_mulai}–${j.jam_selesai}` : ''));
    if (pvStatus) {
      pvStatus.textContent = capStatus.toUpperCase();
      const card = pvStatus.closest('.rounded-xl');
      if (card) setStatusColorClass(card, st, col);
    }

    // Update editing badge
    if (editingBadge) {
      editingBadge.textContent = `Mengedit: Agenda ${(JADWALS.findIndex(x => String(x.id) === String(j.id)) + 1)}`;
      // Clear badge color classes
      ['bg-emerald-50','border-emerald-200','text-emerald-800',
       'bg-amber-50','border-amber-200','text-amber-800',
       'bg-rose-50','border-rose-200','text-rose-800'].forEach(c => editingBadge.classList.remove(c));
      const bMap = {
        emerald: ['bg-emerald-50','border-emerald-200','text-emerald-800'],
        amber:   ['bg-amber-50',  'border-amber-200',  'text-amber-800'  ],
        rose:    ['bg-rose-50',   'border-rose-200',   'text-rose-800'   ],
      };
      (bMap[col] || bMap.emerald).forEach(c => editingBadge.classList.add(c));
    }

    // Animate form refresh
    if (editForm) {
      editForm.style.animation = 'none';
      void editForm.offsetWidth;
      editForm.style.animation = 'formSlide .18s ease';
    }
  }

  // ─── Field visibility ──────────────────────────────────────────────────────
  function filterFields(statusValue) {
    const isTutup = statusValue === 'Tutup';
    [jamMulaiWrapper, jamSelesaiWrapper, shiftWrapper, descWrapper].forEach(el => {
      if (el) el.style.display = isTutup ? 'none' : '';
    });
    if (shiftSpacer) shiftSpacer.style.display = isTutup ? '' : 'none';
    if (isTutup) {
      if (jamMulaiInput)   jamMulaiInput.value   = '';
      if (jamSelesaiInput) jamSelesaiInput.value  = '';
      if (shiftSelect)     shiftSelect.value      = '';
      if (descInput)       descInput.value        = '';
    }
  }

  function filterUserDropdown(statusValue) {
    // Di halaman UBAH, tidak ada lock user — user bebas dipilih untuk semua status.
    // (Lock hanya relevan di Tambah saat membuat jadwal baru)
    if (!userSelect) return;
    userSelect.disabled = false;
    userSelect.classList.remove('is-locked');
    lockIcon?.classList.add('hidden');
    document.getElementById('hiddenUserId')?.remove();
  }

  // ─── Bind status radio ─────────────────────────────────────────────────────
  document.querySelectorAll('#editForm input[name="status"]').forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.checked) {
        filterFields(radio.value);
        filterUserDropdown(radio.value);
      }
    });
  });

  // ─── Bind bubbles ──────────────────────────────────────────────────────────
  document.querySelectorAll('.agenda-bubble').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const j  = JADWALS.find(x => String(x.id) === String(id));
      if (!j) return;
      updateBubbleStyles(id);
      loadJadwal(j);
    });
  });

  // ─── Init dengan jadwal yang aktif ─────────────────────────────────────────
  // String() supaya tidak ada type mismatch int vs string dari Blade
  const INIT_ID = String(@json($selected ? $selected['id'] : ($jadwals[0]['id'] ?? '')));
  if (INIT_ID && JADWALS.length > 0) {
    const initJ = JADWALS.find(x => String(x.id) === INIT_ID) || JADWALS[0];
    updateBubbleStyles(String(initJ.id));
    loadJadwal(initJ);
  }

  // ─── Confirm Modal ─────────────────────────────────────────────────────────
  function showConfirmModal({ title, message, note, confirmText, cancelText, tone = "neutral", onConfirm }) {
    const toneBtn = { neutral: "bg-slate-900 hover:bg-slate-800", danger: "bg-rose-600 hover:bg-rose-700" };
    const t = toneBtn[tone] || toneBtn.neutral;
    const wrap = document.createElement('div');
    wrap.className = "fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3";
    wrap.innerHTML = `
      <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-[0_30px_80px_rgba(2,6,23,0.30)] overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
          <div>
            <div class="text-base font-semibold text-slate-900">${title}</div>
            <div class="text-sm text-slate-600 mt-1">${message}</div>
          </div>
          <button type="button" class="btn-x h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 grid place-items-center shrink-0">
            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <div class="p-5">
          ${note ? `<div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600 mb-4">${note}</div>` : ''}
          <div class="flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t} text-white text-sm font-semibold">${confirmText}</button>
          </div>
        </div>
      </div>`;
    const close = () => wrap.remove();
    wrap.addEventListener('click', e => { if (e.target === wrap) close(); });
    wrap.querySelector('.btn-x')?.addEventListener('click', close);
    wrap.querySelector('.btn-cancel')?.addEventListener('click', close);
    wrap.querySelector('.btn-ok')?.addEventListener('click', () => { close(); onConfirm?.(); });
    document.body.appendChild(wrap);
  }

  editForm?.addEventListener('submit', e => {
    if (editForm.dataset.confirmed === '1') return;
    e.preventDefault();
    showConfirmModal({
      title: 'Simpan perubahan?',
      message: 'Perubahan jadwal akan disimpan ke sistem.',
      note: 'Pastikan semua data sudah benar sebelum menyimpan.',
      confirmText: 'Ya, Simpan',
      cancelText: 'Batal',
      onConfirm: () => { editForm.dataset.confirmed = '1'; editForm.submit(); }
    });
  });

  document.getElementById('btnBatal')?.addEventListener('click', e => {
    e.preventDefault();
    const go = e.currentTarget.getAttribute('href');
    showConfirmModal({
      title: 'Batalkan perubahan?',
      message: 'Perubahan yang belum disimpan akan hilang.',
      confirmText: 'Ya, Keluar',
      cancelText: 'Tetap di sini',
      onConfirm: () => window.location.href = go
    });
  });

  // Prevent manual edit on readonly date input
  document.querySelectorAll('input[type="date"][readonly]').forEach(el => {
    el.addEventListener('keydown', e => e.preventDefault());
    el.addEventListener('mousedown', e => e.preventDefault());
  });
</script>
@endpush