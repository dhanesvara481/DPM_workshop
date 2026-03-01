<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Laporan Stok Real-time - DPM Workshop</title>

<style>
@media print {
  .no-print { display:none !important; }
  body {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}

body{
  font-family:sans-serif;
  background:#fff;
  color:#0f172a;
}

table{
  width:100%;
  border-collapse:collapse;
  font-size:12px;
}

thead th{
  background:#f8fafc;
  border-bottom:2px solid #e2e8f0;
  padding:10px 12px;
  text-align:left;
  font-weight:700;
  color:#475569;
}

tbody td{
  padding:9px 12px;
  border-bottom:1px solid #e2e8f0;
  color:#334155;
}

tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:#f8fafc}

.badge{
  display:inline-flex;
  padding:3px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
  border:1px solid transparent;
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
</style>
</head>

<body>

{{-- BAR ATAS --}}
<div class="no-print" style="display:flex;justify-content:space-between;padding:16px 24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
  <a href="{{ url()->previous() }}"
     style="padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;color:#0f172a;background:#fff;">
     ← Kembali
  </a>

  <button onclick="window.print()"
          style="padding:8px 16px;background:#0f172a;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">
      🖨 Print
  </button>
</div>


<div style="max-width:900px;margin:0 auto;padding:32px 24px;">

{{-- HEADER --}}
<div style="text-align:center;margin-bottom:24px;">
  <div style="font-size:18px;font-weight:800;">LAPORAN STOK REAL-TIME</div>
  <div style="font-size:13px;color:#64748b;">DPM Workshop</div>
  <div style="font-size:12px;color:#94a3b8;">
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
<div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">

<div style="flex:1;border:1px solid #e2e8f0;border-radius:12px;padding:14px;background:#f8fafc;">
<div style="font-size:11px;color:#64748b;">Total Item</div>
<div style="font-size:22px;font-weight:800;">{{ $totalItem }}</div>
</div>

<div style="flex:1;border:1px solid #e2e8f0;border-radius:12px;padding:14px;background:#f8fafc;">
<div style="font-size:11px;color:#64748b;">Total Stok</div>
<div style="font-size:22px;font-weight:800;">{{ $sumStok }}</div>
</div>

<div style="flex:1;border:1px solid #e2e8f0;border-radius:12px;padding:14px;background:#d1fae5;">
<div style="font-size:11px;color:#065f46;">Aman (≥25)</div>
<div style="font-size:22px;font-weight:800;color:#065f46;">{{ $amanCount }}</div>
</div>

<div style="flex:1;border:1px solid #e2e8f0;border-radius:12px;padding:14px;background:#fef3c7;">
<div style="font-size:11px;color:#92400e;">Menipis (1-24)</div>
<div style="font-size:22px;font-weight:800;color:#92400e;">{{ $tipsCount }}</div>
</div>

<div style="flex:1;border:1px solid #e2e8f0;border-radius:12px;padding:14px;background:#fee2e2;">
<div style="font-size:11px;color:#991b1b;">Habis (=0)</div>
<div style="font-size:22px;font-weight:800;color:#991b1b;">{{ $habisCount }}</div>
</div>

</div>

{{-- TABLE --}}
<table>
<thead>
<tr>
<th>No</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Satuan</th>
<th class="text-right">Stok</th>
<th>Status</th>
<th class="text-right">Harga Jual</th>
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

<div style="margin-top:24px;padding-top:16px;border-top:1px solid #e2e8f0;font-size:11px;color:#94a3b8;text-align:center;">
© DPM Workshop 2025 — Dokumen ini dicetak otomatis dari sistem.
</div>

</div>

<script>
window.addEventListener('load',()=>window.print());
</script>

</body>
</html> 