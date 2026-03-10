<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Laporan Stok Real-time - DPM Workshop</title>

<style>
  *{box-sizing:border-box}

  @media print {
    .no-print { display:none !important; }
    body {
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      background:#fff;
    }

    .page-wrap{
      max-width:none !important;
      padding:20px 0 !important;
    }

    .table-wrap{
      overflow:visible !important;
    }
  }

  body{
    margin:0;
    font-family:sans-serif;
    background:#fff;
    color:#0f172a;
  }

  .topbar-wrap{
    max-width:900px;
    margin:16px auto 0;
    padding:0 16px;
  }

  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
  }

  .topbar-left,
  .topbar-right{
    display:flex;
    align-items:center;
    gap:10px;
  }

  .btn{
    appearance:none;
    border:none;
    outline:none;
    cursor:pointer;
    padding:10px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.2s ease;
    white-space:nowrap;
  }

  .btn-back{
    border:1px solid #e2e8f0;
    background:#fff;
    color:#0f172a;
  }

  .btn-back:hover{
    background:#f8fafc;
  }

  .btn-print{
    background:#0f172a;
    color:#fff;
  }

  .btn-print:hover{
    opacity:.92;
  }

  .page-wrap{
    max-width:900px;
    margin:0 auto;
    padding:32px 16px;
  }

  .header{
    text-align:center;
    margin-bottom:24px;
  }

  .header-title{
    font-size:20px;
    font-weight:800;
    line-height:1.3;
  }

  .header-subtitle{
    font-size:13px;
    color:#64748b;
    margin-top:4px;
  }

  .header-date{
    font-size:12px;
    color:#94a3b8;
    margin-top:2px;
  }

  .summary-grid{
    display:grid;
    grid-template-columns:repeat(5, 1fr);
    gap:12px;
    margin-bottom:24px;
  }

  .summary-card{
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:14px;
    min-width:0;
  }

  .summary-card.neutral{ background:#f8fafc; }
  .summary-card.safe{ background:#d1fae5; }
  .summary-card.warn{ background:#fef3c7; }
  .summary-card.danger{ background:#fee2e2; }

  .summary-label{
    font-size:11px;
    color:#64748b;
    margin-bottom:4px;
  }

  .summary-label.safe{ color:#065f46; }
  .summary-label.warn{ color:#92400e; }
  .summary-label.danger{ color:#991b1b; }

  .summary-value{
    font-size:22px;
    font-weight:800;
    line-height:1.2;
    color:#0f172a;
  }

  .summary-value.safe{ color:#065f46; }
  .summary-value.warn{ color:#92400e; }
  .summary-value.danger{ color:#991b1b; }

  .table-wrap{
    width:100%;
    overflow-x:auto;
    border:1px solid #e2e8f0;
    border-radius:14px;
  }

  table{
    width:100%;
    min-width:720px;
    border-collapse:collapse;
    font-size:12px;
    background:#fff;
  }

  thead th{
    background:#f8fafc;
    border-bottom:2px solid #e2e8f0;
    padding:10px 12px;
    text-align:left;
    font-weight:700;
    color:#475569;
    white-space:nowrap;
  }

  tbody td{
    padding:9px 12px;
    border-bottom:1px solid #e2e8f0;
    color:#334155;
    vertical-align:middle;
  }

  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:#f8fafc; }

  .badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:3px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
    border:1px solid transparent;
    white-space:nowrap;
  }

  .badge-aman{
    background:#d1fae5;
    color:#065f46;
    border-color:#a7f3d0;
  }

  .badge-menipis{
    background:#fef3c7;
    color:#92400e;
    border-color:#fde68a;
  }

  .badge-habis{
    background:#fee2e2;
    color:#991b1b;
    border-color:#fca5a5;
  }

  .text-right{text-align:right}
  .text-center{text-align:center}
  .font-bold{font-weight:700}

  .footer{
    margin-top:24px;
    padding-top:16px;
    border-top:1px solid #e2e8f0;
    font-size:11px;
    color:#94a3b8;
    text-align:center;
  }

  @media (max-width: 900px){
    .summary-grid{
      grid-template-columns:repeat(2, 1fr);
    }
  }

  @media (max-width: 640px){
    .topbar-wrap{
      padding:0 12px;
    }

    .topbar{
      align-items:stretch;
    }

    .topbar-left,
    .topbar-right{
      width:100%;
    }

    .topbar-left .btn,
    .topbar-right .btn{
      width:100%;
      justify-content:center;
    }

    .page-wrap{
      padding:24px 12px;
    }

    .header-title{
      font-size:18px;
    }

    .summary-grid{
      grid-template-columns:1fr;
      gap:10px;
    }

    .summary-card{
      padding:12px;
    }

    .summary-value{
      font-size:20px;
    }

    table{
      min-width:680px;
      font-size:11.5px;
    }

    thead th,
    tbody td{
      padding:8px 10px;
    }
  }
</style>
</head>

<body>

{{-- BAR ATAS --}}
<div class="no-print topbar-wrap">
  <div class="topbar">
    <div class="topbar-left">
      <button
        type="button"
        class="btn btn-back"
        onclick="handleBackPrint()">
        ← Kembali
      </button>
    </div>

    <div class="topbar-right">
      <button
        type="button"
        class="btn btn-print"
        onclick="window.print()">
        🖨 Print
      </button>
    </div>
  </div>
</div>

<div class="page-wrap">

  {{-- HEADER --}}
  <div class="header">
    <div class="header-title">LAPORAN STOK REAL-TIME</div>
    <div class="header-subtitle">DPM Workshop</div>
    <div class="header-date">
      Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
  </div>

  @php
    $totalItem  = $barangs->count();
    $sumStok    = $barangs->sum(fn($b)=>(int)$b->stok);
    $amanCount  = $barangs->filter(fn($b)=>(int)$b->stok>=25)->count();
    $tipsCount  = $barangs->filter(fn($b)=>(int)$b->stok>0 && (int)$b->stok<25)->count();
    $habisCount = $barangs->filter(fn($b)=>(int)$b->stok<=0)->count();
  @endphp

  {{-- SUMMARY --}}
  <div class="summary-grid">
    <div class="summary-card neutral">
      <div class="summary-label">Total Item</div>
      <div class="summary-value">{{ $totalItem }}</div>
    </div>

    <div class="summary-card neutral">
      <div class="summary-label">Total Stok</div>
      <div class="summary-value">{{ $sumStok }}</div>
    </div>

    <div class="summary-card safe">
      <div class="summary-label safe">Aman (≥25)</div>
      <div class="summary-value safe">{{ $amanCount }}</div>
    </div>

    <div class="summary-card warn">
      <div class="summary-label warn">Menipis (1-24)</div>
      <div class="summary-value warn">{{ $tipsCount }}</div>
    </div>

    <div class="summary-card danger">
      <div class="summary-label danger">Habis (=0)</div>
      <div class="summary-value danger">{{ $habisCount }}</div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:60px;">No</th>
          <th>Kode</th>
          <th>Nama Barang</th>
          <th>Satuan</th>
          <th class="text-right" style="width:90px;">Stok</th>
          <th style="width:120px;">Status</th>
          <th class="text-right" style="width:140px;">Harga Jual</th>
        </tr>
      </thead>

      <tbody>
        @forelse($barangs as $i=>$b)
          @php
            $stok=(int)$b->stok;

            if($stok<=0){
              $label='Habis';
              $badge='badge-habis';
            }elseif($stok<25){
              $label='Menipis';
              $badge='badge-menipis';
            }else{
              $label='Aman';
              $badge='badge-aman';
            }
          @endphp

          <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td class="font-bold">{{ $b->kode_barang }}</td>
            <td>{{ $b->nama_barang }}</td>
            <td>{{ $b->satuan }}</td>
            <td class="text-right font-bold">{{ $stok }}</td>
            <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
            <td class="text-right">Rp {{ number_format($b->harga_jual,0,',','.') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center" style="padding:24px;color:#94a3b8;">
              Belum ada data barang.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="footer">
    © DPM Workshop 2025 — Dokumen ini dicetak otomatis dari sistem.
  </div>

</div>

<script>
  function handleBackPrint() {
    if (window.opener && !window.opener.closed) {
      window.opener.focus();
      window.close();
      return;
    }

    if (window.history.length > 1) {
      window.history.back();
      return;
    }

    window.location.href = "{{ route('stok_realtime') }}";
  }

  window.addEventListener('load', () => window.print());
</script>

</body>
</html>