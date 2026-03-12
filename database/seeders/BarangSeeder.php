<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'kode_barang' => 'BRG-00001',
                'nama_barang' => 'Thermal Paste Arctic MX-4 4g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 45000,
                'harga_jual' => 70000,
            ],
            [
                'kode_barang' => 'BRG-00002',
                'nama_barang' => 'Thermal Paste Noctua NT-H1 3.5g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 55000,
                'harga_jual' => 85000,
            ],
            [
                'kode_barang' => 'BRG-00003',
                'nama_barang' => 'Thermal Paste Generic 5g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 12000,
                'harga_jual' => 20000,
            ],
            [
                'kode_barang' => 'BRG-00004',
                'nama_barang' => 'Thermal Pad 1mm 10x10cm',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 18000,
                'harga_jual' => 28000,
            ],
            [
                'kode_barang' => 'BRG-00005',
                'nama_barang' => 'Timah Solder 60/40 0.8mm 100g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 28000,
                'harga_jual' => 45000,
            ],
            [
                'kode_barang' => 'BRG-00006',
                'nama_barang' => 'Flux Pasta Solder 50g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 18000,
                'harga_jual' => 28000,
            ],
            [
                'kode_barang' => 'BRG-00007',
                'nama_barang' => 'Wick Desoldering Braid 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 15000,
                'harga_jual' => 24000,
            ],
            [
                'kode_barang' => 'BRG-00008',
                'nama_barang' => 'Isolasi Kapton Tape 10mm',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 18000,
                'harga_jual' => 28000,
            ],
            [
                'kode_barang' => 'BRG-00009',
                'nama_barang' => 'Kuas Pembersih PCB Anti-Statik',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 8000,
                'harga_jual' => 13000,
            ],
            [
                'kode_barang' => 'BRG-00010',
                'nama_barang' => 'Obeng Presisi Set 32 in 1',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 65000,
                'harga_jual' => 100000,
            ],
            [
                'kode_barang' => 'BRG-00011',
                'nama_barang' => 'Solder Station / Pengatur Suhu',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 280000,
                'harga_jual' => 420000,
            ],
            [
                'kode_barang' => 'BRG-00012',
                'nama_barang' => 'Multimeter Digital',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 95000,
                'harga_jual' => 145000,
            ],
            [
                'kode_barang' => 'BRG-00013',
                'nama_barang' => 'Pinset Anti-Statik Set (Lurus + Bengkok)',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 35000,
                'harga_jual' => 55000,
            ],
            [
                'kode_barang' => 'BRG-00014',
                'nama_barang' => 'Gelang Anti-Statik / ESD Wrist Strap',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 18000,
                'harga_jual' => 28000,
            ],
            [
                'kode_barang' => 'BRG-00015',
                'nama_barang' => 'Kabel HDMI 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 28000,
                'harga_jual' => 45000,
            ],
            [
                'kode_barang' => 'BRG-00016',
                'nama_barang' => 'Kabel VGA 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 22000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00017',
                'nama_barang' => 'USB Hub 4-Port USB 3.0',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 55000,
                'harga_jual' => 85000,
            ],
            [
                'kode_barang' => 'BRG-00018',
                'nama_barang' => 'Keyboard USB Wired Standar',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 65000,
                'harga_jual' => 100000,
            ],
            [
                'kode_barang' => 'BRG-00019',
                'nama_barang' => 'Mouse USB Optical Standar',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 38000,
                'harga_jual' => 60000,
            ],
            [
                'kode_barang' => 'BRG-00020',
                'nama_barang' => 'Mousepad 30x25cm',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 22000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00021',
                'nama_barang' => 'DC Power Supply Adjustable 0-30V',
                'stok' => 0,
                'satuan' => 'unit',
                'harga_beli' => 350000,
                'harga_jual' => 520000,
            ],
        ]);
    }
}