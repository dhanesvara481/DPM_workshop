@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

<header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20" data-animate>
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
    <div class="min-w-0">
      <h1 class="text-sm font-semibold tracking-tight text-slate-900">Konfirmasi Invoice</h1>
      <p class="text-xs text-slate-500">Invoice dari user yang statusnya <span class="font-semibold">Pending</span> untuk dikonfirmasi menjadi <span class="font-semibold">Paid</span>.</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="/tampilan_notifikasi"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi" aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
      <a href="{{ route('tampilan_dashboard') }}"
         id="btnBackDashboard"
         class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition px-3 py-2 text-sm">
        <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  <div class="max-w-[1280px] mx-auto w-full space-y-6">

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 p-4 text-sm" data-animate>
        {{ session('success') }}
      </div>
    @endif
    @if (session('error'))
      <div class="rounded-2xl border border-rose-200 bg-rose-50 text-rose-800 p-4 text-sm" data-animate>
        {{ session('error') }}
      </div>
    @endif

    {{-- FILTER BAR --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
            shadow-[0_16px_44px_rgba(2,6,23,0.08)] p-4 sm:p-5" data-animate>
      <form method="GET" class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center">
          <div class="relative">
            <input type="text" name="q" value="{{ $q }}"
                  placeholder="Cari no invoice / pelanggan..."
                  class="h-11 w-full sm:w-[320px] rounded-xl border border-slate-200 bg-white px-4 text-sm
                          focus:outline-none focus:ring-2 focus:ring-slate-200">
          </div>

          <input type="date" name="from" value="{{ request('from') }}"
                class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm
                        focus:outline-none focus:ring-2 focus:ring-slate-200">

          <input type="date" name="to" value="{{ request('to') }}"
                class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm
                        focus:outline-none focus:ring-2 focus:ring-slate-200">

          <button type="submit"
                  class="h-11 px-4 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
            Filter
          </button>

          <a href="{{ route('tampilan_konfirmasi_invoice') }}"
            class="inline-flex items-center justify-center h-11 px-6 rounded-xl
                    border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
            Reset
          </a>

          <a href="{{ route('riwayat_transaksi') }}"
            class="inline-flex items-center justify-center h-9 px-3 rounded-lg
                    border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold">
            Lihat Riwayat →
          </a>
        </div>

        <div class="text-xs text-slate-500">
          Total tampil: <span class="font-semibold text-slate-700">{{ $invoices->total() }}</span>
        </div>
      </form>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden" data-animate>

      <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
          <div class="text-sm font-semibold text-slate-900">Daftar Invoice</div>
          <div class="text-xs text-slate-500">Klik "Konfirmasi Pembayaran" untuk konfirmasi pembayaran.</div>
        </div>
        <div class="inline-flex items-center gap-2 text-xs font-semibold rounded-full px-3 py-1
                  border border-amber-200 bg-amber-50 text-amber-800">
          Pending: {{ $pendingCount ?? 0 }}
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-slate-600">
              <th class="px-5 py-3 font-semibold text-left">No Invoice</th>
              <th class="px-5 py-3 font-semibold text-left">Pelanggan</th>
              <th class="px-5 py-3 font-semibold text-left">Total</th>
              <th class="px-5 py-3 font-semibold text-left">Status</th>
              <th class="px-5 py-3 font-semibold text-left">Tanggal</th>
              <th class="px-24 py-3 font-semibold text-right">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            @forelse ($invoices as $inv)
              @php
                $isPending      = $inv->status === 'Pending';
                $nomorInvoice   = 'INV-' . $inv->invoice_id;
                $namaPelanggan  = $inv->items->first()?->nama_pelanggan ?? '-';
              @endphp
              <tr class="hover:bg-slate-50/70">
                <td class="px-5 py-4 font-semibold text-slate-900">
                  {{ $nomorInvoice }}
                </td>
                <td class="px-5 py-4 text-slate-700">
                  {{ $namaPelanggan }}
                </td>
                <td class="px-5 py-4 text-slate-700">
                  Rp {{ number_format((float) $inv->subtotal, 0, ',', '.') }}
                </td>
                <td class="px-5 py-4">
                  @if($isPending)
                    <span class="inline-flex items-center gap-2 text-xs font-semibold rounded-full px-3 py-1
                                 border border-amber-200 bg-amber-50 text-amber-800">
                      <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                      Pending
                    </span>
                  @else
                    <span class="inline-flex items-center gap-2 text-xs font-semibold rounded-full px-3 py-1
                                 border border-emerald-200 bg-emerald-50 text-emerald-800">
                      <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                      Paid
                    </span>
                  @endif
                </td>
                <td class="px-5 py-4 text-slate-600">
                  {{ optional($inv->tanggal_invoice)->format('d M Y') }}
                </td>

                {{-- AKSI --}}
                <td class="px-5 py-4 text-right">
                  <div class="inline-flex items-center gap-2 justify-end">
                    @if($isPending)
                      {{-- Tombol Konfirmasi --}}
                      <button type="button"
                              class="btn-paid inline-flex items-center justify-center h-10 px-4 rounded-xl
                                     bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition"
                              data-action="{{ route('konfirmasi_invoice_tanda_konfirmasi', ['invoice' => $inv->invoice_id]) }}"
                              data-invoice="{{ $nomorInvoice }}">
                        Konfirmasi Pembayaran
                      </button>

                      {{-- Tombol Hapus — hanya muncul saat Pending --}}
                      <button type="button"
                              class="btn-hapus inline-flex items-center justify-center h-10 w-10 rounded-xl
                                     border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                              data-action="{{ route('hapus_konfirmasi_invoice', $inv->invoice_id) }}"
                              data-invoice="{{ $nomorInvoice }}"
                              title="Hapus Invoice">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                          <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                        </svg>
                      </button>
                    @else
                      {{-- Sudah Paid: hanya label, tanpa tombol hapus --}}
                      <span class="inline-flex items-center justify-center h-10 px-4 rounded-xl
                                   border border-slate-200 bg-white text-slate-500 font-semibold">
                        Terkonfirmasi
                      </span>
                    @endif
                  </div>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                  Tidak ada invoice untuk ditampilkan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="px-5 py-4 border-t border-slate-200">
        {{ $invoices->links() }}
      </div>
    </div>

  </div>
</section>

{{-- MODAL KONFIRMASI PAID --}}
<div id="paidModal" class="fixed inset-0 z-[999] hidden">
  <div id="paidModalOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200
                shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">

      <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Konfirmasi Pembayaran</div>
          <div class="text-xs text-slate-500 mt-0.5">Pastikan invoice sudah benar-benar dibayar.</div>
        </div>
        <button id="paidModalClose" type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
          <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="p-5 space-y-4">
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
          <div class="flex items-start gap-3">
            <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-700 grid place-items-center border border-emerald-200">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-sm font-semibold text-emerald-900">Konfirmasi Pembayaran</div>
              <div class="text-xs text-emerald-800 mt-0.5">
                Invoice: <span id="paidModalInvoice" class="font-semibold">—</span>
              </div>
            </div>
          </div>
        </div>

        <div class="text-xs text-slate-500">
          Setelah dikonfirmasi, status invoice akan berubah menjadi <span class="font-semibold text-slate-700">Paid</span>
          dan <span class="font-semibold text-slate-700">tanggal bayar</span> akan dicatat otomatis.
        </div>

        <form id="paidModalForm" method="POST" action="">
          @csrf
          @method('PATCH')
          <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
            <button type="button" id="paidModalCancel"
                    class="h-11 px-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
              Batal
            </button>
            <button type="submit"
                    class="h-11 px-5 rounded-2xl bg-emerald-600 text-white hover:bg-emerald-700 transition text-sm font-semibold">
              Ya, Konfirmasi
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- MODAL HAPUS --}}
<div id="hapusModal" class="fixed inset-0 z-[999] hidden">
  <div id="hapusModalOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200
                shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">

      <div class="p-5 border-b border-slate-200 flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Hapus Invoice</div>
          <div class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</div>
        </div>
        <button id="hapusModalClose" type="button"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
          <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="p-5 space-y-4">
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
          <div class="flex items-start gap-3">
            <div class="h-10 w-10 rounded-xl bg-rose-100 text-rose-600 grid place-items-center border border-rose-200 shrink-0">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-sm font-semibold text-rose-900">Konfirmasi Penghapusan</div>
              <div class="text-xs text-rose-700 mt-0.5">
                Invoice: <span id="hapusModalInvoice" class="font-semibold">—</span>
              </div>
            </div>
          </div>
        </div>

        <p class="text-xs text-slate-500">
          Seluruh data invoice beserta item-itemnya akan
          <span class="font-semibold text-rose-600">dihapus permanen</span>.
          Pastikan Anda sudah yakin sebelum melanjutkan.
        </p>

        <form id="hapusModalForm" method="POST" action="">
          @csrf
          @method('DELETE')
          <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
            <button type="button" id="hapusModalCancel"
                    class="h-11 px-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
              Batal
            </button>
            <button type="submit"
                    class="h-11 px-5 rounded-2xl bg-rose-600 text-white hover:bg-rose-700 transition text-sm font-semibold">
              Ya, Hapus
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // ── Modal Konfirmasi Paid ──────────────────────────────────────────────────
  (function () {
    const modal    = document.getElementById('paidModal');
    const overlay  = document.getElementById('paidModalOverlay');
    const closeBtn = document.getElementById('paidModalClose');
    const cancel   = document.getElementById('paidModalCancel');
    const form     = document.getElementById('paidModalForm');
    const label    = document.getElementById('paidModalInvoice');

    function openModal(actionUrl, invoiceText) {
      form.setAttribute('action', actionUrl);
      label.textContent = invoiceText || '—';
      modal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
      form.setAttribute('action', '');
      label.textContent = '—';
    }

    document.querySelectorAll('.btn-paid').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn.dataset.action, btn.dataset.invoice));
    });

    overlay?.addEventListener('click', closeModal);
    closeBtn?.addEventListener('click', closeModal);
    cancel?.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
  })();

  // ── Modal Hapus ───────────────────────────────────────────────────────────
  (function () {
    const modal    = document.getElementById('hapusModal');
    const overlay  = document.getElementById('hapusModalOverlay');
    const closeBtn = document.getElementById('hapusModalClose');
    const cancel   = document.getElementById('hapusModalCancel');
    const form     = document.getElementById('hapusModalForm');
    const label    = document.getElementById('hapusModalInvoice');

    function openModal(actionUrl, invoiceText) {
      form.setAttribute('action', actionUrl);
      label.textContent = invoiceText || '—';
      modal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
      form.setAttribute('action', '');
      label.textContent = '—';
    }

    document.querySelectorAll('.btn-hapus').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn.dataset.action, btn.dataset.invoice));
    });

    overlay?.addEventListener('click', closeModal);
    closeBtn?.addEventListener('click', closeModal);
    cancel?.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });
  })();
</script>

@endsection