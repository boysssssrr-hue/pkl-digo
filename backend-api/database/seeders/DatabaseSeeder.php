<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\DivisionItemLimit;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $officialDivisions = [
            ['name' => 'Kepala Cabang', 'description' => 'Pimpinan operasional kantor cabang dan sekretariat pimpinan', 'member_count' => 4],
            ['name' => 'Kepesertaan', 'description' => 'Pengelolaan data kepesertaan, loket administrasi peserta, dan mutasi data', 'member_count' => 12],
            ['name' => 'Mutu Layanan Fasilitas Kesehatan', 'description' => 'Monitoring, evaluasi kemitraan faskes primer dan rujukan lanjutan', 'member_count' => 8],
            ['name' => 'Mutu Layanan Kepesertaan', 'description' => 'Layanan frontliner loket, penanganan komplain, dan kepuasan peserta', 'member_count' => 10],
            ['name' => 'Penjaminan Manfaat dan Utilisasi', 'description' => 'Verifikasi klaim pembiayaan kesehatan, audit medik, dan utilisasi layanan', 'member_count' => 14],
            ['name' => 'Perencanaan, Keuangan dan Pemeriksaan', 'description' => 'Perencanaan anggaran, realisasi keuangan, kasir, dan pemeriksaan kepatuhan', 'member_count' => 8],
            ['name' => 'SDM, Umum, dan Komunikasi', 'description' => 'Pengelolaan kepegawaian, sarana prasarana umum, logistik, dan kehumasan', 'member_count' => 10],
        ];

        $divisions = collect($officialDivisions)->mapWithKeys(fn (array $division) => [
            $division['name'] => Division::updateOrCreate(['name' => $division['name']], $division),
        ]);

        $this->call(ProductSeeder::class);
        $products = Product::all()->keyBy('barcode');

        User::updateOrCreate(['nip' => '12345'], [
            'name' => 'admin', 'nip' => '12345', 'password' => Hash::make('12345678'), 'visible_password' => '12345678', 'role' => 'admin', 'status' => 'active',
        ]);

        $sdmDivisionId = $divisions['SDM, Umum, dan Komunikasi']->id ?? 7;

        $drivers = [
            ['name' => 'Ahmad', 'nip' => 'ahmad', 'telegram_chat_id' => null],
            ['name' => 'Sigit', 'nip' => 'sigit', 'telegram_chat_id' => '7961477123'],
            ['name' => 'Dana', 'nip' => 'dana', 'telegram_chat_id' => '561142889'],
            ['name' => 'Andi', 'nip' => 'andi', 'telegram_chat_id' => '8043704545'],
            ['name' => 'Umar', 'nip' => 'umar', 'telegram_chat_id' => '8680862472'],
            ['name' => 'Kiswan', 'nip' => 'kiswan', 'telegram_chat_id' => null],
            ['name' => 'Iyus', 'nip' => 'iyus', 'telegram_chat_id' => '5827128757'],
            ['name' => 'Dian', 'nip' => 'dian', 'telegram_chat_id' => '120007410'],
        ];

        foreach ($drivers as $drv) {
            User::updateOrCreate(['nip' => $drv['nip']], [
                'name' => $drv['name'],
                'nip' => $drv['nip'],
                'division_id' => $sdmDivisionId,
                'password' => Hash::make('12345678'),
                'visible_password' => '12345678',
                'role' => 'sopir',
                'status' => 'active',
                'telegram_chat_id' => $drv['telegram_chat_id'],
            ]);
        }

        User::updateOrCreate(['nip' => 'budi'], [
            'name' => 'Budi Santoso',
            'nip' => 'budi',
            'division_id' => $sdmDivisionId,
            'password' => Hash::make('12345678'),
            'visible_password' => '12345678',
            'role' => 'ob',
            'status' => 'active',
        ]);

        $employees = [
            ['name' => 'Yayak Nugroho', 'nip' => '02560', 'division' => 'Kepala Cabang'],
            ['name' => 'Tenny Romandia', 'nip' => '02614', 'division' => 'Kepesertaan'],
            ['name' => 'Hibatus Zuriyah', 'nip' => '06833', 'division' => 'Kepesertaan'],
            ['name' => 'Rinawati', 'nip' => '06807', 'division' => 'Kepesertaan'],
            ['name' => 'Prima Mentari', 'nip' => '09545', 'division' => 'Kepesertaan'],
            ['name' => 'Fajar Tri P. Herlambang', 'nip' => '08711', 'division' => 'Kepesertaan'],
            ['name' => 'Muhammad Ikhsan', 'nip' => '05086', 'division' => 'Kepesertaan'],
            ['name' => 'Denis Filya Siburian', 'nip' => '07830', 'division' => 'Kepesertaan'],
            ['name' => 'Renie Nur Aziza', 'nip' => '220307', 'division' => 'Kepesertaan'],
            ['name' => 'Adnila Gunawan Putri', 'nip' => '220357', 'division' => 'Kepesertaan'],
            ['name' => 'Naufal Akbar Putrasyahreza', 'nip' => '230469', 'division' => 'Kepesertaan'],
            ['name' => 'Rizky Roylando Manurung', 'nip' => '240186', 'division' => 'Kepesertaan'],
            ['name' => 'Maria Olivia Regina', 'nip' => '250063', 'division' => 'Kepesertaan'],
            ['name' => 'Jonathan Aditya Ramadhan', 'nip' => '250447', 'division' => 'Kepesertaan'],
            ['name' => 'Dwi Puji Astuti', 'nip' => '250704', 'division' => 'Kepesertaan'],
            ['name' => 'Intan Ayunita Nurhanifah', 'nip' => '260031', 'division' => 'Kepesertaan'],
            ['name' => 'Reghina Putri Nabillah', 'nip' => '260118', 'division' => 'Kepesertaan'],
            ['name' => 'Mawar Siswanto', 'nip' => '260399', 'division' => 'Kepesertaan'],
            ['name' => 'Farelia Faza Anggana', 'nip' => '260400', 'division' => 'Kepesertaan'],
            ['name' => 'Vina Oktavia', 'nip' => '260504', 'division' => 'Kepesertaan'],
            ['name' => 'Ari Wahyu Kirani', 'nip' => '260510', 'division' => 'Kepesertaan'],
            ['name' => 'Andi Shalsabila Putri', 'nip' => '260596', 'division' => 'Kepesertaan'],
            ['name' => 'Ayu Triana Situmorang', 'nip' => '260816', 'division' => 'Kepesertaan'],
            ['name' => 'Zahra Aqilah', 'nip' => '260759', 'division' => 'Kepesertaan'],
            ['name' => 'Dian Ashari', 'nip' => '260684', 'division' => 'Kepesertaan'],
            ['name' => 'Naomy Nada Nisrina Ridhana Chaniago', 'nip' => '260685', 'division' => 'Kepesertaan'],
            ['name' => 'Windy Riqul Aini', 'nip' => '260761', 'division' => 'Kepesertaan'],
            ['name' => 'Yefi Kurniawan', 'nip' => '07650', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Damar Lia Susanti', 'nip' => '04546', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Retno Jiwo Utami', 'nip' => '06855', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Nur Qurotul Ayuni', 'nip' => '07899', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Muhammad Zamzami', 'nip' => '08011', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Tri Dame Uly Manullang', 'nip' => '220031', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Nur Setyawan', 'nip' => '240183', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Siti Nur Ainun Nadiyah', 'nip' => '250703', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Dewi Tri Arisanti', 'nip' => '250701', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Jessica Virginia Yola', 'nip' => '260626', 'division' => 'Mutu Layanan Fasilitas Kesehatan'],
            ['name' => 'Ghuri Ghuryani', 'nip' => '03595', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Nurwati Prihantini', 'nip' => '03344', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Roosa Magdalena Situmorang', 'nip' => '03174', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Nailul Amala', 'nip' => '09865', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Yohana Dwita Lydia', 'nip' => '220547', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Melani Tania Wijaya', 'nip' => '230101', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Yulia Indah Rahmawati', 'nip' => '230470', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Nopembri Marifin Nababan', 'nip' => '250078', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Neyla Raihan Khoirunissa', 'nip' => '250071', 'division' => 'Mutu Layanan Kepesertaan'],
            ['name' => 'Ayu Kusuma Wardhani', 'nip' => '03703', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Nurul Fithri', 'nip' => '07532', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Yoshika Kurniasari', 'nip' => '07853', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Yunita', 'nip' => '04455', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Dinda Indrawati', 'nip' => '06062', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Putri Wulandari Cahyaningtyas', 'nip' => '06851', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Erny Prian Kusuma', 'nip' => '06867', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Asri Wijayati M', 'nip' => '06990', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Rizka Yunita', 'nip' => '07589', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Amin Khilmi Fadhilah', 'nip' => '07671', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Rieza Gathari Gatot Sardjito', 'nip' => '09652', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Dewan Prasetyo', 'nip' => '05064', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Shiko Ariesona', 'nip' => '05087', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Pratista Nurmalasari', 'nip' => '05248', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Dimas Arryo Radytyo', 'nip' => '06068', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Nurfitri', 'nip' => '07585', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Hafidhotul Marfuah', 'nip' => '07587', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Sigit Purnomo', 'nip' => '07881', 'division' => 'Penjaminan Manfaat dan Utilisasi'],
            ['name' => 'Erzan Dhanalvin', 'nip' => '02485', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Ervina Kurniati', 'nip' => '04630', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Ghiki Lestari', 'nip' => '06792', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Arief Febriansyah', 'nip' => '08054', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Indah Sari Hastuti', 'nip' => '06790', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Delia Lestari', 'nip' => '10344', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Yuliana Sinta Anggraeni', 'nip' => '220306', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Putri Faraz Oktavi Yanti', 'nip' => '250076', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Sarah Abigail Yuranian', 'nip' => '251206', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Joice Elma Miranda Gulo', 'nip' => '260398', 'division' => 'Perencanaan, Keuangan dan Pemeriksaan'],
            ['name' => 'Mohamad Irham Syahbana', 'nip' => '06065', 'division' => 'SDM, Umum, dan Komunikasi'],
            ['name' => 'Giana Carlanadzova', 'nip' => '09784', 'division' => 'SDM, Umum, dan Komunikasi'],
            ['name' => 'Sandy Kurniawan', 'nip' => '06797', 'division' => 'SDM, Umum, dan Komunikasi'],
            ['name' => 'Dian Puspa Pratiwi', 'nip' => '08815', 'division' => 'SDM, Umum, dan Komunikasi'],
            ['name' => 'Daniska Alisia Azzahra', 'nip' => '250700', 'division' => 'SDM, Umum, dan Komunikasi'],
            ['name' => 'Ibnu Sina Al-Khotami', 'nip' => '250702', 'division' => 'SDM, Umum, dan Komunikasi'],
        ];

        foreach ($employees as $emp) {
            User::updateOrCreate(
                ['nip' => (string)$emp['nip']],
                [
                    'name' => $emp['name'],
                    'division_id' => $divisions[$emp['division']]->id,
                    'password' => Hash::make('12345678'),
                    'visible_password' => '12345678',
                    'role' => 'user',
                    'status' => 'active',
                ]
            );
        }

        $limits = [
            ['division' => 'Kepala Cabang', 'product' => 'ATK-001', 'quantity_limit' => 12],
            ['division' => 'Kepala Cabang', 'product' => 'ATK-002', 'quantity_limit' => 4],
            ['division' => 'Kepesertaan', 'product' => 'ATK-001', 'quantity_limit' => 24],
            ['division' => 'Kepesertaan', 'product' => 'ATK-002', 'quantity_limit' => 12],
            ['division' => 'Mutu Layanan Fasilitas Kesehatan', 'product' => 'ATK-001', 'quantity_limit' => 16],
            ['division' => 'Mutu Layanan Fasilitas Kesehatan', 'product' => 'ATK-002', 'quantity_limit' => 8],
            ['division' => 'Mutu Layanan Kepesertaan', 'product' => 'ATK-001', 'quantity_limit' => 20],
            ['division' => 'Mutu Layanan Kepesertaan', 'product' => 'ATK-002', 'quantity_limit' => 8],
            ['division' => 'Penjaminan Manfaat dan Utilisasi', 'product' => 'ATK-001', 'quantity_limit' => 28],
            ['division' => 'Penjaminan Manfaat dan Utilisasi', 'product' => 'ATK-002', 'quantity_limit' => 15],
            ['division' => 'Perencanaan, Keuangan dan Pemeriksaan', 'product' => 'ATK-001', 'quantity_limit' => 16],
            ['division' => 'Perencanaan, Keuangan dan Pemeriksaan', 'product' => 'ATK-002', 'quantity_limit' => 10],
            ['division' => 'SDM, Umum, dan Komunikasi', 'product' => 'ATK-001', 'quantity_limit' => 20],
            ['division' => 'SDM, Umum, dan Komunikasi', 'product' => 'ATK-002', 'quantity_limit' => 10],
        ];

        foreach ($limits as $limit) {
            DivisionItemLimit::updateOrCreate(
                ['division_id' => $divisions[$limit['division']]->id, 'product_id' => $products[$limit['product']]->id],
                ['quantity_limit' => $limit['quantity_limit'], 'period' => '6_bulan'],
            );
        }
    }
}
