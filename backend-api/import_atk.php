<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function parseSheetAll($zip, $sheetName, $sharedStrings) {
    $xmlContent = $zip->getFromName($sheetName);
    if (!$xmlContent) return [];
    
    $xml = simplexml_load_string($xmlContent);
    $rows = [];
    
    foreach ($xml->sheetData->row as $row) {
        $rowNum = (int)$row['r'];
        $rowData = [];
        foreach ($row->c as $c) {
            $cellRef = (string)$c['r'];
            $colLetter = preg_replace('/[0-9]/', '', $cellRef);
            $type = (string)$c['t'];
            $val = '';
            
            if ($type === 's') {
                $idx = (int)$c->v;
                $val = $sharedStrings[$idx] ?? '';
            } elseif ($type === 'inlineStr') {
                $val = (string)$c->is->t;
            } else {
                $val = (string)$c->v;
            }
            $rowData[$colLetter] = trim($val);
        }
        $rows[$rowNum] = $rowData;
    }
    return $rows;
}

function detectCategory($name) {
    $n = strtolower($name);

    // Folder & Arsip
    if (str_contains($n, 'map') || str_contains($n, 'ordner') || str_contains($n, 'folder') || 
        str_contains($n, 'binder clip') || str_contains($n, 'paper clip') || str_contains($n, 'penjepit') ||
        str_contains($n, 'business file') || str_contains($n, 'clear holder') || str_contains($n, 'pocket') ||
        str_contains($n, 'snelhecter') || str_contains($n, 'separator') || str_contains($n, 'pembatas') ||
        str_contains($n, 'box file')) {
        return 'Folder & Arsip';
    }

    // Buku & Kertas
    if (str_contains($n, 'kertas') || str_contains($n, 'hvs') || str_contains($n, 'buku') || 
        str_contains($n, 'concorde') || str_contains($n, 'karton') || str_contains($n, 'amplop') || 
        str_contains($n, 'kwitansi') || str_contains($n, 'post it') || str_contains($n, 'sticky note') ||
        str_contains($n, 'label') || str_contains($n, 'sign here') || str_contains($n, 't & j') ||
        str_contains($n, 'samson')) {
        return 'Buku & Kertas';
    }

    // Alat Tulis
    if (str_contains($n, 'pulpen') || str_contains($n, 'ballpoint') || str_contains($n, 'pensil') || 
        str_contains($n, 'spidol') || str_contains($n, 'marker') || str_contains($n, 'highlighter') || 
        str_contains($n, 'stabilo') || str_contains($n, 'pen ') || str_starts_with($n, 'pen') ||
        str_contains($n, 'correction') || str_contains($n, 'corection') || str_contains($n, 'tipex') ||
        str_contains($n, 'tip-ex') || str_contains($n, 'penghapus') || str_contains($n, 'eraser') ||
        str_contains($n, 'rautan') || str_contains($n, 'sharpener') || str_contains($n, 'penggaris') ||
        str_contains($n, 'refill') || str_contains($n, 'isi pulpen') || str_contains($n, 'tinta') ||
        str_contains($n, 'ribbon') || str_contains($n, 'cartridge') || str_contains($n, 'toner')) {
        return 'Alat Tulis';
    }

    // Perlengkapan
    return 'Perlengkapan';
}

$excelPath = __DIR__ . '/../5_6172433724559336721.xlsx';
$zip = new ZipArchive();
if ($zip->open($excelPath) !== TRUE) {
    die("Gagal membuka file Excel di: $excelPath\n");
}

$ssXml = $zip->getFromName('xl/sharedStrings.xml');
$ss = simplexml_load_string($ssXml);
$sharedStrings = [];
foreach ($ss->si as $si) {
    if (isset($si->t)) $sharedStrings[] = (string)$si->t;
    elseif (isset($si->r)) {
        $t = '';
        foreach ($si->r as $r) $t .= (string)$r->t;
        $sharedStrings[] = $t;
    } else $sharedStrings[] = '';
}

$s1 = parseSheetAll($zip, 'xl/worksheets/sheet1.xml', $sharedStrings);
$s4 = parseSheetAll($zip, 'xl/worksheets/sheet4.xml', $sharedStrings);
$zip->close();

// Build Sheet 4 Sisa Stok lookup
$s4_lookup = [];
foreach ($s4 as $rNum => $r) {
    if ($rNum <= 6) continue;
    if (!empty($r['B'])) {
        $cleanName = trim($r['B']);
        $s4_lookup[$cleanName] = isset($r['G']) && is_numeric($r['G']) ? (float)$r['G'] : 0;
    }
}

$productsToInsert = [];
$now = now();

foreach ($s1 as $rNum => $r) {
    if ($rNum <= 3) continue;
    if (empty($r['B'])) continue;

    $no = (int)($r['A'] ?? 0);
    $name = trim($r['B']);
    $unit = trim($r['D'] ?? 'Pcs');
    if (empty($unit)) $unit = 'Pcs';
    $s1_stock = isset($r['C']) && is_numeric($r['C']) ? (float)$r['C'] : 0;

    // S4 sisa stok lookup
    $sisa = null;
    if (isset($s4_lookup[$name])) {
        $sisa = $s4_lookup[$name];
    } else {
        foreach ($s4_lookup as $k => $v) {
            if (str_starts_with($name, $k) || str_starts_with($k, $name)) {
                $sisa = $v;
                break;
            }
        }
    }

    $rawStock = $sisa !== null ? $sisa : $s1_stock;
    
    // Sesuaikan stok agar aman (tidak boleh minus untuk unsigned integer DB)
    if ($rawStock < 0) {
        $finalStock = max(0, (int)$s1_stock); // Pakai stok awal positif atau 0
    } else {
        $finalStock = (int)$rawStock;
    }

    // Tentukan status
    if ($finalStock === 0) {
        $status = 'out_of_stock';
    } elseif ($finalStock <= 5) {
        $status = 'low_stock';
    } else {
        $status = 'available';
    }

    $barcode = sprintf('ATK-%03d', $no);
    $category = detectCategory($name);

    $productsToInsert[] = [
        'id'          => $no,
        'barcode'     => $barcode,
        'name'        => $name,
        'category'    => $category,
        'unit'        => $unit,
        'description' => "Barang operasional kantor: {$name}",
        'stock'       => $finalStock,
        'status'      => $status,
        'image'       => '/images/atk/default-atk.svg',
        'created_at'  => $now,
        'updated_at'  => $now,
    ];
}

echo "Total barang siap diimport: " . count($productsToInsert) . " barang.\n";

// Disable foreign keys, truncate products, reset auto increment
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('borrowing_items')->truncate();
DB::table('division_item_limits')->truncate();
DB::table('products')->truncate();

echo "Menghapus data ATK lama dan mereset ID urutan...\n";

// Insert secara batch
$chunks = array_chunk($productsToInsert, 50);
foreach ($chunks as $chunk) {
    DB::table('products')->insert($chunk);
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

$insertedCount = DB::table('products')->count();
echo "SUKSES! Berhasil memasukkan {$insertedCount} data ATK ke database secara berurutan (ID 1 s/d {$insertedCount}).\n";

// Tampilkan sampel barang
echo "\n--- 5 Barang Pertama ---\n";
foreach (DB::table('products')->take(5)->get() as $p) {
    echo "ID: {$p->id} | {$p->barcode} | {$p->name} | Cat: {$p->category} | Stok: {$p->stock} {$p->unit} | Status: {$p->status}\n";
}

echo "\n--- 5 Barang Terakhir ---\n";
foreach (DB::table('products')->orderBy('id', 'desc')->take(5)->get()->reverse() as $p) {
    echo "ID: {$p->id} | {$p->barcode} | {$p->name} | Cat: {$p->category} | Stok: {$p->stock} {$p->unit} | Status: {$p->status}\n";
}

echo "\n--- Barang yang disesuaikan stoknya (sebelumnya minus) ---\n";
foreach (DB::table('products')->whereIn('id', [1, 59])->get() as $p) {
    echo "ID: {$p->id} | {$p->barcode} | {$p->name} | Stok: {$p->stock} {$p->unit} | Status: {$p->status}\n";
}
