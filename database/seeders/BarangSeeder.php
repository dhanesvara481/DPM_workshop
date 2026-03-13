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
                'nama_barang' => 'THERMAL GRIZZLY Kryonaut Extreme 2g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 260000,
                'harga_jual' => 280000,
            ],
            [
                'kode_barang' => 'BRG-00002',
                'nama_barang' => 'Thermal Paste Noctua NT-H1 3.5g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 130000,
                'harga_jual' => 150000,
            ],
            [
                'kode_barang' => 'BRG-00003',
                'nama_barang' => 'Thermal Paste MX4 (Servis) 1g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 10000,
                'harga_jual' => 20000,
            ],
            [
                'kode_barang' => 'BRG-00004',
                'nama_barang' => 'Thermal Pad Silicone 1mm 10x10cm',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00005',
                'nama_barang' => 'Timah Solder 60/40 0.8mm 100g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00006',
                'nama_barang' => 'Flux Pasta Solder 50g',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00007',
                'nama_barang' => 'Wick Desoldering Braid 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 15000,
                'harga_jual' => 25000,
            ],
            [
                'kode_barang' => 'BRG-00008',
                'nama_barang' => 'Isolasi Kapton Tape 10mm',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
            ],
            [
                'kode_barang' => 'BRG-00009',
                'nama_barang' => 'Kuas Pembersih PCB Anti-Statik',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 10000,
                'harga_jual' => 15000,
            ],
            [
                'kode_barang' => 'BRG-00010',
                'nama_barang' => 'Obeng Presisi Set 32 in 1',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 35000,
                'harga_jual' => 45000,
            ],
            [
                'kode_barang' => 'BRG-00011',
                'nama_barang' => 'ROBOT Card Reader USB 2 in 1 USB 3.0 Type-C',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 65000,
                'harga_jual' => 75000,
            ],
            [
                'kode_barang' => 'BRG-00012',
                'nama_barang' => 'Pinset Anti-Statik Jakemy JM-T11 (3 in 1)',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00013',
                'nama_barang' => 'Gelang Anti-Statik / ESD Wrist Strap',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 15000,
                'harga_jual' => 25000,
            ],
            [
                'kode_barang' => 'BRG-00014',
                'nama_barang' => 'Kabel HDMI 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00015',
                'nama_barang' => 'Kabel VGA 1.5m',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 20000,
                'harga_jual' => 30000,
            ],
            [
                'kode_barang' => 'BRG-00016',
                'nama_barang' => 'Usb Hub 4-PORT. Usb 3.0 ProffTech.',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 55000,
                'harga_jual' => 65000,
            ],
            [
                'kode_barang' => 'BRG-00017',
                'nama_barang' => 'Pembersih Layar LCD Kit 3 in 1 Mikuso',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kode_barang' => 'BRG-00018',
                'nama_barang' => 'Screen Protector Laptop Anti-Gores 15.6 inch',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 35000,
                'harga_jual' => 55000,
            ],
            [
                'kode_barang' => 'BRG-00019',
                'nama_barang' => 'Mousepad Kvlar T1 80 x 30',
                'stok' => 0,
                'satuan' => 'pcs',
                'harga_beli' => 70000,
                'harga_jual' => 90000,
            ],
            [
                'kode_barang' => 'BRG-00020',
                'nama_barang' => 'Anti Slip Grip Tape Mouse Sticker Ajazz AJ159',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 70000,
                'harga_jual' => 90000,
            ],
            [
                'kode_barang' => 'BRG-00021',
                'nama_barang' => 'PTM Thermal Grizzly 50x40x0.2mm',
                'stok' => 0,
                'satuan' => 'set',
                'harga_beli' => 160000,
                'harga_jual' => 180000,
            ],
        ]);
    }
}