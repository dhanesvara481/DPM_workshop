@extends('staff.layout.app')

@section('title', 'DPM Workshop - Staff')
@section('page_title', 'Stok Opname')
@section('page_subtitle', now()->format('d M Y'))

@section('content')

<div class="max-w-5xl mx-auto w-full space-y-5">

  {{-- Alert --}}
  @foreach(['success','error','info'] as $type)
    @if(session($type))
      @php $c = ['success'=>'emerald','error'=>'rose','info'=>'blue'][$type]; @endphp
      <div class="flex items-start gap-3 rounded-xl border border-{{ $c }}-200 bg-{{ $c }}-50 px-4 py-3 text-sm text-{{ $c }}-800">
        {{ session($type) }}
      </div>
    @endif
  @endforeach

  {{-- Penjelasan --}}
  <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
    <div class="flex items-start gap-3">
      <svg class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <div>
        <p class="text-sm font-semibold text-blue-800 mb-0.5">Tugas Stok Opname</p>
        <p class="text-xs text-blue-700">
          Di sini tampil sesi opname yang di-assign kepadamu oleh admin.
          Tugasmu adalah menghitung stok fisik barang di gudang, mengisi data, lalu submit untuk disetujui admin.
          Selama sesi aktif (Draft), pergerakan stok dibekukan otomatis.
        </p>
      </div>
    </div>
  </div>

  {{-- Filter --}}
  <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] p-4">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-[130px]">
        <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
        <select name="status"
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
          <option value="">Semua</option>
          <option value="draft"             {{ request('status') === 'draft'             ? 'selected' : '' }}>Draft (Perlu Diisi)</option>
          <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
          <option value="disetujui"         {{ request('status') === 'disetujui'         ? 'selected' : '' }}>Disetujui</option>
          <option value="ditolak"           {{ request('status') === 'ditolak'           ? 'selected' : '' }}>Ditolak</option>
        </select>
      </div>
      <div class="flex gap-2">
        <button type="submit"
                class="h-9 px-4 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition">
          Filter
        </button>
        @if(request()->hasAny(['status']))
          <a href="{{ route('stok_opname.daftarOpnameStaff') }}"
             class="h-9 px-4 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
            Reset
          </a>
        @endif
      </div>
    </form>
  </div>

  {{-- List --}}
  <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur shadow-[0_4px_20px_rgba(2,6,23,0.06)] overflow-hidden">

    @if($opnames->isEmpty())
      <div class="py-20 text-center">
        <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-100 grid place-items-center mb-4">
          <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <p class="text-slate-500 text-sm">Belum ada sesi opname yang di-assign kepadamu.</p>
        <p class="text-slate-400 text-xs mt-1">Hubungi admin jika kamu seharusnya mendapat tugas opname.</p>
      </div>
    @else

      {{-- Desktop --}}
      <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Oleh</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($opnames as $opname)
            <tr class="hover:bg-slate-50 transition {{ $opname->isDraft() ? 'bg-amber-50/30' : '' }}">
              <td class="px-5 py-4 font-medium text-slate-800 whitespace-nowrap">
                {{ $opname->tanggal_opname->format('d M Y') }}
              </td>
              <td class="px-5 py-4 text-slate-600 max-w-[180px] truncate">
                {{ $opname->keterangan ?? '-' }}
              </td>
              <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                {{ $opname->nama_pembuat }}
              </td>
              <td class="px-5 py-4 text-center">
                @php
                  $total  = $opname->details_count ?: 1;
                  $diisi  = $opname->sudah_diisi_count ?? 0;
                  $persen = min(100, (int) round($diisi / $total * 100));
                @endphp
                <div class="flex items-center justify-center gap-2">
                  <div class="w-20 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full rounded-full {{ $persen >= 100 ? 'bg-emerald-500' : 'bg-blue-400' }}"
                         style="width: {{ $persen }}%"></div>
                  </div>
                  <span class="text-xs text-slate-500 whitespace-nowrap">{{ $diisi }}/{{ $opname->details_count }}</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
                  {{ $opname->status_label }}
                </span>
                @if($opname->isDitolak() && $opname->catatan_approval)
                  <p class="text-xs text-rose-600 mt-1 italic max-w-[160px] truncate" title="{{ $opname->catatan_approval }}">
                    {{ $opname->catatan_approval }}
                  </p>
                @endif
              </td>
              <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  @if($opname->isDraft())
                    <a href="{{ route('stok_opname.ubahOpnameStaff', $opname->opname_id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-medium hover:bg-slate-700 transition">
                      Isi Stok
                    </a>
                  @endif
                  <a href="{{ route('stok_opname.detailOpnameStaff', $opname->opname_id) }}"
                     class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                    Detail
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Mobile --}}
      <div class="sm:hidden divide-y divide-slate-100">
        @foreach($opnames as $opname)
        <div class="p-4 {{ $opname->isDraft() ? 'bg-amber-50/30' : '' }}">
          <div class="flex items-start justify-between gap-3 mb-2">
            <div>
              <p class="text-sm font-bold text-slate-800">{{ $opname->tanggal_opname->format('d M Y') }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ $opname->keterangan ?? '-' }}</p>
              <p class="text-xs text-slate-400 mt-0.5">Oleh: {{ $opname->nama_pembuat }}</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }} shrink-0">
              {{ $opname->status_label }}
            </span>
          </div>
          @php
            $total  = $opname->details_count ?: 1;
            $diisi  = $opname->sudah_diisi_count ?? 0;
            $persen = min(100, (int) round($diisi / $total * 100));
          @endphp
          <div class="flex items-center gap-2 mb-3">
            <div class="flex-1 h-1.5 rounded-full bg-slate-200 overflow-hidden">
              <div class="h-full rounded-full {{ $persen >= 100 ? 'bg-emerald-500' : 'bg-blue-400' }}"
                   style="width: {{ $persen }}%"></div>
            </div>
            <span class="text-xs text-slate-500 shrink-0">{{ $diisi }}/{{ $opname->details_count }}</span>
          </div>
          @if($opname->isDitolak() && $opname->catatan_approval)
            <p class="text-xs text-rose-600 mb-2 italic">Ditolak: {{ $opname->catatan_approval }}</p>
          @endif
          <div class="flex flex-wrap gap-2">
            @if($opname->isDraft())
              <a href="{{ route('stok_opname.ubahOpnameStaff', $opname->opname_id) }}"
                 class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-medium hover:bg-slate-700 transition">
                Isi Stok
              </a>
            @endif
            <a href="{{ route('stok_opname.detailOpnameStaff', $opname->opname_id) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
              Detail
            </a>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($opnames->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between gap-3 flex-wrap">
          <p class="text-xs text-slate-500">
            Menampilkan {{ $opnames->firstItem() }}–{{ $opnames->lastItem() }} dari {{ $opnames->total() }} sesi
          </p>
          <div class="flex items-center gap-1">
            @if ($opnames->onFirstPage())
              <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 text-sm cursor-not-allowed">‹</span>
            @else
              <a href="{{ $opnames->previousPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">‹</a>
            @endif
            @foreach ($opnames->getUrlRange(max(1, $opnames->currentPage() - 2), min($opnames->lastPage(), $opnames->currentPage() + 2)) as $page => $url)
              @if ($page == $opnames->currentPage())
                <span class="h-9 w-9 rounded-xl bg-slate-900 text-white grid place-items-center text-sm font-semibold">{{ $page }}</span>
              @else
                <a href="{{ $url }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">{{ $page }}</a>
              @endif
            @endforeach
            @if ($opnames->hasMorePages())
              <a href="{{ $opnames->nextPageUrl() }}" class="h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center text-slate-700 text-sm">›</a>
            @else
              <span class="h-9 w-9 rounded-xl border border-slate-200 bg-slate-50 grid place-items-center text-slate-300 text-sm cursor-not-allowed">›</span>
            @endif
          </div>
        </div>
      @endif

    @endif
  </div>

</div>

@endsection