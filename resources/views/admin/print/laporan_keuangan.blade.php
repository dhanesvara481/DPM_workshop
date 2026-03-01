<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Penjualan - DPM Workshop</title>
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
    .brand-name { font-size: 15pt; font-weight: 700; letter-spacing: -.3px; }
    .brand-sub  { font-size: 8pt; color: var(--ink3); margin-top: 1px; }

    .doc-meta { text-align: right; }
    .doc-title { font-size: 13pt; font-weight: 700; }
    .doc-period {
      margin-top: 4px; display: inline-block;
      background: var(--bg); border: 1px solid var(--line);
      border-radius: 6px; padding: 3px 10px;
      font-size: 8pt; color: var(--ink2);
      font-family: 'IBM Plex Mono', monospace;
    }
    .doc-date  { font-size: 7.5pt; color: var(--ink3); margin-top: 4px; }

    /* ── SUMMARY ── */
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
    .s-card.accent { background: var(--ink); border-color: var(--ink); }
    .s-card.accent .s-label { color: rgba(255,255,255,.6); }
    .s-card.accent .s-value { color: #fff; }
    .s-label { font-size: 7.5pt; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; }
    .s-value { font-size: 13pt; font-weight: 700; color: var(--ink); margin-top: 3px; line-height: 1; }
    .s-value.green { color: var(--green); }

    /* ── TABLE ── */
    table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }

    thead tr { background: var(--ink); color: #fff; }
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

    td { padding: 7px 10px; vertical-align: middle; }
    td.r { text-align: right; }
    td.no  { color: var(--ink4); font-size: 8.5pt; }
    td.kode { font-family: 'IBM Plex Mono', monospace; font-size: 8.5pt; font-weight: 600; color: var(--ink2); }
    td.nama { font-weight: 500; }
    td.tgl  { font-family: 'IBM Plex Mono', monospace; font-size: 8.5pt; color: var(--ink3); }
    td.nominal { text-align: right; font-family: 'IBM Plex Mono', monospace; font-size: 9pt; font-weight: 700; color: var(--green); }

    /* Totals row */
    .tfoot-row { background: #f1f5f9 !important; border-top: 2px solid var(--ink) !important; }
    .tfoot-row td { font-weight: 700; padding: 9px 10px; }
    .tfoot-row td.total-label { text-align: right; font-size: 9pt; color: var(--ink); }
    .tfoot-row td.total-val { text-align: right; font-size: 11pt; color: var(--green); font-family: 'IBM Plex Mono', monospace; }

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
  <span>Preview Cetak · Laporan Penjualan</span>
  <div class="btn-group">
    <button class="btn-print" onclick="window.print()">🖨 Cetak</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
  </div>
</div>
<div class="preview-spacer no-print"></div>

<div class="page">

  @php
    $mode   = $mode   ?? 'custom';
    $dari   = $dari   ?? null;
    $sampai = $sampai ?? null;
    $week   = $week   ?? null;
    $month  = $month  ?? null;
    $year   = $year   ?? null;

    $rowsCol    = collect($rows ?? []);
    $countTrx   = $rowsCol->count();
    $totalMasuk = (int) $rowsCol->sum(fn($x) => (int)($x->total ?? 0));
    $avg        = $countTrx ? (int) round($totalMasuk / $countTrx) : 0;
    $fmt        = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');

    $periodLabel = match($mode) {
        'week'  => 'Minggu ' . ($week ?? '-'),
        'month' => \Carbon\Carbon::parse(($month ?? now()->format('Y-m')) . '-01')->translatedFormat('F Y'),
        'year'  => 'Tahun ' . ($year ?? now()->year),
        default => ($dari && $sampai)
            ? \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') . ' – ' . \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y')
            : 'Semua Data',
    };
  @endphp

  {{-- HEADER --}}
  <div class="doc-header">
    <div class="brand">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">DPM Workshop</div>
        <div class="brand-sub">Laporan Keuangan Penjualan</div>
      </div>
    </div>
    <div class="doc-meta">
      <div class="doc-title">Laporan Penjualan</div>
      <div><span class="doc-period">{{ $periodLabel }}</span></div>
      <div class="doc-date">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
    </div>
  </div>

  {{-- SUMMARY --}}
  <div class="summary">
    <div class="s-card">
      <div class="s-label">Jumlah Transaksi</div>
      <div class="s-value">{{ number_format($countTrx, 0, ',', '.') }}</div>
    </div>
    <div class="s-card accent">
      <div class="s-label">Total Penjualan</div>
      <div class="s-value">{{ $fmt($totalMasuk) }}</div>
    </div>
    <div class="s-card">
      <div class="s-label">Rata-rata / Invoice</div>
      <div class="s-value green">{{ $fmt($avg) }}</div>
    </div>
  </div>

  {{-- TABLE --}}
  <table>
    <thead>
      <tr>
        <th style="width:36px">No</th>
        <th style="width:120px">Kode Invoice</th>
        <th>Pelanggan / User</th>
        <th style="width:120px">Tanggal</th>
        <th class="r" style="width:140px">Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rowsCol as $i => $r)
        @php
          $name = trim((string)($r->nama_pengguna ?? 'User'));
          $initials = collect(preg_split('/\s+/', $name))
            ->filter()->take(2)
            ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
            ->join('');
        @endphp
        <tr>
          <td class="no">{{ $i + 1 }}</td>
          <td class="kode">{{ $r->kode_transaksi ?? ('INV-' . ($r->id ?? '-')) }}</td>
          <td class="nama">{{ $name ?: 'User' }}</td>
          <td class="tgl">
            {{ !empty($r->tanggal_invoice) ? \Carbon\Carbon::parse($r->tanggal_invoice)->format('d/m/Y') : '-' }}
          </td>
          </td>
          <td class="nominal">{{ $fmt($r->total ?? 0) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:24px;color:#94a3b8;">
            Tidak ada data transaksi pada periode ini.
          </td>
        </tr>
      @endforelse

      {{-- TOTAL ROW --}}
      @if($countTrx > 0)
        <tr class="tfoot-row">
          <td colspan="4" class="total-label">TOTAL KESELURUHAN</td>
          <td class="total-val">{{ $fmt($totalMasuk) }}</td>
        </tr>
      @endif
    </tbody>
  </table>

  {{-- FOOTER --}}
  <div class="doc-footer">
    <div class="footer-note">
      © DPM Workshop 2025<br>
      Dokumen ini dicetak otomatis dari sistem laporan penjualan.<br>
      Periode: {{ $periodLabel }}
    </div>
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-label">Mengetahui,</div>
    </div>
  </div>

</div>
</body>
</html>