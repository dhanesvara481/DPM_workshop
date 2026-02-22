<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nota {{ $trx->kode_transaksi ?? 'Invoice' }} - DPM Workshop</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink:   #0f172a;
      --ink2:  #334155;
      --ink3:  #64748b;
      --ink4:  #94a3b8;
      --line:  #e2e8f0;
      --green: #059669;
      --bg:    #f8fafc;
    }

    html, body {
      font-family: 'IBM Plex Sans', sans-serif;
      font-size: 10pt;
      color: var(--ink);
      background: #fff;
    }

    /* ── LAYOUT ── */
    /* Nota ukuran 80mm thermal atau A5 — kita pakai A5 agar fleksibel */
    .page {
      width: 148mm;       /* A5 lebar */
      min-height: 210mm;
      margin: 0 auto;
      padding: 10mm 12mm 12mm;
      background: #fff;
    }

    /* ── HEADER ── */
    .doc-header {
      text-align: center;
      padding-bottom: 10px;
      border-bottom: 2px dashed var(--line);
      margin-bottom: 12px;
    }
    .brand-logo {
      width: 38px; height: 38px;
      background: var(--ink);
      border-radius: 10px;
      display: grid; place-items: center;
      margin: 0 auto 6px;
    }
    .brand-logo svg { width: 22px; height: 22px; stroke: #fff; fill: none; }
    .brand-name { font-size: 14pt; font-weight: 700; letter-spacing: -.2px; }
    .brand-sub  { font-size: 7.5pt; color: var(--ink3); margin-top: 1px; }
    .doc-title  {
      margin-top: 8px;
      font-size: 9pt; font-weight: 600; letter-spacing: 2px;
      text-transform: uppercase; color: var(--ink3);
    }

    /* ── KODE + TANGGAL ── */
    .invoice-meta {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px dashed var(--line);
    }
    .meta-label { font-size: 7pt; color: var(--ink4); text-transform: uppercase; letter-spacing: .5px; }
    .meta-val   { font-size: 9.5pt; font-weight: 700; color: var(--ink); margin-top: 1px; font-family: 'IBM Plex Mono', monospace; }
    .meta-right { text-align: right; }

    /* ── PELANGGAN ── */
    .customer-row {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 10px;
      background: var(--bg);
      border-radius: 8px;
      margin-bottom: 12px;
    }
    .avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: var(--ink); color: #fff;
      display: grid; place-items: center;
      font-size: 9pt; font-weight: 700;
      flex-shrink: 0;
    }
    .customer-name { font-weight: 600; font-size: 9.5pt; }
    .customer-sub  { font-size: 7.5pt; color: var(--ink3); }

    /* ── ITEMS TABLE ── */
    table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 10px; }
    thead tr { border-bottom: 1px solid var(--ink); }
    thead th {
      padding: 5px 4px;
      text-align: left;
      font-size: 7.5pt;
      font-weight: 600;
      color: var(--ink2);
      text-transform: uppercase;
      letter-spacing: .4px;
    }
    thead th.r { text-align: right; }

    tbody tr { border-bottom: 1px dashed var(--line); }
    tbody tr:last-child { border-bottom: none; }

    td { padding: 5px 4px; vertical-align: top; }
    td.nama { font-weight: 500; max-width: 72mm; }
    td.qty  { text-align: center; color: var(--ink2); white-space: nowrap; }
    td.harga { text-align: right; font-family: 'IBM Plex Mono', monospace; white-space: nowrap; }
    td.sub   { text-align: right; font-family: 'IBM Plex Mono', monospace; font-weight: 600; white-space: nowrap; }

    /* ── RINGKASAN ── */
    .summary-block {
      border-top: 1px dashed var(--line);
      padding-top: 8px;
      margin-top: 4px;
    }
    .sum-row {
      display: flex;
      justify-content: space-between;
      font-size: 8.5pt;
      padding: 2px 0;
      color: var(--ink2);
    }
    .sum-row.grand {
      border-top: 2px solid var(--ink);
      margin-top: 4px;
      padding-top: 6px;
      font-size: 11pt;
      font-weight: 700;
      color: var(--ink);
    }
    .sum-row.grand .val { color: var(--green); font-family: 'IBM Plex Mono', monospace; }
    .sum-row .val { font-family: 'IBM Plex Mono', monospace; font-weight: 600; }

    /* ── BADGE STATUS ── */
    .status-badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 7.5pt;
      font-weight: 700;
      letter-spacing: .4px;
      text-transform: uppercase;
      background: #ecfdf5;
      color: var(--green);
      border: 1px solid #a7f3d0;
    }

    /* ── FOOTER ── */
    .doc-footer {
      margin-top: 14px;
      padding-top: 10px;
      border-top: 2px dashed var(--line);
      text-align: center;
    }
    .thankyou { font-size: 10pt; font-weight: 700; color: var(--ink); }
    .footer-note { font-size: 7pt; color: var(--ink4); margin-top: 3px; line-height: 1.6; }
    .barcode-placeholder {
      margin: 10px auto 0;
      width: 80px; height: 24px;
      display: flex; align-items: flex-end; gap: 1.5px;
    }
    .barcode-placeholder span {
      display: block;
      background: var(--ink2);
      width: 100%;
      border-radius: 1px;
    }

    /* ── PRINT ── */
    @page { size: A5 portrait; margin: 0; }
    @media print {
      html, body { background: #fff !important; }
      .no-print { display: none !important; }
    }

    /* ── PREVIEW BAR ── */
    .preview-bar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      background: var(--ink); color: #fff;
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 20px; font-size: 12px; gap: 12px;
    }
    .preview-bar span { opacity: .7; }
    .preview-bar .btn-group { display: flex; gap: 8px; }
    .preview-bar button {
      padding: 6px 16px; border-radius: 8px; border: none; cursor: pointer;
      font-size: 12px; font-weight: 600; font-family: inherit;
    }
    .btn-print { background: #fff; color: var(--ink); }
    .btn-close  { background: rgba(255,255,255,.15); color: #fff; }
    .preview-spacer { height: 46px; }
    @media print { .preview-bar, .preview-spacer { display: none !important; } }
  </style>
</head>
<body>

{{-- PREVIEW BAR --}}
<div class="preview-bar no-print">
  <span>Preview Nota · {{ $trx->kode_transaksi ?? 'Invoice' }}</span>
  <div class="btn-group">
    <button class="btn-print" onclick="window.print()">🖨 Cetak</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
  </div>
</div>
<div class="preview-spacer no-print"></div>

@php
  $trx     = $trx ?? null;
  $items   = $items ?? collect();

  $tanggal = $trx?->created_at
    ? \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d F Y')
    : '-';
  $jam = $trx?->created_at
    ? \Carbon\Carbon::parse($trx->created_at)->format('H:i')
    : '-';

  $kode    = $trx->kode_transaksi ?? ('INV-' . ($trx->id ?? '-'));
  $nama    = trim((string)($trx->nama_pengguna ?? $trx->nama_pelanggan ?? 'User'));
  $initials = collect(preg_split('/\s+/', $nama))
                ->filter()->take(2)
                ->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                ->join('');

  $subBarang = (float)($trx->subtotal_barang ?? 0);
  $biayaJasa = (float)($trx->biaya_jasa ?? 0);
  $total     = (float)($trx->total ?? 0);

  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

<div class="page">

  {{-- HEADER --}}
  <div class="doc-header">
    <div class="brand-logo">
      <svg viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h4"/>
      </svg>
    </div>
    <div class="brand-name">DPM Workshop</div>
    <div class="brand-sub">Bengkel & Spare Part</div>
    <div class="doc-title">— Nota Transaksi —</div>
  </div>

  {{-- INVOICE META --}}
  <div class="invoice-meta">
    <div>
      <div class="meta-label">Kode Invoice</div>
      <div class="meta-val">{{ $kode }}</div>
    </div>
    <div class="meta-right">
      <div class="meta-label">Tanggal</div>
      <div class="meta-val" style="font-size:8.5pt">{{ $tanggal }}</div>
      <div style="font-size:7.5pt;color:var(--ink3);margin-top:1px;">{{ $jam }} WIB</div>
    </div>
  </div>

  {{-- PELANGGAN --}}
  <div class="customer-row">
    <div class="avatar">{{ $initials ?: 'U' }}</div>
    <div>
      <div class="customer-name">{{ $nama ?: 'User' }}</div>
      <div class="customer-sub">Pelanggan &nbsp;·&nbsp; <span class="status-badge">PAID</span></div>
    </div>
  </div>

  {{-- ITEMS --}}
  <table>
    <thead>
      <tr>
        <th>Item / Barang</th>
        <th class="r" style="width:30px">Qty</th>
        <th class="r" style="width:44px">Harga</th>
        <th class="r" style="width:52px">Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        @php
          $namaItem = $it->nama_barang ?? $it->deskripsi ?? ($it->barang?->nama_barang ?? '-');
          $harga    = (float)($it->harga ?? 0);
          $qty      = (int)($it->qty ?? $it->jumlah ?? 0);
          $sub      = (float)($it->total ?? ($harga * $qty));

          // Jika harga accessor 0, hitung dari total/qty
          if ($harga == 0 && $qty > 0) {
              $harga = $sub / $qty;
          }
        @endphp
        <tr>
          <td class="nama">
            {{ $namaItem }}
            @if($it->tipe_transaksi ?? false)
              <div style="font-size:7pt;color:var(--ink4);">{{ $it->tipe_transaksi }}</div>
            @endif
          </td>
          <td class="qty">{{ $qty }}</td>
          <td class="harga">{{ number_format((int)$harga, 0, ',', '.') }}</td>
          <td class="sub">{{ number_format((int)$sub, 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:12px 4px;color:var(--ink4);">
            Tidak ada item.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- SUMMARY --}}
  <div class="summary-block">
    @if($subBarang > 0)
      <div class="sum-row">
        <span>Subtotal Barang</span>
        <span class="val">{{ $fmt($subBarang) }}</span>
      </div>
    @endif

    @if($biayaJasa > 0)
      <div class="sum-row">
        <span>Biaya Jasa / Service</span>
        <span class="val">{{ $fmt($biayaJasa) }}</span>
      </div>
    @endif

    <div class="sum-row grand">
      <span>TOTAL</span>
      <span class="val">{{ $fmt($total) }}</span>
    </div>
  </div>

  {{-- FOOTER --}}
  <div class="doc-footer">
    <div class="thankyou">Terima kasih! 🙏</div>
    <div class="footer-note">
      Simpan nota ini sebagai bukti pembayaran.<br>
      Barang yang sudah dibeli tidak dapat dikembalikan.<br>
      © DPM Workshop 2025
    </div>

    {{-- Dekorasi barcode palsu --}}
    <div class="barcode-placeholder" aria-hidden="true">
      @php
        $bars = [2,5,3,7,4,2,6,3,5,4,8,3,5,2,7,4,3,6,4,5,3,7,2,5];
      @endphp
      @foreach($bars as $h)
        <span style="height:{{ $h * 3 }}px;"></span>
      @endforeach
    </div>
    <div style="font-size:7pt;color:var(--ink4);margin-top:3px;font-family:'IBM Plex Mono',monospace;">
      {{ $kode }}
    </div>
  </div>

</div>
</body>
</html>