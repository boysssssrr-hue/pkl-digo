<?php

namespace Database\Seeders;

use App\Models\CleaningTool;
use App\Models\Product;
use App\Models\Room;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class AssetManagementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data ATK (Products)
        $atkItems = [
            ['barcode' => 'ATK-001', 'name' => 'Pulpen Joyko Gel Pen 0.5 Black', 'category' => 'Alat Tulis', 'unit' => 'Pcs', 'stock' => 150, 'status' => 'available', 'description' => 'Tinta gel hitam berkualitas tinggi, anti macet.', 'image' => '/images/atk/atk-001.svg'],
            ['barcode' => 'ATK-002', 'name' => 'Kertas HVS Sinarmas A4 80gr', 'category' => 'Buku & Kertas', 'unit' => 'Rim', 'stock' => 35, 'status' => 'available', 'description' => 'Kertas putih bersih standar cetak dokumen kantor.', 'image' => '/images/atk/atk-002.svg'],
            ['barcode' => 'ATK-003', 'name' => 'Spidol Snowman Whiteboard Marker', 'category' => 'Alat Tulis', 'unit' => 'Pcs', 'stock' => 80, 'status' => 'available', 'description' => 'Spidol papan tulis mudah dihapus.', 'image' => '/images/atk/atk-003.svg'],
            ['barcode' => 'ATK-004', 'name' => 'Gunting Joyko Stainless SC-838', 'category' => 'Perlengkapan', 'unit' => 'Pcs', 'stock' => 24, 'status' => 'available', 'description' => 'Mata pisau stainless steel tajam dan awet.', 'image' => '/images/atk/atk-004.svg'],
            ['barcode' => 'ATK-005', 'name' => 'Binder Clip No. 260 Joyko 51mm', 'category' => 'Perlengkapan', 'unit' => 'Box', 'stock' => 50, 'status' => 'available', 'description' => 'Klip penjepit kertas ukuran besar isi 12 pcs.', 'image' => '/images/atk/atk-005.svg'],
            ['barcode' => 'ATK-006', 'name' => 'Map Folder Bantex Folio Pocket', 'category' => 'Folder & Arsip', 'unit' => 'Pcs', 'stock' => 65, 'status' => 'available', 'description' => 'Map dokumen plastik anti air dengan kantong arsip.', 'image' => '/images/atk/atk-006.svg'],
            ['barcode' => 'ATK-007', 'name' => 'Stabilo Boss Original Highlighter Pastel', 'category' => 'Alat Tulis', 'unit' => 'Pcs', 'stock' => 42, 'status' => 'available', 'description' => 'Penanda teks warna lembut tidak tembus kertas.', 'image' => '/images/atk/atk-007.svg'],
            ['barcode' => 'ATK-008', 'name' => 'Stapler Max HD-10 Tokyo & Isi', 'category' => 'Perlengkapan', 'unit' => 'Pcs', 'stock' => 8, 'status' => 'low_stock', 'description' => 'Stapler standar meja kerja kantor.', 'image' => '/images/atk/atk-008.svg'],
            ['barcode' => 'ATK-009', 'name' => 'Buku Agenda Hardcover Folio Batik', 'category' => 'Buku & Kertas', 'unit' => 'Buku', 'stock' => 28, 'status' => 'available', 'description' => 'Buku catatan rapat & notulen dinas.', 'image' => '/images/atk/atk-009.svg'],
            ['barcode' => 'ATK-010', 'name' => 'Sticky Notes Post-it 3x3 Kuning', 'category' => 'Perlengkapan', 'unit' => 'Pad', 'stock' => 4, 'status' => 'low_stock', 'description' => 'Kertas memo tempel serbaguna.', 'image' => '/images/atk/atk-010.svg'],
        ];

        foreach ($atkItems as $atk) {
            Product::updateOrCreate(['barcode' => $atk['barcode']], $atk);
        }

        // 2. Data Ruangan (Rooms)
        $rooms = [
            [
                'code' => 'RNG-01',
                'name' => 'Ruang Rapat Utama (Graha 1)',
                'capacity' => 35,
                'floor' => 'Lantai 2 - Sayap Barat',
                'facilities' => 'Smart TV 75", Video Conference Polycom, AC Central, Sound System, Mic Wireless, Whiteboard',
                'status' => 'Tersedia',
                'description' => 'Ruang rapat kapasitas sedang-besar dengan fasilitas telekonferensi lengkap.',
                'image' => '/images/rooms/rng-01.svg',
            ],
            [
                'code' => 'RNG-02',
                'name' => 'Aula Serbaguna Gedung Diklat',
                'capacity' => 150,
                'floor' => 'Lantai 1 - Gedung Utama',
                'facilities' => 'Panggung Seminar, LED Videotron 4x3m, Sound System 5000W, 150 Kursi Banquet, Ruang Transit VIP',
                'status' => 'Tersedia',
                'description' => 'Aula serbaguna untuk acara besar, seminar nasional, dan sosialisasi kedinasan.',
                'image' => '/images/rooms/rng-02.svg',
            ],
            [
                'code' => 'RNG-03',
                'name' => 'Ruang Rapat Eksekutif VIP',
                'capacity' => 14,
                'floor' => 'Lantai 3 - Area Direksi',
                'facilities' => 'Meja Marmer Konferensi, Smart TV 65", AC Inverter, Coffee Bar Machine, High-speed Wifi',
                'status' => 'Sedang Digunakan',
                'description' => 'Ruang pertemuan privat untuk pimpinan dan tamu dinas istimewa.',
                'image' => '/images/rooms/rng-03.svg',
            ],
            [
                'code' => 'RNG-04',
                'name' => 'Studio Podcast & Multimedia',
                'capacity' => 6,
                'floor' => 'Lantai 2 - Ruang Kreatif',
                'facilities' => 'Acoustic Soundproofing, 4x Mic Shure SM7B, Kamera Sony 4K, Studio Lighting Godox, Audio Interface',
                'status' => 'Tersedia',
                'description' => 'Fasilitas studio kedap suara untuk rekaman konten edukasi, podcast internal, dan media humas.',
                'image' => '/images/rooms/rng-04.svg',
            ],
            [
                'code' => 'RNG-05',
                'name' => 'Ruang Kolaborasi & Brainstorming',
                'capacity' => 16,
                'floor' => 'Lantai 1 - Sayap Timur',
                'facilities' => 'Interactive Digital Whiteboard, Sofa Modular, Proyektor Laser, Bean Bag Relax Area, Dispenser',
                'status' => 'Pemeliharaan',
                'description' => 'Ruang diskusi fleksibel untuk tim kerja lintas divisi.',
                'image' => '/images/rooms/rng-05.svg',
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['code' => $room['code']], $room);
        }

        // 3. Data Mobil Operasional (Vehicles)
        $vehicles = [
            [
                'license_plate' => 'B 1024 PQ',
                'name' => 'Toyota Innova Zenix 2.0 V CVT',
                'brand' => 'Toyota',
                'transmission' => 'Automatic CVT',
                'capacity' => 7,
                'fuel_type' => 'Bensin',
                'status' => 'Sedang Digunakan',
                'status_key' => 'departed',
                'current_driver_name' => 'Ahmad Sopir',
                'current_destination' => 'Kunjungan Lapangan Faskes Primer',
                'current_passengers' => 'Dr. Rina & Tim',
                'current_division' => 'Mutu Layanan Faskes',
                'color' => '#10b981',
                'body_color' => '#047857',
                'description' => 'Kendaraan dinas operasional pimpinan dan kunjungan luar kota resmi.',
                'image' => '/images/vehicles/b-1024-pq.svg',
            ],
            [
                'license_plate' => 'B 2831 UY',
                'name' => 'Toyota Avanza 1.5 G CVT',
                'brand' => 'Toyota',
                'transmission' => 'Automatic',
                'capacity' => 7,
                'fuel_type' => 'Bensin',
                'status' => 'Sedang Digunakan',
                'status_key' => 'departed',
                'current_driver_name' => 'Hibatus Zuriyah',
                'current_destination' => 'Layanan BPJS Keliling Kec. Sukmajaya',
                'current_passengers' => 'Tim Pelayanan Mobile',
                'current_division' => 'Kepesertaan',
                'color' => '#06b6d4',
                'body_color' => '#0284c7',
                'description' => 'Kendaraan operasional harian dinas luar kota dan administrasi instansi.',
                'image' => '/images/vehicles/b-2831-uy.svg',
            ],
            [
                'license_plate' => 'B 1945 RFS',
                'name' => 'Toyota Fortuner 2.8 VRZ',
                'brand' => 'Toyota',
                'transmission' => 'Automatic',
                'capacity' => 7,
                'fuel_type' => 'Diesel',
                'status' => 'Sedang Digunakan',
                'status_key' => 'departed',
                'current_driver_name' => 'Dedi Kurnia',
                'current_destination' => 'Kunjungan Kerja Kantor Wilayah',
                'current_passengers' => 'Kepala Cabang & Sekretaris',
                'current_division' => 'Kepala Cabang',
                'color' => '#ef4444',
                'body_color' => '#b91c1c',
                'description' => 'Mobil dinas pimpinan kantor cabang.',
                'image' => '/images/vehicles/b-1945-rfs.svg',
            ],
            [
                'license_plate' => 'B 9042 RX',
                'name' => 'Mitsubishi Xpander Ultimate',
                'brand' => 'Mitsubishi',
                'transmission' => 'Automatic',
                'capacity' => 7,
                'fuel_type' => 'Bensin',
                'status' => 'Perjalanan Pulang',
                'status_key' => 'returning',
                'current_driver_name' => 'Yefi Kurniawan',
                'current_destination' => 'Visitasi RS Hermina (OTW Kembali)',
                'current_passengers' => 'Tim Verifikator',
                'current_division' => 'Penjaminan Manfaat',
                'color' => '#f59e0b',
                'body_color' => '#d97706',
                'description' => 'Mobil dinas operasional staf dan koordinasi antar instansi pemerintah.',
                'image' => '/images/vehicles/b-9042-rx.svg',
            ],
            [
                'license_plate' => 'B 7788 VIP',
                'name' => 'Honda CR-V 2.0 RS e:HEV Hybrid',
                'brand' => 'Honda',
                'transmission' => 'e-CVT Hybrid',
                'capacity' => 5,
                'fuel_type' => 'Bensin Hybrid',
                'status' => 'Tersedia',
                'status_key' => 'available',
                'current_driver_name' => 'Bagas Pratama',
                'current_destination' => 'Pool Kantor',
                'current_passengers' => null,
                'current_division' => 'SDM, Umum, dan Komunikasi',
                'color' => '#8b5cf6',
                'body_color' => '#6d28d9',
                'description' => 'Mobil kedinasan pimpinan utama instansi.',
                'image' => '/images/vehicles/b-7788-vip.svg',
            ],
            [
                'license_plate' => 'B 3418 CD',
                'name' => 'Daihatsu Luxio 1.5 M Minibus',
                'brand' => 'Daihatsu',
                'transmission' => 'Manual',
                'capacity' => 8,
                'fuel_type' => 'Bensin',
                'status' => 'Tersedia',
                'status_key' => 'available',
                'current_driver_name' => 'Budi Santoso',
                'current_destination' => 'Pool Kantor',
                'current_passengers' => null,
                'current_division' => 'SDM, Umum, dan Komunikasi',
                'color' => '#14b8a6',
                'body_color' => '#0f766e',
                'description' => 'Kendaraan angkut logistik barang kantor, perlengkapan event, dan tim lapangan.',
                'image' => '/images/vehicles/b-3418-cd.svg',
            ],
            [
                'license_plate' => 'B 2026 EV',
                'name' => 'Wuling Air EV Long Range',
                'brand' => 'Wuling',
                'transmission' => 'Automatic',
                'capacity' => 4,
                'fuel_type' => 'Listrik',
                'status' => 'Sedang Digunakan',
                'status_key' => 'departed',
                'current_driver_name' => 'Siti Rahma',
                'current_destination' => 'Pengantaran Berkas Klaim FKTP',
                'current_passengers' => 'Staf Administrasi Klaim',
                'current_division' => 'SDM, Umum, dan Komunikasi',
                'color' => '#ec4899',
                'body_color' => '#be185d',
                'description' => 'Mobil listrik ramah lingkungan untuk mobilitas dokumen dalam kota.',
                'image' => '/images/vehicles/b-2026-ev.svg',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(['license_plate' => $vehicle['license_plate']], $vehicle);
        }

        // 4. Data Cleaning Service (CleaningTool)
        $cleaningTools = [
            [
                'code' => 'CLN-001',
                'name' => 'Mesin Polisher Lantai 17" Klenco Heavy Duty',
                'category' => 'Mesin Pembersih',
                'quantity' => 2,
                'unit' => 'Unit',
                'location' => 'Gudang Janitor Lt. 1',
                'status' => 'Tersedia',
                'description' => 'Mesin pemoles & scrub marmer dan granit lantai utama.',
                'image' => '/images/cleaning/cln-001.svg',
            ],
            [
                'code' => 'CLN-002',
                'name' => 'Industrial Wet & Dry Vacuum Cleaner 30L',
                'category' => 'Mesin Penyedot Debu',
                'quantity' => 4,
                'unit' => 'Unit',
                'location' => 'Gudang Janitor Lt. 2',
                'status' => 'Tersedia',
                'description' => 'Penyedot debu basah & kering berdaya hisap tinggi untuk karpet ruang rapat.',
                'image' => '/images/cleaning/cln-002.svg',
            ],
            [
                'code' => 'CLN-003',
                'name' => 'High Pressure Washer 140 Bar Karcher K4',
                'category' => 'Mesin Cuci Tekanan',
                'quantity' => 2,
                'unit' => 'Unit',
                'location' => 'Janitor Basement / Area Luar',
                'status' => 'Sedang Digunakan',
                'description' => 'Pembersih semprot air tekanan tinggi untuk area parkir dan paving luar.',
                'image' => '/images/cleaning/cln-003.svg',
            ],
            [
                'code' => 'CLN-004',
                'name' => 'Janitor Cleaning Cart / Trolley 3-Tier',
                'category' => 'Trolley & Keranjang',
                'quantity' => 6,
                'unit' => 'Unit',
                'location' => 'Stasiun Janitor Lt. 1, 2, 3',
                'status' => 'Tersedia',
                'description' => 'Troli perlengkapan kebersihan harian lengkap dengan kantong sampah.',
                'image' => '/images/cleaning/cln-004.svg',
            ],
            [
                'code' => 'CLN-005',
                'name' => 'Ride-on Floor Scrubber Dryer Otomatis',
                'category' => 'Mesin Otomatis',
                'quantity' => 1,
                'unit' => 'Unit',
                'location' => 'Gudang Lobby Utama',
                'status' => 'Perbaikan',
                'description' => 'Mesin pencuci & pengering lantai otomatis untuk area selasar gedung.',
                'image' => '/images/cleaning/cln-005.svg',
            ],
            [
                'code' => 'CLN-006',
                'name' => 'Mop Set Microfiber Dual Bucket Wringer',
                'category' => 'Peralatan Manual',
                'quantity' => 10,
                'unit' => 'Set',
                'location' => 'Pantry & Gudang Kebersihan',
                'status' => 'Tersedia',
                'description' => 'Alat pel lantai microfiber dengan ember pemeras ganda higienis.',
                'image' => '/images/cleaning/cln-006.svg',
            ],
        ];

        foreach ($cleaningTools as $tool) {
            CleaningTool::updateOrCreate(['code' => $tool['code']], $tool);
        }
    }
}
