<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Kirim pesan teks ke Telegram
     */
    public static function sendMessage(string $text, array $inlineKeyboard = [], ?string $chatId = null): bool
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $targetChatId = $chatId ?: env('TELEGRAM_CHAT_ID');

        if (!$token || !$targetChatId) {
            Log::warning('TelegramService: Token atau Chat ID belum dikonfigurasi di .env');
            return false;
        }

        try {
            $payload = [
                'chat_id'                  => $targetChatId,
                'text'                     => $text,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => false,
            ];

            if (!empty($inlineKeyboard)) {
                $filteredKeyboard = [];
                foreach ($inlineKeyboard as $row) {
                    $validRow = [];
                    foreach ($row as $btn) {
                        $url = $btn['url'] ?? '';
                        // Telegram mensyaratkan URL publik valid (menolak localhost/127.0.0.1)
                        if (!empty($url) && !preg_match('/localhost|127\.0\.0\.1/i', $url) && filter_var($url, FILTER_VALIDATE_URL)) {
                            $validRow[] = $btn;
                        }
                    }
                    if (!empty($validRow)) {
                        $filteredKeyboard[] = $validRow;
                    }
                }

                if (!empty($filteredKeyboard)) {
                    $payload['reply_markup'] = json_encode([
                        'inline_keyboard' => $filteredKeyboard
                    ]);
                }
            }

            $response = Http::timeout(8)
                ->withoutVerifying()
                ->post("https://api.telegram.org/bot{$token}/sendMessage", $payload);

            if (!$response->successful()) {
                Log::error('TelegramService Error: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('TelegramService Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notifikasi Permohonan Mobil Baru Masuk
     */
    public static function notifyNewVehicleBooking($booking): bool
    {
        $id = $booking->id;
        $ticketCode = str_pad($id, 4, '0', STR_PAD_LEFT);
        $borrower = htmlspecialchars($booking->borrower_name ?: ($booking->user?->name ?? 'Pegawai Kantor'));
        $nip = htmlspecialchars($booking->borrower_nip ?: ($booking->user?->nip ?? '-'));
        $division = htmlspecialchars($booking->borrower_division ?: ($booking->user?->division?->name ?? 'Operasional Kantor'));
        $destination = htmlspecialchars($booking->destination ?: 'Tujuan Kedinasan');
        $pickup = htmlspecialchars($booking->pickup_location ?: 'BPJS Kesehatan Cabang Jakarta Utara');
        $passengers = (int)($booking->passengers_count ?: 1);
        $passengersDetail = htmlspecialchars($booking->passengers ?: '');
        $purpose = htmlspecialchars($booking->purpose ?: '-');
        $notes = htmlspecialchars($booking->notes ?: '');
        
        $isRoutine = (bool)($booking->is_routine || ($booking->booking_type ?? '') === 'routine');
        $mode = $isRoutine ? '🔄 Kunjungan Rutin Terjadwal' : '🚗 Sekali Jalan (Insidental)';
        
        if ($isRoutine && !empty($booking->routine_schedule)) {
            $schedule = htmlspecialchars($booking->routine_schedule);
            if ($booking->start_date) {
                $schedule .= ' (Mulai: ' . date('d/m/Y', strtotime($booking->start_date)) . ')';
            }
        } elseif ($booking->start_date) {
            $startDateStr = date('d-m-Y', strtotime($booking->start_date));
            $startTimeStr = date('H:i', strtotime($booking->start_date));
            if ($booking->end_date && date('Y-m-d', strtotime($booking->start_date)) !== date('Y-m-d', strtotime($booking->end_date))) {
                $endDateStr = date('d-m-Y', strtotime($booking->end_date));
                $endTimeStr = date('H:i', strtotime($booking->end_date));
                $schedule = "{$startDateStr} {$startTimeStr} WIB s/d {$endDateStr} {$endTimeStr} WIB (Estimasi Pulang)";
            } elseif ($booking->end_date) {
                $endTimeStr = date('H:i', strtotime($booking->end_date));
                $schedule = "{$startDateStr} • {$startTimeStr} s/d {$endTimeStr} WIB (Estimasi Pulang)";
            } else {
                $schedule = "{$startDateStr} {$startTimeStr} WIB";
            }
        } else {
            $schedule = 'Segera / Hari Ini';
        }

        // Format Rute Bertahap jika ada
        $routeFormatted = '';
        if (str_contains($destination, '➔') || str_contains($destination, '->')) {
            $rawStops = str_contains($destination, '➔') ? explode('➔', $destination) : explode('->', $destination);
            $stops = array_values(array_filter(array_map('trim', $rawStops)));
            if (count($stops) > 1) {
                $routeFormatted = "\n";
                foreach ($stops as $idx => $stop) {
                    $num = $idx + 1;
                    $routeFormatted .= "   └ <b>Titik {$num}:</b> {$stop}\n";
                }
            }
        }

        $text = "🚗 <b>[SIMPEL] PERMOHONAN PINJAM MOBIL BARU</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📋 <b>No. Permohonan:</b> #BOOK-{$ticketCode}\n";
        $text .= "🏷️ <b>Tipe Layanan:</b> {$mode}\n";
        $text .= "👤 <b>Pemohon:</b> {$borrower} (NIP: {$nip})\n";
        $text .= "🏢 <b>Unit Kerja / Divisi:</b> {$division}\n";
        $text .= "👥 <b>Jumlah Penumpang:</b> {$passengers} Orang" . ($passengersDetail ? " ({$passengersDetail})" : "") . "\n";
        $text .= "📅 <b>Jadwal Keberangkatan:</b> {$schedule}\n";
        $text .= "🏢 <b>Titik Penjemputan:</b> {$pickup}\n";
        if ($routeFormatted) {
            $text .= "📍 <b>Rute Tujuan:</b>" . rtrim($routeFormatted) . "\n";
        } else {
            $text .= "📍 <b>Rute Tujuan:</b> {$destination}\n";
        }
        $text .= "🎯 <b>Keperluan Dinas:</b>\n<i>\"{$purpose}\"</i>\n";
        if ($notes && $notes !== $purpose) {
            $text .= "💬 <b>Catatan Tambahan:</b>\n<i>{$notes}</i>\n";
        }
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "⚡ <i>Status: Menunggu persetujuan admin dan penugasan armada / sopir.</i>";

        $mapsUrl = $booking->google_maps_nav_url ?: $booking->google_maps_url;
        if (empty($mapsUrl) || !filter_var($mapsUrl, FILTER_VALIDATE_URL)) {
            $destQuery = urlencode($booking->destination ?: 'Jakarta Utara');
            $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$destQuery}";
        }

        $buttons = [
            [['text' => '🧭 Buka Rute Google Maps', 'url' => $mapsUrl]]
        ];

        return self::sendMessage($text, $buttons);
    }

    /**
     * Notifikasi Penugasan Khusus ke Sopir yang Dipilih Admin
     */
    public static function notifyVehicleBookingApproved($booking): bool
    {
        $id = $booking->id;
        $ticketCode = str_pad($id, 4, '0', STR_PAD_LEFT);
        $borrower = htmlspecialchars($booking->borrower_name ?: ($booking->user?->name ?? 'Pegawai Kantor'));
        $nip = htmlspecialchars($booking->borrower_nip ?: ($booking->user?->nip ?? '-'));
        $division = htmlspecialchars($booking->borrower_division ?: ($booking->user?->division?->name ?? 'Operasional Kantor'));
        $destination = htmlspecialchars($booking->destination ?: '-');
        $pickup = htmlspecialchars($booking->pickup_location ?: 'BPJS Kesehatan Cabang Jakarta Utara');
        $driver = htmlspecialchars($booking->driver_name ?: ($booking->driver?->name ?? 'Pengemudi'));
        $vehicle = $booking->vehicle ? htmlspecialchars("{$booking->vehicle->name} ({$booking->vehicle->license_plate})") : 'Mobil Dinas Operasional';

        $isRoutine = (bool)($booking->is_routine || ($booking->booking_type ?? '') === 'routine');
        $modeLabel = $isRoutine ? '🔄 Kunjungan Rutin Terjadwal' : '🚗 Sekali Jalan (Insidental)';

        if ($isRoutine && !empty($booking->routine_schedule)) {
            $schedule = htmlspecialchars($booking->routine_schedule);
            if ($booking->start_date) {
                $schedule .= ' (Mulai: ' . date('d/m/Y', strtotime($booking->start_date)) . ')';
            }
        } elseif ($booking->start_date) {
            $startDateStr = date('d-m-Y', strtotime($booking->start_date));
            $startTimeStr = date('H:i', strtotime($booking->start_date));
            if ($booking->end_date && date('Y-m-d', strtotime($booking->start_date)) !== date('Y-m-d', strtotime($booking->end_date))) {
                $endDateStr = date('d-m-Y', strtotime($booking->end_date));
                $endTimeStr = date('H:i', strtotime($booking->end_date));
                $schedule = "{$startDateStr} {$startTimeStr} WIB s/d {$endDateStr} {$endTimeStr} WIB (Estimasi Pulang)";
            } elseif ($booking->end_date) {
                $endTimeStr = date('H:i', strtotime($booking->end_date));
                $schedule = "{$startDateStr} • {$startTimeStr} s/d {$endTimeStr} WIB (Estimasi Pulang)";
            } else {
                $schedule = "{$startDateStr} {$startTimeStr} WIB";
            }
        } else {
            $schedule = 'Hari Ini (Segera)';
        }

        $passengers = (int)($booking->passengers_count ?: 1);
        $passengersDetail = htmlspecialchars($booking->passengers ?: '');
        $purpose = htmlspecialchars($booking->purpose ?: '-');
        $notes = htmlspecialchars($booking->notes ?: '');

        // Format Rute Bertahap jika ada lebih dari 1 destinasi
        $routeFormatted = '';
        if (str_contains($destination, '➔') || str_contains($destination, '->')) {
            $rawStops = str_contains($destination, '➔') ? explode('➔', $destination) : explode('->', $destination);
            $stops = array_values(array_filter(array_map('trim', $rawStops)));
            if (count($stops) > 1) {
                $routeFormatted = "\n";
                foreach ($stops as $idx => $stop) {
                    $num = $idx + 1;
                    $routeFormatted .= "   └ <b>Tujuan {$num}:</b> {$stop}\n";
                }
            }
        }

        // Estimasi Jarak & Waktu Tempuh jika ada
        $tripEstimates = '';
        if (!empty($booking->distance_km)) {
            $tripEstimates .= number_format((float)$booking->distance_km, 1) . ' km';
        }
        if (!empty($booking->estimated_duration_minutes)) {
            $tripEstimates .= ($tripEstimates ? ' • ±' : '±') . $booking->estimated_duration_minutes . ' menit';
        }

        $text = "🚗 <b>[SIMPEL] PENUGASAN SUPIR & ARMADA MOBIL</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "Halo Pak <b>{$driver}</b>, Anda telah ditugaskan oleh Admin untuk melayani perjalanan dinas berikut:\n\n";
        $text .= "📋 <b>No. Penugasan:</b> #TRIP-{$ticketCode}\n";
        $text .= "🏷️ <b>Tipe Layanan:</b> {$modeLabel}\n";
        $text .= "👤 <b>Pemohon:</b> {$borrower} (NIP: {$nip})\n";
        $text .= "🏢 <b>Unit Kerja / Divisi:</b> {$division}\n";
        $text .= "👥 <b>Penumpang:</b> {$passengers} Orang" . ($passengersDetail ? " ({$passengersDetail})" : "") . "\n";
        $text .= "📅 <b>Waktu / Jadwal:</b> {$schedule}\n";
        $text .= "🏢 <b>Titik Penjemputan:</b> {$pickup}\n";
        if ($routeFormatted) {
            $text .= "📍 <b>Rute Tujuan:</b>" . rtrim($routeFormatted) . "\n";
        } else {
            $text .= "📍 <b>Rute Tujuan:</b> {$destination}\n";
        }
        if ($tripEstimates) {
            $text .= "📏 <b>Estimasi Rute:</b> {$tripEstimates}\n";
        }
        $text .= "🚗 <b>Armada Mobil:</b> {$vehicle}\n";
        $text .= "🎯 <b>Keperluan Dinas:</b>\n<i>\"{$purpose}\"</i>\n";

        if ($notes && $notes !== $purpose) {
            $text .= "💬 <b>Catatan / Arahan:</b>\n<i>{$notes}</i>\n";
        }

        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📢 <i>Silakan bersiap menuju titik jemput dan lakukan scan barcode armada saat berangkat.</i>";

        $mapsUrl = $booking->google_maps_nav_url ?: $booking->google_maps_url;
        if (empty($mapsUrl) || !filter_var($mapsUrl, FILTER_VALIDATE_URL)) {
            $destQuery = urlencode($booking->destination ?: 'Jakarta Utara');
            $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$destQuery}";
        }

        $buttons = [
            [['text' => '🧭 Buka Rute Google Maps', 'url' => $mapsUrl]]
        ];

        // Cek apakah driver punya Chat ID Telegram tersendiri di database
        $targetChatId = null;
        if (!empty($booking->driver_id)) {
            $driverUser = \App\Models\User::find($booking->driver_id);
            if ($driverUser && !empty($driverUser->telegram_chat_id)) {
                $targetChatId = $driverUser->telegram_chat_id;
            }
        }
        if (!$targetChatId && !empty($booking->driver_name)) {
            $driverName = trim($booking->driver_name);
            $driverUser = \App\Models\User::where('role', 'sopir')
                ->where(function($q) use ($driverName) {
                    $q->where('name', $driverName)
                      ->orWhere('name', 'LIKE', '%' . $driverName . '%')
                      ->orWhereRaw('LOWER(name) = ?', [strtolower($driverName)]);
                })
                ->first();
            if ($driverUser && !empty($driverUser->telegram_chat_id)) {
                $targetChatId = $driverUser->telegram_chat_id;
            }
        }

        // Kirim langsung ke Telegram driver jika chat ID tersedia
        if ($targetChatId) {
            $sent = self::sendMessage($text, $buttons, $targetChatId);
            if ($sent) {
                return true;
            }
            // Jika gagal kirim langsung ke supir (misal belum /start bot), kirim backup ke grup/admin
            Log::warning("Gagal mengirim Telegram ke driver {$driver} ({$targetChatId}). Mengirimkan backup ke channel admin.");
            $warningText = "⚠️ <b>[PERINGATAN SISTEM]</b> Pengiriman pesan langsung ke Telegram Pak <b>{$driver}</b> belum dapat terkirim (pastikan akun telah menekan <code>/start</code> pada bot @simpel_jakut_notif_bot).\n\n" . $text;
            return self::sendMessage($warningText, $buttons);
        }

        // Jika driver belum memiliki ID khusus di profilnya, kirim ke default TELEGRAM_CHAT_ID (admin/grup)
        return self::sendMessage($text, $buttons);
    }

    /**
     * Notifikasi Permohonan Peminjaman Ruangan Rapat
     */
    public static function notifyRoomBooking($booking): bool
    {
        $room = htmlspecialchars($booking->room?->name ?? 'Ruang Rapat');
        $user = htmlspecialchars($booking->user?->name ?? ($booking->borrower_name ?? 'Pegawai'));
        $division = htmlspecialchars($booking->user?->division?->name ?? ($booking->borrower_division ?? '-'));
        $purpose = htmlspecialchars($booking->purpose ?? '-');
        $date = $booking->start_time ? date('d-m-Y H:i', strtotime($booking->start_time)) . ' WIB' : '-';

        $text = "🏢 <b>[SIMPEL] BOOKING RUANGAN RAPAT</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "🚪 <b>Ruangan:</b> {$room}\n";
        $text .= "👤 <b>Pemohon:</b> {$user} ({$division})\n";
        $text .= "📅 <b>Waktu:</b> {$date}\n";
        $text .= "📝 <b>Agenda:</b> {$purpose}\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━";

        $appUrl = env('APP_FRONTEND_URL', 'http://localhost:3000');
        $buttons = [
            [['text' => '🌐 Cek Jadwal Ruangan', 'url' => "{$appUrl}/User/ruangan"]]
        ];

        return self::sendMessage($text, $buttons);
    }

    /**
     * Notifikasi Permintaan Pembersihan OB (Office Boy)
     */
    public static function notifyCleaningRequest($request): bool
    {
        $room = htmlspecialchars($request->room?->name ?? ($request->room_name ?? 'Area Kantor'));
        $requester = htmlspecialchars($request->user?->name ?? 'Pegawai');
        $notes = htmlspecialchars($request->notes ?? 'Pembersihan rutin ruangan');

        $text = "🧹 <b>[SIMPEL] PERMINTAAN TUGAS KEBERSIHAN (OB)</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📍 <b>Lokasi / Ruangan:</b> {$room}\n";
        $text .= "👤 <b>Peminta:</b> {$requester}\n";
        $text .= "📋 <b>Catatan:</b> {$notes}\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━";

        $appUrl = env('APP_FRONTEND_URL', 'http://localhost:3000');
        $buttons = [
            [['text' => '🧹 Buka Portal OB', 'url' => "{$appUrl}/ob/dashboard"]]
        ];

        return self::sendMessage($text, $buttons);
    }
}
