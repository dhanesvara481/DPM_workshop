<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Stok Real-time - DPM Workshop</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink:    #0f172a;
      --ink2:   #334155;
      --ink3:   #64748b;
      --ink4:   #94a3b8;
      --line:   #e2e8f0;
      --green:  #059669;
      --amber:  #d97706;
      --red:    #dc2626;
      --bg:     #f8fafc;
    }

    html, body {
      font-family: 'IBM Plex Sans', sans-serif;
      font-size: 11pt;
      color: var(--ink);
      background: #fff;
    }

    .page {
      width: 210mm;
      min-height: 297mm;
      margin: 0 auto;
      padding: 14mm 16mm 12mm;
      background: #fff;
    }

    /* ── HEADER ── */
    .doc-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--ink);
      margin-bottom: 14px;
    }
    .brand { display: flex; align-items: center; gap: 10px; }
    .brand-icon {
      width: 36px; height: 36px;
      background: var(--ink);
      border-radius: 8px;
      display: grid; place-items: center;
    }
    .brand-icon svg { width: 20px; height: 20px; stroke: #fff; fill: none; }
    .brand-name { font-size: 15pt; font-weight: 700; letter-spacing: -.3px; color: var(--ink); }
    .brand-sub  { font-size: 8pt; color: var(--ink3); margin-top: 1px; }

    .doc-meta { text-align: right; }
    .doc-title { font-size: 13pt; font-weight: 700; color: var(--ink); }
    .doc-date  { font-size: 8pt; color: var(--ink3); margin-top: 3px; font-family: 'IBM Plex Mono', monospace; }

    /* ── SUMMARY BAR ── */
    .summary {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 14px;
    }
    .s-card {
      border: 1px solid var(--line);
      border-radius: 8px;
      padding: 9px 12px;
      background: var(--bg);
    }
    .s-label { font-size: 7.5pt; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; }
    .s-value { font-size: 15pt; font-weight: 700; color: var(--ink); margin-top: 2px; line-height: 1; }
    .s-value.red   { color: var(--red); }
    .s-value.green { color: var(--green); }

    /* ── TABLE ── */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9.5pt;
    }
    thead tr {
      background: var(--ink);
      color: #fff;
    }
    thead th {
      padding: 8px 10px;
      text-align: left;
      font-weight: 600;
      font-size: 8pt;
      letter-spacing: .4px;
      text-transform: uppercase;
    }
    thead th.r { text-align: right; }

    tbody tr { border-bottom: 1px solid var(--line); }
    tbody tr:nth-child(even) { background: var(--bg); }
    tbody tr:last-child { border-bottom: 2px solid var(--ink); }

    td { padding: 7px 10px; vertical-align: middle; }
    td.r { text-align: right; }
    td.no { color: var(--ink4); font-size: 8.5pt; }
    td.kode { font-family: 'IBM Plex Mono', monospace; font-size: 8.5pt; font-weight: 600; color: var(--ink2); }
    td.nama { font-weight: 500; }
    td.stok { font-weight: 700; text-align: right; }
    td.stok.zero   { color: var(--red); }
    td.stok.low    { color: var(--amber); }
    td.stok.ok     { color: var(--green); }
    td.harga { text-align: right; font-family: 'IBM Plex Mono', monospace; font-size: 8.5pt; }

    .badge {
      display: inline-block;
      padding: 2px 7px;
      border-radius: 20px;
      font-size: 7.5pt;
      font-weight: 600;
      letter-spacing: .3px;
      border: 1px solid;
    }
    .badge-ok     { background:#ecfdf5; color:var(--green); border-color:#a7f3d0; }
    .badge-low    { background:#fffbeb; color:var(--amber); border-color:#fcd34d; }
    .badge-empty  { background:#fef2f2; color:var(--red);   border-color:#fca5a5; }

    /* ── FOOTER ── */
    .doc-footer {
      margin-top: 18px;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      padding-top: 10px;
      border-top: 1px solid var(--line);
    }
    .footer-note { font-size: 7.5pt; color: var(--ink4); line-height: 1.6; }
    .sign-box { text-align: center; width: 160px; }
    .sign-line { border-bottom: 1px solid var(--ink2); margin-bottom: 4px; height: 42px; }
    .sign-label { font-size: 8pt; color: var(--ink3); }

    /* ── PRINT ── */
    @page { size: A4 portrait; margin: 0; }
    @media print {
      html, body { background: #fff !important; }
      .page { padding: 14mm 16mm 12mm; }
      .no-print { display: none !important; }
    }

    /* ── SCREEN PREVIEW bar ── */
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

{{-- PREVIEW BAR (layar saja) --}}
<div class="preview-bar no-print">
  <span>Preview Cetak · Stok Real-time</span>
  <div class="btn-group">
    <button class="btn-print" onclick="window.print()">🖨 Cetak</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
  </div>
</div>
<div class="preview-spacer no-print"></div>

<div class="page">

  @php
    $barangCol = collect($barangs ?? []);
    $total     = $barangCol->count();
    $sumStok   = $barangCol->sum(fn($b) => (int)($b->stok ?? 0));
    $habis     = $barangCol->filter(fn($b) => (int)($b->stok ?? 0) <= 0)->count();
    $min       = 5;
    $fmt       = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
  @endphp

  {{-- HEADER --}}
  <div class="doc-header">
    <div class="brand">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">DPM Workshop</div>
        <div class="brand-sub">Sistem Manajemen Stok</div>
      </div>
    </div>
    <div class="doc-meta">
      <div class="doc-title">Laporan Stok Real-time</div>
      <div class="doc-date">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
    </div>
  </div>

  {{-- SUMMARY --}}
  <div class="summary">
    <div class="s-card">
      <div class="s-label">Total Jenis Barang</div>
      <div class="s-value">{{ $total }}</div>
    </div>
    <div class="s-card">
      <div class="s-label">Total Stok Keseluruhan</div>
      <div class="s-value">{{ number_format($sumStok, 0, ',', '.') }}</div>
    </div>
    <div class="s-card">
      <div class="s-label">Stok Habis (= 0)</div>
      <div class="s-value {{ $habis > 0 ? 'red' : 'green' }}">{{ $habis }}</div>
    </div>
  </div>

  {{-- TABLE --}}
  <table>
    <thead>
      <tr>
        <th style="width:38px">No</th>
        <th style="width:120px">Kode</th>
        <th>Nama Barang</th>
        <th style="width:70px">Satuan</th>
        <th class="r" style="width:70px">Stok</th>
        <th style="width:90px">Status</th>
        <th class="r" style="width:120px">Harga Jual</th>
      </tr>
    </thead>
    <tbody>
      @forelse($barangCol as $i => $b)
        @php
          $stok = (int)($b->stok ?? 0);
          if ($stok <= 0)        { $label='Habis';   $badgeCls='badge-empty'; $stokCls='zero'; }
          elseif ($stok <= $min) { $label='Menipis'; $badgeCls='badge-low';   $stokCls='low'; }
          else                   { $label='Aman';    $badgeCls='badge-ok';    $stokCls='ok'; }
        @endphp
        <tr>
          <td class="no">{{ $i + 1 }}</td>
          <td class="kode">{{ $b->kode_barang ?? '-' }}</td>
          <td class="nama">{{ $b->nama_barang ?? '-' }}</td>
          <td>{{ $b->satuan ?? '-' }}</td>
          <td class="stok {{ $stokCls }}">{{ $stok }}</td>
          <td><span class="badge {{ $badgeCls }}">{{ $label }}</span></td>
          <td class="harga">{{ isset($b->harga_jual) ? $fmt($b->harga_jual) : '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:24px;color:#94a3b8;">
            Tidak ada data barang.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- FOOTER --}}
  <div class="doc-footer">
    <div class="footer-note">
      © DPM Workshop 2025<br>
      Dokumen ini dicetak otomatis dari sistem manajemen stok.<br>
      Stok menipis: ≤ {{ $min }} unit &nbsp;|&nbsp; Stok habis: 0 unit
    </div>
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-label">Mengetahui,</div>
    </div>
  </div>

</div>
</body>
</html>