@extends('admin.layout.app')

@section('title', 'Stok Opname')

@section('content')
<div class="px-4 md:px-8 py-8 max-w-7xl mx-auto">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Stok Opname</h1>
      <p class="text-sm text-slate-500 mt-1">Audit fisik stok barang & penyesuaian selisih</p>
    </div>
    <a href="{{ route('stok_opname.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium
              hover:bg-slate-700 transition shadow-sm">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
      Buat Sesi Opname
    </a>
  </div>

  {{-- Alert --}}
  @if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
      <svg class="h-5 w-5 shrink-0 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
      <svg class="h-5 w-5 shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      </svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Filter --}}
  <form method="GET" class="mb-6 flex flex-wrap gap-3 items-end">
    <div>
      <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
      <select name="status"
              class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
        <option value="">Semua Status</option>
        <option value="draft"             {{ request('status') === 'draft'             ? 'selected' : '' }}>Draft</option>
        <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
        <option value="disetujui"         {{ request('status') === 'disetujui'         ? 'selected' : '' }}>Disetujui</option>
        <option value="ditolak"           {{ request('status') === 'ditolak'           ? 'selected' : '' }}>Ditolak</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-medium text-slate-500 mb-1">Dari</label>
      <input type="date" name="dari" value="{{ request('dari') }}"
             class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
    </div>
    <div>
      <label class="block text-xs font-medium text-slate-500 mb-1">Sampai</label>
      <input type="date" name="sampai" value="{{ request('sampai') }}"
             class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
    </div>
    <button type="submit"
            class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-700 transition">
      Filter
    </button>
    @if(request()->hasAny(['status','dari','sampai']))
      <a href="{{ route('stok_opname.index') }}"
         class="px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
        Reset
      </a>
    @endif
  </form>

  {{-- Tabel --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    @if($opnames->isEmpty())
      <div class="py-20 text-center">
        <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-100 grid place-items-center mb-4">
          <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <p class="text-slate-500 text-sm">Belum ada sesi stok opname.</p>
        <a href="{{ route('stok_opname.create') }}" class="mt-3 inline-block text-sm font-medium text-slate-900 underline underline-offset-2">
          Buat sesi pertama
        </a>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Oleh</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Barang</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Selisih</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($opnames as $opname)
            <tr class="hover:bg-slate-50 transition">
              <td class="px-5 py-4 font-medium text-slate-800 whitespace-nowrap">
                {{ $opname->tanggal_opname->format('d M Y') }}
              </td>
              <td class="px-5 py-4 text-slate-600 max-w-[200px] truncate">
                {{ $opname->keterangan ?? '-' }}
              </td>
              <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                {{ $opname->nama_pembuat }}
              </td>
              <td class="px-5 py-4 text-center text-slate-700 font-medium">
                {{ $opname->details_count }}
              </td>
              <td class="px-5 py-4 text-center">
                @if($opname->jumlah_selisih_count > 0)
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                    {{ $opname->jumlah_selisih_count }} item
                  </span>
                @else
                  <span class="text-slate-400 text-xs">—</span>
                @endif
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opname->status_badge_class }}">
                  {{ $opname->status_label }}
                </span>
              </td>
              <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <a href="{{ route('stok_opname.show', $opname->opname_id) }}"
                     class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                    Detail
                  </a>
                  @if($opname->isDraft())
                    <a href="{{ route('stok_opname.edit', $opname->opname_id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-medium hover:bg-slate-700 transition">
                      Isi Stok
                    </a>
                  @endif
                  @if(in_array($opname->status, ['draft', 'ditolak']))
                    <form method="POST" action="{{ route('stok_opname.destroy', $opname->opname_id) }}"
                          onsubmit="return confirm('Hapus sesi opname ini?')">
                      @csrf @method('DELETE')
                      <button type="submit"
                              class="inline-flex items-center px-3 py-1.5 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">
                        Hapus
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($opnames->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
          {{ $opnames->withQueryString()->links() }}
        </div>
      @endif
    @endif
  </div>

</div>
@endsection