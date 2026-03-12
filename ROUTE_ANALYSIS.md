# Route Usage Analysis Report

**Generated:** March 12, 2026  
**Analysis Scope:** All Blade files in `resources/views/` vs `routes/web.php`

---

## EXECUTIVE SUMMARY

✅ **GOOD NEWS:** All routes that use the `route()` helper function are correctly pointing to defined routes.  
✅ **Stok Opname Routes:** All 10 routes use proper dot notation prefix (`stok_opname.*`)  
⚠️ **CAUTION:** 2 routes bypass the `route()` helper and use raw URL paths instead

---

## Complete Route Inventory

### All Defined Routes in routes/web.php (65 total)

**Admin Routes:**

- `tampilan_dashboard`, `mengelola_barang`, `tambah_barang`, `simpan_barang`, `ubah_barang`, `perbarui_barang`, `hapus_barang`, `buat_kode_barang`
- `barang_keluar`, `simpan_barang_keluar`
- `barang_masuk`, `simpan_barang_masuk`
- `stok_realtime`, `stok_realtime.print`, `riwayat_perubahan_stok`
- `riwayat_transaksi`, `detail_riwayat_transaksi`, `transaksi.nota`
- `tampilan_invoice`, `tampilan_konfirmasi_invoice`, `konfirmasi_invoice_tanda_konfirmasi`, `hapus_konfirmasi_invoice`
- `laporan_penjualan`, `laporan_penjualan.print`
- `kelola_jadwal_kerja`, `tambah_jadwal_kerja`, `simpan_jadwal_kerja`, `ubah_jadwal_kerja`, `perbarui_jadwal_kerja`, `hapus_jadwal_kerja`, `delete_jadwal_kerja`, `hapus_jadwal_kerja_batch`, `hapus_jadwal_kerja_all`, `tampilan_jadwal_kerja`
- `tampilan_manajemen_staf`, `tambah_staf`, `simpan_staf`, `ubah_staf`, `update_staf`, `toggle_status_staf`
- `stok_opname.daftarOpname`, `stok_opname.buatOpname`, `stok_opname.simpanOpname`, `stok_opname.detailOpname`, `stok_opname.ubahOpname`, `stok_opname.updateOpname`, `stok_opname.submitOpname`, `stok_opname.setujuiOpname`, `stok_opname.tolakOpname`, `stok_opname.hapusOpname`
- `tampilan_profil`, `edit_profil`, `update_profil`

**Staff Routes:**

- `tampilan_dashboard_staff`, `tampilan_invoice_staff`, `stok_realtime_staff`, `stok_realtime_staff.print`
- `riwayat_transaksi_staff`, `detail_riwayat_transaksi_staff`, `transaksi.nota_staff`
- `jadwal_kerja_staff`, `tampilan_profil_staff`

**Shared Routes:**

- `login`, `login.attempt`, `logout`, `dashboard`
- `tampilan_notifikasi`, `detail_notifikasi`
- `invoice.simpan`, `invoice.check-stok`

---

## Routes Used in Blade Files via route() Helper ✅

**ALL 52 UNIQUE ROUTES FOUND IN BLADE FILES EXIST AND ARE CORRECTLY REFERENCED:**

```
✅ tampilan_notifikasi              ✅ stok_realtime                ✅ tampilan_invoice
✅ tampilan_konfirmasi_invoice      ✅ kelola_jadwal_kerja         ✅ tampilan_profil_staff
✅ tampilan_dashboard_staff         ✅ tampilan_invoice_staff      ✅ logout
✅ tampilan_profil                  ✅ update_profil               ✅ tampilan_dashboard
✅ mengelola_barang                 ✅ barang_masuk                ✅ barang_keluar
✅ riwayat_perubahan_stok           ✅ riwayat_transaksi           ✅ laporan_penjualan
✅ tampilan_manajemen_staf          ✅ ubah_staf                   ✅ simpan_staf
✅ simpan_barang_keluar             ✅ simpan_barang_masuk         ✅ stok_realtime.print
✅ detail_riwayat_transaksi         ✅ transaksi.nota              ✅ laporan_penjualan.print
✅ tambah_jadwal_kerja              ✅ perbarui_jadwal_kerja       ✅ ubah_jadwal_kerja
✅ hapus_jadwal_kerja               ✅ hapus_jadwal_kerja_batch    ✅ hapus_jadwal_kerja_all
✅ stok_opname.daftarOpname         ✅ stok_opname.buatOpname      ✅ stok_opname.detailOpname
✅ stok_opname.ubahOpname           ✅ stok_opname.hapusOpname     ✅ stok_opname.simpanOpname
✅ stok_opname.updateOpname         ✅ stok_opname.submitOpname    ✅ stok_opname.setujuiOpname
✅ stok_opname.tolakOpname          ✅ invoice.simpan              ✅ invoice.check-stok
✅ tambah_barang                    ✅ ubah_barang                 ✅ perbarui_barang
✅ detail_notifikasi                ✅ edit_profil                 ✅ update_staf
✅ riwayat_transaksi_staff          ✅ detail_riwayat_transaksi_staff ✅ transaksi.nota_staff
✅ jadwal_kerja_staff               ✅ login.attempt               ✅ konfirmasi_invoice_tanda_konfirmasi
✅ hapus_konfirmasi_invoice
```

---

## Stok Opname Routes: PERFECT IMPLEMENTATION ✅

All 10 stok_opname routes correctly use dot notation prefix as a route group:

| Route Name                  | Used In                                                | Status |
| --------------------------- | ------------------------------------------------------ | ------ |
| `stok_opname.daftarOpname`  | sidebar, mengelola_barang, stok_opname views           | ✅     |
| `stok_opname.buatOpname`    | tampilan_stok_opname.blade.php                         | ✅     |
| `stok_opname.simpanOpname`  | tambah_stok_opname.blade.php                           | ✅     |
| `stok_opname.detailOpname`  | tampilan_stok_opname, tambah_stok_opname, detail views | ✅     |
| `stok_opname.ubahOpname`    | ubah, detail, tampilan views                           | ✅     |
| `stok_opname.updateOpname`  | ubah_stok_opname.blade.php                             | ✅     |
| `stok_opname.submitOpname`  | ubah_stok_opname.blade.php                             | ✅     |
| `stok_opname.setujuiOpname` | detail_stok_opname.blade.php                           | ✅     |
| `stok_opname.tolakOpname`   | detail_stok_opname.blade.php                           | ✅     |
| `stok_opname.hapusOpname`   | tampilan_stok_opname.blade.php                         | ✅     |

---

## Routes Used But NOT via route() Helper ⚠️

These routes are hardcoded as raw URLs instead of using the `route()` helper:

### 1. **`hapus_barang` - Delete Button**

- **File:** [admin/mengelola_barang/tampilan_barang.blade.php](resources/views/admin/mengelola_barang/tampilan_barang.blade.php#L510)
- **Current Usage:** `form.action = `/hapus_barang/${barangId}`;`
- **Should Be:** `form.action = "{{ route('hapus_barang', ['id' => ''] }}/" + barangId;`
- **Risk:** If URL structure changes, this will break
- **Severity:** MEDIUM

### 2. **`buat_kode_barang` - Auto-generate Code**

- **File:** [admin/mengelola_barang/tambah_barang.blade.php](resources/views/admin/mengelola_barang/tambah_barang.blade.php#L445)
- **Current Usage:** `const res = await fetch('/barang/buat_kode_barang');`
- **Should Be:** `const res = await fetch("{{ route('buat_kode_barang') }}");`
- **Risk:** If URL structure changes, form auto-generation will break
- **Severity:** MEDIUM

---

## Routes Defined But Not Used in Blade Files

These routes are defined in `routes/web.php` but not referenced in blade files. They may be called via:

- JavaScript/AJAX after being built once with route()
- API calls
- Migrations or other backend code

| Route Name            | Purpose             | Status              |
| --------------------- | ------------------- | ------------------- |
| `delete_jadwal_kerja` | DELETE jadwal       | ✓ (Called via JS)   |
| `toggle_status_staf`  | PATCH toggle status | ✓ (Called via JS)   |
| `dashboard`           | Redirect router     | ✓ (Not direct link) |
| `login`               | GET login page      | ? (Not visible)     |

---

## Summary Table

| Category                                | Count | Status |
| --------------------------------------- | ----- | ------ |
| Total defined routes                    | 65    | -      |
| Routes using `route()` helper correctly | 52    | ✅     |
| Routes using raw URLs                    | 2     | ⚠️     |
| Routes defined but not used             | ~11   | ℹ️    |
| Route mismatches found                  | 0     | ✅     |

---

## Recommendations

### Priority 1: Medium Risk

1. **[admin/mengelola_barang/tampilan_barang.blade.php](resources/views/admin/mengelola_barang/tampilan_barang.blade.php#L510)** - Line 510
    - Replace raw URL with `route()` helper for `hapus_barang`
2. **[admin/mengelola_barang/tambah_barang.blade.php](resources/views/admin/mengelola_barang/tambah_barang.blade.php#L445)** - Line 445
    - Replace raw URL with `route()` helper for `buat_kode_barang`

### Result

✅ **All route names that use route() helper are CORRECT**  
✅ **All stok_opname routes use proper dot notation**  
⚠️ **2 routes use hardcoded URLs instead of route() helper** (recommend fixing)
