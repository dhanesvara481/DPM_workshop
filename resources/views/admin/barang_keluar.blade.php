@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="relative h-16 bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
  <div class="h-full px-4 sm:px-6 flex items-center justify-between gap-3">

    <div class="flex items-center gap-3 min-w-0">
      {{-- hamburger (mobile) --}}
      <button id="btnSidebar"
              type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
              aria-label="Buka menu">
        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="min-w-0">
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Barang Keluar</h1>
        <p class="text-xs text-slate-500">Catat stok keluar dari barang yang sudah terdaftar.</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('tampilan_notifikasi') }}"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi"
         aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
    </div>

  </div>
</header>

{{-- CONTENT --}}
<section class="relative p-4 sm:p-6">
  <div class="max-w-[1120px] mx-auto w-full space-y-5">

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-5 py-4">
        <p class="text-sm font-semibold">Berhasil</p>
        <p class="text-sm">{{ session('success') }}</p>
      </div>
    @endif

    @if($errors->any())
      <div class="rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4">
        <p class="text-sm font-semibold">Gagal</p>
        <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- FORM CARD --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
          <div>
            <h2 class="text-base font-semibold text-slate-900">Input Stok Keluar</h2>
            <p class="mt-1 text-sm text-slate-500">
              Pilih barang dari data yang sudah diinput di "Kelola Barang" untuk dicatat keluar.
            </p>
          </div>
          <span class="inline-flex self-start items-center rounded-full border border-slate-200 bg-slate-50
                      px-3 py-1 text-xs text-slate-600">
            Form Barang Keluar
          </span>
        </div>
      </div>

      <form id="formKeluar"
            action="{{ route('simpan_barang_keluar') }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-6 py-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

          {{-- Kode Barang --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Kode Barang</label>
            <select name="barang_id" id="kodeBarangSelect"
                    class="w-full px-3 py-2.5 rounded-lg border {{ $errors->has('barang_id') ? 'border-red-300' : 'border-slate-200' }}
                           bg-white/90 text-sm focus:outline-none focus:ring-4 focus:ring-blue-900/10
                           focus:border-blue-900/30 transition">
              <option value="">-- Pilih Kode Barang --</option>
              @foreach($barangs as $b)
                <option value="{{ $b->barang_id }}"
                        data-kode="{{ $b->kode_barang }}"
                        data-nama="{{ $b->nama_barang }}"
                        data-satuan="{{ $b->satuan }}"
                        data-stok="{{ $b->stok }}"
                        {{ old('barang_id') == $b->barang_id ? 'selected' : '' }}>
                  {{ $b->kode_barang }}
                </option>
              @endforeach
            </select>
            @error('barang_id')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-slate-500">Pilih kode barang yang sudah terdaftar</p>
          </div>

          {{-- Stok Tersedia --}}
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Stok Tersedia</label>
            <input type="text" id="stokTersedia" readonly placeholder="-"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm
                          text-slate-700 focus:outline-none font-semibold tracking-tight text-center">
            <p class="mt-2 text-xs text-slate-500">Otomatis</p>
          </div>

          {{-- Tanggal --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal</label>
            <input type="date" name="tanggal"
                   value="{{ date('Y-m-d') }}"
                   readonly
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm
                          text-slate-500 cursor-not-allowed focus:outline-none transition">
            @error('tanggal')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Nama Barang --}}
          <div class="md:col-span-7">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Barang</label>
            <input type="text" id="namaBarang" readonly placeholder="Akan terisi otomatis"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm
                          text-slate-700 focus:outline-none">
          </div>

          {{-- Qty Keluar --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Jumlah Stok Keluar</label>
            <input type="number" min="1" name="qty_keluar" id="qtyKeluar"
                   value="{{ old('qty_keluar') }}"
                   placeholder="Masukkan jumlah keluar"
                   class="w-full px-3 py-2.5 rounded-lg border {{ $errors->has('qty_keluar') ? 'border-red-300' : 'border-slate-200' }}
                          bg-white/90 text-sm focus:outline-none focus:ring-4 focus:ring-blue-900/10
                          focus:border-blue-900/30 transition">
            @error('qty_keluar')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-slate-500">Jumlah keluar tidak boleh lebih dari stok saat ini.</p>
          </div>

          {{-- Satuan --}}
          <div class="md:col-span-7">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Satuan</label>
            <input type="text" id="satuanBarang" readonly placeholder="Akan terisi otomatis"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-sm
                          text-slate-700 focus:outline-none">
          </div>

          {{-- Keterangan --}}
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Keterangan</label>
            <select name="keterangan"
                    class="w-full px-3 py-2.5 rounded-lg border {{ $errors->has('keterangan') ? 'border-red-300' : 'border-slate-200' }}
                           bg-white/90 text-sm focus:outline-none focus:ring-4 focus:ring-blue-900/10
                           focus:border-blue-900/30 transition">
              <option value="">-- Pilih Keterangan --</option>
              <option value="Barang Rusak"         {{ old('keterangan') == 'Barang Rusak'         ? 'selected' : '' }}>Barang Rusak</option>
              <option value="Barang Dikembalikan"  {{ old('keterangan') == 'Barang Dikembalikan'  ? 'selected' : '' }}>Barang Dikembalikan</option>
              <option value="Penyesuaian Stok"     {{ old('keterangan') == 'Penyesuaian Stok'     ? 'selected' : '' }}>Penyesuaian Stok</option>
            </select>
            @error('keterangan')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Foto Bukti --}}
          <div class="md:col-span-7">
            <label class="block text-sm font-semibold text-slate-800 mb-2">
              Foto Bukti
            </label>

            {{-- Drop zone --}}
            <div id="dropZone"
                 class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed
                        border-slate-200 bg-slate-50/60 px-4 py-5 text-center cursor-pointer
                        hover:border-blue-900/30 hover:bg-blue-50/40 transition group
                        {{ $errors->has('foto_bukti') ? 'border-red-300 bg-red-50/40' : '' }}">

              {{-- Preview (hidden by default) --}}
              <div id="previewWrap" class="hidden w-full">
                <div class="relative inline-block">
                  <img id="previewImg"
                       src=""
                       alt="Preview"
                       class="mx-auto max-h-36 rounded-lg object-cover shadow-sm border border-slate-200">
                  <button type="button" id="btnHapusFoto"
                          class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 hover:bg-red-600
                                 text-white grid place-items-center shadow-md transition"
                          aria-label="Hapus foto">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
                <p id="previewName" class="mt-2 text-xs text-slate-500 truncate max-w-[200px] mx-auto"></p>
              </div>

              {{-- Placeholder (shown when no file) --}}
              <div id="dropPlaceholder" class="flex flex-col items-center gap-2">
                <div class="h-10 w-10 rounded-xl bg-white border border-slate-200 grid place-items-center
                            group-hover:border-blue-900/20 transition shadow-sm">
                  <svg class="h-5 w-5 text-slate-400 group-hover:text-blue-900/50 transition"
                       fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159
                             m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909
                             M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0017.25
                             4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-slate-700">
                    <span class="text-blue-900 underline underline-offset-2">Klik untuk upload</span>
                    atau drag &amp; drop
                  </p>
                  <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, WEBP · maks. 20 MB</p>
                </div>
              </div>

              {{-- Input file tersembunyi --}}
              <input type="file"
                     name="foto_bukti"
                     id="inputFotoBukti"
                     accept="image/jpeg,image/png,image/webp"
                     class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" required>
            </div>

            @error('foto_bukti')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-slate-500">Upload foto kondisi barang sebagai dokumentasi keluar.</p>
          </div>

        </div>

        <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
          <div class="text-xs text-slate-500">
            Pastikan barang sudah ada di menu <span class="font-semibold text-slate-700">Kelola Barang</span>.
          </div>

          <div class="flex gap-2">
            <a id="btnKembaliKeluar" href="{{ route('mengelola_barang') }}"
               class="tip inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white hover:bg-slate-50 transition">
              Kembali
            </a>

            <button type="button" id="btnResetKeluar"
                    class="tip inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                           border border-slate-200 bg-white hover:bg-slate-50 transition">
              Reset
            </button>

            <button type="submit" id="btnSimpanKeluar"
                    class="tip btn-shine inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5
                           text-sm font-semibold bg-blue-950 text-white hover:bg-blue-900 transition
                           shadow-[0_12px_24px_rgba(2,6,23,0.16)]">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5l7 7-7 7"/>
              </svg>
              Simpan
            </button>
          </div>
        </div>
      </form>
    </div>

    {{-- TABLE RIWAYAT --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-slate-200
                shadow-[0_18px_48px_rgba(2,6,23,0.10)] overflow-hidden">

      <div class="px-6 py-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div>
            <h2 class="text-base font-semibold text-slate-900">Riwayat Barang Keluar</h2>
            <p class="text-sm text-slate-500">Daftar transaksi stok keluar terbaru.</p>
          </div>

          <div class="w-full sm:w-[380px]">
            <div class="relative flex items-center gap-2">
              <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                  </svg>
                </span>
                <input id="searchKeluar" type="text"
                       value="{{ request('search') }}"
                       placeholder="Cari kode / nama barang..."
                       class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-200 bg-white/90
                              text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900/30 transition">
              </div>

              @if(request('search'))
                <a href="{{ route('barang_keluar', array_filter(['sort' => request('sort'), 'dir' => request('dir')])) }}"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-2.5 text-xs font-semibold
                          border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition whitespace-nowrap">
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Reset
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm" id="tableKeluar">
          @php
            $colsKeluar = [
              'tanggal_keluar' => ['label' => 'Tanggal',    'align' => 'text-left'],
              'kode_barang'    => ['label' => 'Kode',        'align' => 'text-left'],
              'nama_barang'    => ['label' => 'Nama',        'align' => 'text-left'],
              'keterangan'     => ['label' => 'Keterangan',  'align' => 'text-left'],
              'jumlah_keluar'  => ['label' => 'Qty',         'align' => 'text-right'],
            ];
          @endphp

          <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur">
            <tr class="text-slate-600">
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide text-left w-[50px]">No</th>

              @foreach ($colsKeluar as $key => $col)
                @php
                  $isActive = $sort === $key;
                  $nextDir  = ($isActive && $dir === 'asc') ? 'desc' : 'asc';
                  $url      = request()->fullUrlWithQuery(['sort' => $key, 'dir' => $nextDir, 'page' => 1]);
                @endphp
                <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide {{ $col['align'] }}">
                  <a href="{{ $url }}"
                     class="inline-flex items-center gap-1.5 group {{ $isActive ? 'text-blue-900' : 'text-slate-600 hover:text-slate-900' }} transition">
                    {{ $col['label'] }}
                    <span class="flex flex-col gap-[2px]">
                      <svg class="h-2.5 w-2.5 {{ $isActive && $dir === 'asc' ? 'text-blue-900' : 'text-slate-300 group-hover:text-slate-400' }}" viewBox="0 0 10 6" fill="currentColor"><path d="M5 0L10 6H0L5 0Z"/></svg>
                      <svg class="h-2.5 w-2.5 {{ $isActive && $dir === 'desc' ? 'text-blue-900' : 'text-slate-300 group-hover:text-slate-400' }}" viewBox="0 0 10 6" fill="currentColor"><path d="M5 6L0 0H10L5 6Z"/></svg>
                    </span>
                  </a>
                </th>
              @endforeach

              {{-- Kolom Foto --}}
              <th class="px-5 py-4 font-semibold text-xs uppercase tracking-wide text-center w-[80px]">Foto</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
          @forelse($barangKeluar as $i => $k)
            @php
              $fotoUrl = $k->foto_bukti ? asset('storage/' . $k->foto_bukti) : null;
            @endphp
            <tr class="row-lift hover:bg-slate-50/70 transition"
                data-row-text="{{ strtolower(($k->kode_barang ?? '').' '.($k->nama_barang ?? '').' '.($k->keterangan ?? '')) }}">
              <td class="px-5 py-4 text-slate-600">{{ $barangKeluar->firstItem() + $loop->index }}</td>
              <td class="px-5 py-4 text-slate-700 whitespace-nowrap">{{ $k->tanggal_keluar ?? '-' }}</td>
              <td class="px-5 py-4 font-semibold text-slate-900">{{ $k->kode_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-slate-700">{{ $k->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-slate-700">{{ $k->keterangan ?? '-' }}</td>
              <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ $k->qty_keluar ?? 0 }}</td>

              {{-- Foto Bukti --}}
              <td class="px-5 py-4 text-center">
                @if($k->ref_invoice)
                  {{-- Dari Invoice --}}
                  <span class="tip inline-flex items-center justify-center h-10 w-10 rounded-lg
                               border border-violet-200 bg-violet-50 text-violet-600"
                        data-tip="Invoice: {{ $k->ref_invoice }}">
                    <i class="fa-solid fa-file-invoice text-base"></i>
                  </span>
                @elseif($fotoUrl)
                  {{-- Ada foto bukti --}}
                  <button type="button"
                          class="btn-lihat-foto group relative inline-block"
                          data-src="{{ $fotoUrl }}"
                          aria-label="Lihat foto bukti">
                    <img src="{{ $fotoUrl }}"
                         alt="Bukti"
                         class="h-10 w-10 rounded-lg object-cover border border-slate-200
                                shadow-sm group-hover:opacity-80 transition">
                    <span class="absolute inset-0 rounded-lg bg-slate-900/0 group-hover:bg-slate-900/20
                                 transition flex items-center justify-center">
                      <svg class="h-4 w-4 text-white opacity-0 group-hover:opacity-100 transition"
                           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                      </svg>
                    </span>
                  </button>
                @else
                  {{-- Tidak ada bukti --}}
                  <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg
                               border border-dashed border-slate-200 bg-slate-50">
                    <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159
                               m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909
                               M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0017.25
                               4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                  </span>
                @endif
              </td>
            </tr>
          @empty
            @for($r = 1; $r <= 3; $r++)
              <tr class="row-lift hover:bg-slate-50/70 transition">
                <td class="px-5 py-5 text-slate-400">{{ $r }}</td>
                <td class="px-5 py-5"><div class="h-4 w-28 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-4 w-20 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-4 w-52 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-4 w-40 rounded bg-slate-100"></div></td>
                <td class="px-5 py-5 text-right"><div class="h-4 w-16 ml-auto rounded bg-slate-100"></div></td>
                <td class="px-5 py-5"><div class="h-10 w-10 rounded-lg bg-slate-100 mx-auto"></div></td>
              </tr>
            @endfor
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- PAGINATION --}}
      @if ($barangKeluar->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between gap-4 flex-wrap">
          <p class="text-xs text-slate-500">
            Menampilkan {{ $barangKeluar->firstItem() }}–{{ $barangKeluar->lastItem() }}
            dari {{ $barangKeluar->total() }} transaksi
          </p>
          <nav class="flex items-center gap-1">
            @if ($barangKeluar->onFirstPage())
              <span class="h-9 w-9 grid place-items-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
              </span>
            @else
              <a href="{{ $barangKeluar->previousPageUrl() }}" class="h-9 w-9 grid place-items-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
              </a>
            @endif

            @foreach ($barangKeluar->getUrlRange(max(1, $barangKeluar->currentPage()-2), min($barangKeluar->lastPage(), $barangKeluar->currentPage()+2)) as $page => $url)
              @if ($page == $barangKeluar->currentPage())
                <span class="h-9 w-9 grid place-items-center rounded-lg bg-blue-950 text-white text-xs font-semibold">{{ $page }}</span>
              @else
                <a href="{{ $url }}" class="h-9 w-9 grid place-items-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">{{ $page }}</a>
              @endif
            @endforeach

            @if ($barangKeluar->hasMorePages())
              <a href="{{ $barangKeluar->nextPageUrl() }}" class="h-9 w-9 grid place-items-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </a>
            @else
              <span class="h-9 w-9 grid place-items-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </span>
            @endif
          </nav>
        </div>
      @endif

      <div class="px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
        © DPM Workshop 2025
      </div>
    </div>

  </div>
</section>

{{-- ===== LIGHTBOX MODAL ===== --}}
<div id="lightbox"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4"
     role="dialog" aria-modal="true" aria-label="Lihat foto bukti">

  <div class="relative max-w-3xl w-full">
    {{-- Close --}}
    <button id="btnCloseLightbox"
            type="button"
            class="absolute -top-12 right-0 h-10 w-10 rounded-xl bg-white/10 hover:bg-white/20
                   text-white grid place-items-center transition"
            aria-label="Tutup">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <img id="lightboxImg"
         src=""
         alt="Foto bukti barang keluar"
         class="w-full max-h-[80vh] object-contain rounded-2xl shadow-[0_40px_80px_rgba(0,0,0,0.5)]">

    {{-- Download --}}
    <a id="lightboxDownload"
       href=""
       download
       target="_blank"
       class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/20
              text-white text-sm font-medium px-4 py-2.5 transition">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
      </svg>
      Unduh Foto
    </a>
  </div>
</div>

{{-- Toast --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-50 hidden w-[340px] rounded-2xl border border-slate-200
            bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]">
  <div class="flex items-start gap-3">
    <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
    <div class="min-w-0">
      <p id="toastTitle" class="text-sm font-semibold text-slate-900">Berhasil</p>
      <p id="toastMsg"   class="text-xs text-slate-600 mt-0.5">Aksi berhasil.</p>
    </div>
    <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition"
            type="button" aria-label="Close">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>

@push('head')
<style>
  @media (prefers-reduced-motion: reduce) {
    .row-lift, .btn-shine { animation: none !important; transition: none !important; }
  }

  .row-lift { transform: translateY(0); transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease; }
  .row-lift:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(2,6,23,0.06); }

  .btn-shine { position: relative; overflow: hidden; }
  .btn-shine::after {
    content: "";
    position: absolute;
    inset: 0;
    transform: translateX(-120%);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transition: transform .65s ease;
  }
  .btn-shine:hover::after { transform: translateX(120%); }

  /* tooltip */
  .tip { position: relative; }
  .tip[data-tip]::after {
    content: attr(data-tip);
    position: absolute;
    right: 50%;
    top: calc(100% + 10px);
    background: rgba(15,23,42,.92);
    color: rgba(255,255,255,.92);
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 10px;
    white-space: nowrap;
    opacity: 0;
    transform: translate(50%, -4px);
    pointer-events: none;
    transition: .15s ease;
  }
  .tip:hover::after { opacity: 1; transform: translate(50%, 0); }

  /* shake */
  @keyframes shake {
    0%   { transform: translateX(0) }
    25%  { transform: translateX(-6px) }
    50%  { transform: translateX(6px) }
    75%  { transform: translateX(-4px) }
    100% { transform: translateX(0) }
  }
  .shake { animation: shake .28s ease; }

  /* lightbox fade */
  #lightbox { transition: opacity .2s ease; }
  #lightbox.show { display: flex !important; }

  /* drop zone drag-over */
  #dropZone.drag-over {
    border-color: rgb(30 58 138 / 0.4);
    background-color: rgb(239 246 255 / 0.6);
  }
</style>
@endpush

@push('scripts')
<script>
  // ===== Toast =====
  const toastEl    = document.getElementById('toast');
  const toastTitle = document.getElementById('toastTitle');
  const toastMsg   = document.getElementById('toastMsg');
  const toastDot   = document.getElementById('toastDot');
  const toastClose = document.getElementById('toastClose');
  let toastTimer   = null;

  const showToast = (title, msg, type = 'success') => {
    if (!toastEl) return;
    toastTitle.textContent = title;
    toastMsg.textContent   = msg;
    toastDot.className     = "mt-1 h-2.5 w-2.5 rounded-full " + (type === 'success' ? "bg-emerald-500" : "bg-red-500");
    toastEl.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toastEl.classList.add('hidden'), 2600);
  };
  toastClose?.addEventListener('click', () => toastEl.classList.add('hidden'));

  @if(session('success'))
    showToast('Berhasil', @json(session('success')), 'success');
  @endif
  @if($errors->any())
    showToast('Gagal', @json($errors->first()), 'error');
  @endif

  // ===== Confirm Modal =====
  function showConfirmModal({ title, message, confirmText, cancelText, note, tone = "neutral", onConfirm }) {
    const toneMap = {
      neutral: { btn: "bg-slate-900 hover:bg-slate-800", noteBg: "bg-slate-50",  noteBr: "border-slate-200", noteTx: "text-slate-600" },
      danger:  { btn: "bg-rose-600 hover:bg-rose-700",   noteBg: "bg-rose-50",   noteBr: "border-rose-200",  noteTx: "text-rose-700"  },
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
          <div class="rounded-xl border ${t.noteBr} ${t.noteBg} p-4 text-xs ${t.noteTx}">${note || ''}</div>
          <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="btn-cancel h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold">${cancelText}</button>
            <button type="button" class="btn-ok h-10 px-5 rounded-xl ${t.btn} text-white text-sm font-semibold">${confirmText}</button>
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

  // ===== Elements =====
  const form         = document.getElementById('formKeluar');
  const kodeSelect   = document.getElementById('kodeBarangSelect');
  const namaBarang   = document.getElementById('namaBarang');
  const satuanBarang = document.getElementById('satuanBarang');
  const stokTersedia = document.getElementById('stokTersedia');
  const qtyInput     = document.getElementById('qtyKeluar');
  const btnReset     = document.getElementById('btnResetKeluar');
  const btnKembali   = document.getElementById('btnKembaliKeluar');

  // ===== Barang select sync =====
  const getStokSelected = () => {
    const opt = kodeSelect?.options?.[kodeSelect.selectedIndex];
    return parseInt(opt?.dataset?.stok || '0', 10) || 0;
  };

  const syncBarangFields = () => {
    if (!kodeSelect) return;
    const opt    = kodeSelect.options[kodeSelect.selectedIndex];
    const nama   = opt?.dataset?.nama   || '';
    const satuan = opt?.dataset?.satuan || '';
    const stok   = getStokSelected();

    if (namaBarang)   namaBarang.value   = nama;
    if (satuanBarang) satuanBarang.value = satuan;
    if (stokTersedia) stokTersedia.value = stok > 0 ? String(stok) : '';

    if (qtyInput) {
      qtyInput.max = stok > 0 ? String(stok) : "";
      const cur = parseInt(qtyInput.value || '0', 10) || 0;
      if (stok > 0 && cur > stok) qtyInput.value = stok;
    }
  };

  kodeSelect?.addEventListener('change', syncBarangFields);
  syncBarangFields();

  // ===== Foto Bukti: preview & drag-drop =====
  const inputFoto      = document.getElementById('inputFotoBukti');
  const dropZone       = document.getElementById('dropZone');
  const previewWrap    = document.getElementById('previewWrap');
  const previewImg     = document.getElementById('previewImg');
  const previewName    = document.getElementById('previewName');
  const dropPlaceholder= document.getElementById('dropPlaceholder');
  const btnHapusFoto   = document.getElementById('btnHapusFoto');

  const MAX_SIZE_MB    = 2;
  const ALLOWED_TYPES  = ['image/jpeg', 'image/png', 'image/webp'];

  const showPreview = (file) => {
    if (!file) return;

    // Validasi tipe
    if (!ALLOWED_TYPES.includes(file.type)) {
      showToast('Gagal', 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.', 'error');
      clearFoto();
      return;
    }

    // Validasi ukuran
    if (file.size > MAX_SIZE_MB * 1024 * 1024) {
      showToast('Gagal', `Ukuran file terlalu besar. Maksimal ${MAX_SIZE_MB} MB.`, 'error');
      clearFoto();
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      previewImg.src      = e.target.result;
      previewName.textContent = file.name;
      previewWrap?.classList.remove('hidden');
      dropPlaceholder?.classList.add('hidden');
    };
    reader.readAsDataURL(file);
  };

  const clearFoto = () => {
    if (inputFoto) inputFoto.value = '';
    if (previewImg)  previewImg.src = '';
    if (previewName) previewName.textContent = '';
    previewWrap?.classList.add('hidden');
    dropPlaceholder?.classList.remove('hidden');
  };

  inputFoto?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (file) showPreview(file);
    else clearFoto();
  });

  btnHapusFoto?.addEventListener('click', (e) => {
    e.stopPropagation();
    clearFoto();
  });

  // Drag & drop
  dropZone?.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('drag-over');
  });

  dropZone?.addEventListener('dragleave', () => {
    dropZone.classList.remove('drag-over');
  });

  dropZone?.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const file = e.dataTransfer?.files?.[0];
    if (!file) return;

    // Inject file ke input
    const dt = new DataTransfer();
    dt.items.add(file);
    inputFoto.files = dt.files;
    showPreview(file);
  });

  // ===== Lightbox =====
  const lightbox         = document.getElementById('lightbox');
  const lightboxImg      = document.getElementById('lightboxImg');
  const lightboxDownload = document.getElementById('lightboxDownload');
  const btnCloseLightbox = document.getElementById('btnCloseLightbox');

  const openLightbox = (src) => {
    lightboxImg.src         = src;
    lightboxDownload.href   = src;
    lightbox.classList.add('show');
    document.body.style.overflow = 'hidden';
  };

  const closeLightbox = () => {
    lightbox.classList.remove('show');
    document.body.style.overflow = '';
    setTimeout(() => { lightboxImg.src = ''; }, 200);
  };

  document.querySelectorAll('.btn-lihat-foto').forEach(btn => {
    btn.addEventListener('click', () => openLightbox(btn.dataset.src));
  });

  btnCloseLightbox?.addEventListener('click', closeLightbox);
  lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && lightbox?.classList.contains('show')) closeLightbox();
  });

  // ===== Search table =====
  const inputKeluar = document.getElementById('searchKeluar');

  const applySearch = () => {
    const q = (inputKeluar?.value || '').trim().toLowerCase();
    document.querySelectorAll('#tableKeluar tbody tr[data-row-text]').forEach(tr => {
      tr.classList.toggle('hidden', !!q && !tr.dataset.rowText.includes(q));
    });
  };

  inputKeluar?.addEventListener('input', applySearch);
  applySearch();

  // ===== Dirty guard =====
  let isDirty = false;
  form?.querySelectorAll('input:not([readonly]), select, textarea').forEach(el => {
    el.addEventListener('input',  () => { isDirty = true; });
    el.addEventListener('change', () => { isDirty = true; });
  });

  // ── Reset ────────────────────────────────────────────────────────────────────
  btnReset?.addEventListener('click', () => {
    if (!form) return;

    const doReset = () => {
      form.reset();
      const dateInput = form.querySelector('input[name="tanggal"]');
      if (dateInput) dateInput.value = new Date().toISOString().slice(0, 10);
      clearFoto();
      syncBarangFields();
      isDirty = false;
      showToast('Reset', 'Form dikosongkan.', 'success');
    };

    if (!isDirty) { doReset(); return; }

    showConfirmModal({
      title:       "Reset form?",
      message:     "Semua input yang sudah diisi akan dikosongkan.",
      confirmText: "Ya, Reset",
      cancelText:  "Batal",
      note:        "Kalau kamu yakin mau mulai ulang, klik \"Ya, Reset\".",
      tone:        "danger",
      onConfirm:   doReset,
    });
  });

  // ── Kembali ──────────────────────────────────────────────────────────────────
  btnKembali?.addEventListener('click', e => {
    if (!isDirty) return;
    e.preventDefault();
    const go = e.currentTarget.getAttribute('href');
    showConfirmModal({
      title:       "Keluar dari halaman?",
      message:     "Perubahan belum disimpan. Kalau keluar sekarang, data yang sudah diisi akan hilang.",
      confirmText: "Ya, Keluar",
      cancelText:  "Tetap di sini",
      note:        "Klik \"Tetap di sini\" kalau masih mau lanjut isi form.",
      onConfirm:   () => { window.location.href = go; },
    });
  });

  // ── Submit ────────────────────────────────────────────────────────────────────
  form?.addEventListener('submit', e => {
    if (form.dataset.confirmed === "1") return;
    e.preventDefault();

    const barangId = kodeSelect?.value || '';
    const qty      = parseInt(qtyInput?.value || '0', 10) || 0;
    const stok     = getStokSelected();

    if (!barangId) {
      kodeSelect?.classList.add('border-red-300', 'shake');
      setTimeout(() => kodeSelect?.classList.remove('shake'), 300);
      showToast('Gagal', 'Pilih kode barang terlebih dahulu.', 'error');
      return;
    }
    kodeSelect?.classList.remove('border-red-300');

    if (qty <= 0) {
      qtyInput?.classList.add('border-red-300', 'shake');
      setTimeout(() => qtyInput?.classList.remove('shake'), 300);
      showToast('Gagal', 'Jumlah keluar minimal 1.', 'error');
      return;
    }
    qtyInput?.classList.remove('border-red-300');

    if (stok <= 0) {
      showToast('Gagal', 'Stok barang kosong. Tidak bisa keluar.', 'error');
      return;
    }

    if (qty > stok) {
      qtyInput?.classList.add('border-red-300', 'shake');
      setTimeout(() => qtyInput?.classList.remove('shake'), 300);
      showToast('Gagal', `Jumlah keluar (${qty}) melebihi stok tersedia (${stok}).`, 'error');
      return;
    }

    const adaFoto = inputFoto?.files?.length > 0;

    showConfirmModal({
      title:       "Simpan transaksi keluar?",
      message:     "Stok barang akan berkurang sesuai jumlah keluar.",
      confirmText: "Ya, Simpan",
      cancelText:  "Batal",
      note:        adaFoto
        ? "Foto bukti akan disimpan bersama transaksi ini."
        : "Tidak ada foto bukti yang dilampirkan.",
      onConfirm:   () => {
        form.dataset.confirmed = "1";
        isDirty = false;
        form.submit();
      },
    });
  });

  // Prevent date input manipulation
  document.querySelectorAll('input[type="date"][readonly]').forEach(el => {
    el.addEventListener('keydown', e => e.preventDefault());
    el.addEventListener('mousedown', e => e.preventDefault());
  });
</script>
@endpush

@endsection