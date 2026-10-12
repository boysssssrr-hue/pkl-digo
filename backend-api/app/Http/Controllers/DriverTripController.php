<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBooking;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverTripController extends Controller
{
    /**
     * Ambil data trip aktif sopir saat ini
     */
    public function myTrip(Request $request)
    {
        $user = $request->user();
        $userId = $user?->id;
        $userName = $user?->name;

        $vehicle = Vehicle::where(function ($q) use ($userId, $userName) {
            if ($userId) {
                $q->where('current_driver_id', $userId);
            }
            if ($userName) {
                $q->orWhere('current_driver_name', $userName);
            }
        })
        ->whereIn('status_key', ['departed', 'returning'])
        ->first();

        return response()->json([
            'status' => 'success',
            'has_active_trip' => (bool)$vehicle,
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Ambil riwayat perjalanan sopir (berdasarkan driver_id atau driver_name spesifik)
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $driverId = $request->input('driver_id', $user?->id);
        $driverName = $request->input('driver_name', $user?->name);

        $query = VehicleBooking::with(['vehicle', 'user.division'])->latest();

        if ($driverId || $driverName) {
            $query->where(function ($q) use ($driverId, $driverName) {
                if ($driverId) {
                    $q->where('driver_id', $driverId)
                      ->orWhere('user_id', $driverId);
                }
                if ($driverName) {
                    $q->orWhere('driver_name', $driverName)
                      ->orWhere('notes', 'like', "%Sopir: {$driverName}%")
                      ->orWhere('notes', 'like', "%Pengemudi: {$driverName}%");
                }
            });
        }

        $history = $query->limit(50)->get();

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }

    /**
     * Ambil riwayat peminjaman mobil untuk pegawai tertentu
     */
    public function userBookings($userId)
    {
        $targetUser = User::find($userId);
        $targetName = $targetUser ? trim($targetUser->name) : null;
        $targetNip = $targetUser ? trim($targetUser->nip) : null;

        $bookings = VehicleBooking::with(['vehicle', 'driver'])
            ->where(function ($q) use ($userId, $targetName, $targetNip) {
                $q->where('user_id', $userId);
                if ($targetName) {
                    $q->orWhere('borrower_name', $targetName);
                }
                if ($targetNip && $targetNip !== '-' && $targetNip !== '12345') {
                    $q->orWhere('borrower_nip', $targetNip);
                }
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    /**
     * Ambil daftar lokasi tujuan yang paling sering dikunjungi oleh user tertentu (Rekomendasi Personal)
     */
    public function frequentDestinations($userId)
    {
        $targetUser = User::find($userId);
        $targetName = $targetUser ? trim($targetUser->name) : null;
        $targetNip = $targetUser ? trim($targetUser->nip) : null;

        // Ambil riwayat booking mobil user ini
        $bookings = VehicleBooking::where(function ($q) use ($userId, $targetName, $targetNip) {
            $q->where('user_id', $userId);
            if ($targetName) {
                $q->orWhere('borrower_name', $targetName);
            }
            if ($targetNip && $targetNip !== '-' && $targetNip !== '12345') {
                $q->orWhere('borrower_nip', $targetNip);
            }
        })
        ->whereNotNull('destination')
        ->latest()
        ->get(['destination', 'destination_lat', 'destination_lng', 'distance_km']);

        // Dictionary preset koordinat & icon yang sudah dikenal
        $knownLocations = [
            'rsud koja' => ['lat' => -6.1089322, 'lng' => 106.8996682, 'icon' => '🏥', 'subtitle' => 'Jl. Deli No. 4, Koja, Jakarta Utara', 'distance' => '~2.5 km'],
            'kantor walikota jakarta utara' => ['lat' => -6.1201816, 'lng' => 106.8918439, 'icon' => '🏛️', 'subtitle' => 'Jl. Laksamana Yos Sudarso No. 27-29, Jakarta Utara', 'distance' => '~2.7 km'],
            'walikota jakarta utara' => ['lat' => -6.1201816, 'lng' => 106.8918439, 'icon' => '🏛️', 'subtitle' => 'Jl. Laksamana Yos Sudarso No. 27-29, Jakarta Utara', 'distance' => '~2.7 km'],
            'rsud tanjung priok' => ['lat' => -6.115837, 'lng' => 106.88933, 'icon' => '🏥', 'subtitle' => 'Jl. Bugis No. 1, Tanjung Priok, Jakarta Utara', 'distance' => '~2.8 km'],
            'rsud cilincing' => ['lat' => -6.112966, 'lng' => 106.92607, 'icon' => '🏥', 'subtitle' => 'Jl. Madya Kebantenan No. 4, Cilincing, Jakarta Utara', 'distance' => '~3.2 km'],
            'dinas kesehatan prov. dki jakarta' => ['lat' => -6.1547607, 'lng' => 106.8368718, 'icon' => '🏛️', 'subtitle' => 'Jl. Kesehatan No. 10, Petojo Selatan, Gambir, Jakarta Pusat', 'distance' => '~10.5 km'],
            'dinas kesehatan provinsi dki jakarta' => ['lat' => -6.1547607, 'lng' => 106.8368718, 'icon' => '🏛️', 'subtitle' => 'Jl. Kesehatan No. 10, Petojo Selatan, Gambir, Jakarta Pusat', 'distance' => '~10.5 km'],
            'dinkes dki' => ['lat' => -6.1547607, 'lng' => 106.8368718, 'icon' => '🏛️', 'subtitle' => 'Jl. Kesehatan No. 10, Petojo Selatan, Gambir, Jakarta Pusat', 'distance' => '~10.5 km'],
            'bpjs kesehatan kantor pusat' => ['lat' => -6.1764, 'lng' => 106.8718, 'icon' => '🏢', 'subtitle' => 'Jl. Letjen Suprapto Kav. 20 No. 14, Cempaka Putih, Jakarta Pusat', 'distance' => '~7.8 km'],
            'balai kota dki jakarta' => ['lat' => -6.1812627, 'lng' => 106.8286123, 'icon' => '🏛️', 'subtitle' => 'Jl. Medan Merdeka Selatan No. 8-9, Gambir, Jakarta Pusat', 'distance' => '~11.2 km'],
            'rsud duren sawit' => ['lat' => -6.2341, 'lng' => 106.9172, 'icon' => '🏥', 'subtitle' => 'Duren Sawit, Jakarta Timur', 'distance' => '~14.0 km'],
        ];

        // Ekstraksi dan hitung frekuensi tiap lokasi
        $counts = [];
        $locData = [];

        foreach ($bookings as $b) {
            $destRaw = $b->destination;
            if (!$destRaw) continue;

            // Split jika multi-stop (dengan tanda ➔ atau -> atau koma)
            $stops = preg_split('/(➔|->)/u', $destRaw);
            foreach ($stops as $stop) {
                $stopName = trim($stop);
                if (empty($stopName) || strlen($stopName) < 3) continue;

                $cleanKey = strtolower($stopName);
                if (!isset($counts[$cleanKey])) {
                    $counts[$cleanKey] = 0;
                    
                    // Deteksi icon berdasarkan kata kunci
                    $icon = '📍';
                    if (str_contains($cleanKey, 'rsud') || str_contains($cleanKey, 'rumah sakit') || str_contains($cleanKey, 'puskesmas') || str_contains($cleanKey, 'klinik')) {
                        $icon = '🏥';
                    } elseif (str_contains($cleanKey, 'kantor') || str_contains($cleanKey, 'dinas') || str_contains($cleanKey, 'walikota') || str_contains($cleanKey, 'balai kota') || str_contains($cleanKey, 'kementerian')) {
                        $icon = '🏛️';
                    } elseif (str_contains($cleanKey, 'bpjs') || str_contains($cleanKey, 'gedung') || str_contains($cleanKey, 'tower')) {
                        $icon = '🏢';
                    }

                    // Cocokkan coords dari preset jika ada
                    $coords = null;
                    $distance = null;
                    $subtitle = 'Destinasi Kedinasan';

                    foreach ($knownLocations as $kName => $kData) {
                        if (str_contains($cleanKey, $kName) || str_contains($kName, $cleanKey)) {
                            $coords = ['lat' => $kData['lat'], 'lng' => $kData['lng']];
                            $icon = $kData['icon'];
                            $subtitle = $kData['subtitle'];
                            $distance = $kData['distance'];
                            break;
                        }
                    }

                    if (!$coords && $b->destination_lat && $b->destination_lng) {
                        $coords = ['lat' => (float)$b->destination_lat, 'lng' => (float)$b->destination_lng];
                        if ($b->distance_km) {
                            $distance = '~' . number_format((float)$b->distance_km, 1) . ' km';
                        }
                    }

                    $locData[$cleanKey] = [
                        'name'     => $stopName,
                        'icon'     => $icon,
                        'subtitle' => $subtitle,
                        'coords'   => $coords,
                        'distance' => $distance,
                    ];
                }
                $counts[$cleanKey]++;
            }
        }

        // Urutkan berdasarkan frekuensi terbanyak (paling sering dikunjungi)
        arsort($counts);

        $results = [];
        foreach ($counts as $k => $qty) {
            $item = $locData[$k];
            $item['count'] = $qty;
            $results[] = $item;
        }

        return response()->json([
            'status' => 'success',
            'data'   => array_slice($results, 0, 8) // Ambil top 8 lokasi tersering
        ]);
    }

    /**
     * Ambil notifikasi booking kendaraan masuk (hanya yang sudah disetujui admin) dan perjalanan yang sedang aktif
     * Sopir bisa menerima notifikasi setiap mobil sesuai dengan penugasan nama pengemudi dari admin (menyesuaikan dinamis)
     */
    public function bookings(Request $request)
    {
        $user = $request->user();
        $driverId = $request->input('driver_id', $user?->id);
        $driverName = $request->input('driver_name', $user?->name);

        // Hanya booking yang berstatus 'approved' yang masuk ke notifikasi sopir
        $query = VehicleBooking::with(['user.division', 'vehicle', 'driver'])
            ->where('status', 'approved')
            ->latest();

        // Notifikasi disaring sesuai pengajuan nama pengemudi dari admin (tidak terikat penanggung jawab mobil tetap)
        if ($driverId || $driverName) {
            $query->where(function ($q) use ($driverId, $driverName) {
                // 1. Ditugaskan khusus ke sopir ini oleh admin (bisa untuk mobil apapun)
                if ($driverId) {
                    $q->where('driver_id', $driverId);
                }
                if ($driverName) {
                    $q->orWhere('driver_name', $driverName);
                }
                // 2. ATAU tugas terbuka dari admin (belum ditentukan nama sopir spesifik, siap diambil oleh sopir pool)
                $q->orWhere(function ($unassigned) {
                    $unassigned->whereNull('driver_id')
                               ->where(function ($sq) {
                                   $sq->whereNull('driver_name')
                                      ->orWhere('driver_name', '');
                               });
                });
            });
        }

        $approvedBookings = $query->get();

        $activeTrips = VehicleBooking::with(['user.division', 'vehicle', 'driver'])
            ->where('status', 'in_progress')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'pending_count' => $approvedBookings->count(),
            'pending' => $approvedBookings,
            'active'  => $activeTrips,
        ]);
    }

    /**
     * Admin Menyetujui atau Menolak Permohonan Peminjaman Kendaraan
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status'      => 'required|in:approved,rejected,pending,completed',
            'vehicle_id'  => 'nullable|exists:vehicles,id',
            'driver_id'   => 'nullable|exists:users,id',
            'driver_name' => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
        ]);

        $booking = VehicleBooking::with(['vehicle', 'user'])->findOrFail($id);
        $booking->status = $validated['status'];
        if (!empty($validated['notes'])) {
            $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . $validated['notes'];
        }

        // Jika disetujui, assign mobil dan pengemudi yang dipilih admin secara dinamis (tanpa penanggung jawab mobil tetap)
        if ($validated['status'] === 'approved') {
            if (!empty($validated['vehicle_id'])) {
                $booking->vehicle_id = $validated['vehicle_id'];
            }

            // Assign driver sesuai pengajuan nama pengemudi dari admin
            if (!empty($validated['driver_id'])) {
                $booking->driver_id = $validated['driver_id'];
                $driverUser = User::find($validated['driver_id']);
                if ($driverUser) {
                    $booking->driver_name = $driverUser->name;
                }
            }
            if (!empty($validated['driver_name'])) {
                $booking->driver_name = $validated['driver_name'];
            }
        }

        $booking->save();
        $booking->load(['vehicle', 'driver', 'user.division']);

        // Kirim notifikasi Telegram saat status disetujui
        if ($validated['status'] === 'approved') {
            try {
                TelegramService::notifyVehicleBookingApproved($booking);
            } catch (\Throwable $t) {
                \Illuminate\Support\Facades\Log::warning('Telegram approve notify error: ' . $t->getMessage());
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Status peminjaman kendaraan berhasil diperbarui: ' . $validated['status'],
            'data'    => $booking
        ]);
    }

    /**
     * Pegawai membuat permohonan booking kendaraan
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'          => 'nullable|exists:vehicles,id',
            'borrower_name'       => 'nullable|string|max:255',
            'borrower_nip'        => 'nullable|string|max:100',
            'borrower_division'   => 'nullable|string|max:255',
            'destination'                 => 'nullable|string|max:1000',
            'destinations'                => 'nullable|array',
            'destinations.*'              => 'nullable|string|max:255',
            'pickup_location'             => 'nullable|string|max:255',
            'pickup_lat'                  => 'nullable|numeric',
            'pickup_lng'                  => 'nullable|numeric',
            'destination_lat'             => 'nullable|numeric',
            'destination_lng'             => 'nullable|numeric',
            'distance_km'                 => 'nullable|numeric',
            'estimated_duration_minutes'  => 'nullable|integer',
            'trip_mode'                   => 'nullable|string|max:50',
            'purpose'                     => 'nullable|string|max:1000',
            'passengers'                  => 'nullable|string|max:255',
            'passengers_count'    => 'nullable|integer|min:1',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'notes'               => 'nullable|string',
            'booking_type'        => 'nullable|in:incidental,routine',
            'is_routine'          => 'nullable|boolean',
            'routine_schedule'    => 'nullable|string|max:500',
            'recurring_frequency' => 'nullable|string|max:100',
            'recurring_days'      => 'nullable',
            'google_maps_url'     => 'nullable|string|max:3000',
        ]);

        $user = $request->user();
        $borrowerName = trim((string)($request->input('borrower_name') ?: ''));
        $borrowerDivision = trim((string)($request->input('borrower_division') ?: ''));
        $borrowerNip = trim((string)($request->input('borrower_nip') ?: ''));
        $reqUserId = $request->input('user_id');

        $userId = null;

        // 1. Jika ada user_id yang valid dikirim dari frontend (bukan 1 / admin)
        if ($reqUserId && $reqUserId != 1) {
            $matched = User::find($reqUserId);
            if ($matched) {
                $userId = $matched->id;
                if (!$borrowerName) $borrowerName = $matched->name;
                if (!$borrowerDivision) $borrowerDivision = $matched->division?->name ?? 'Operasional Kantor';
                if (!$borrowerNip) $borrowerNip = $matched->nip ?? '-';
            }
        }

        // 2. Jika belum resolved dan ada borrowerName spesifik (bukan 'admin')
        if (!$userId && $borrowerName && strtolower($borrowerName) !== 'admin') {
            $matchedByName = User::where('name', $borrowerName)->orWhere('name', 'like', "%{$borrowerName}%")->first();
            if ($matchedByName) {
                $userId = $matchedByName->id;
                if (!$borrowerDivision) $borrowerDivision = $matchedByName->division?->name ?? 'Operasional Kantor';
                if (!$borrowerNip) $borrowerNip = $matchedByName->nip ?? '-';
            }
        }

        // 3. Jika belum resolved, gunakan akun pengguna yang login
        if (!$userId && $user && $user->role !== 'admin' && strtolower($user->name) !== 'admin') {
            $userId = $user->id;
            if (!$borrowerName) $borrowerName = $user->name;
            if (!$borrowerDivision) $borrowerDivision = $user->division?->name ?? 'Operasional Kantor';
            if (!$borrowerNip) $borrowerNip = $user->nip ?? '-';
        }

        // 4. Fallback jika masih belum resolved
        if (!$userId) {
            $firstEmp = User::where('role', 'user')->first();
            $userId = $firstEmp ? $firstEmp->id : 82;
            if (!$borrowerName) $borrowerName = $firstEmp ? $firstEmp->name : 'Pegawai Kantor';
            if (!$borrowerDivision) $borrowerDivision = $firstEmp?->division?->name ?? 'SDM, Umum, dan Komunikasi';
            if (!$borrowerNip) $borrowerNip = $firstEmp?->nip ?? '-';
        }

        // Process dynamic destinations array or single string
        $destList = [];
        if (!empty($validated['destinations']) && is_array($validated['destinations'])) {
            $destList = array_values(array_filter(array_map('trim', $validated['destinations'])));
        }
        if (empty($destList) && !empty($validated['destination'])) {
            $destList = [trim($validated['destination'])];
        }

        $destinationStr = !empty($destList) ? implode(' ➔ ', $destList) : 'Perjalanan Dinas Kantor';
        $purpose = trim((string)($validated['purpose'] ?? ''));

        $count = !empty($validated['passengers_count']) 
            ? (int)$validated['passengers_count']
            : (count(array_filter(explode(',', (string)($validated['passengers'] ?? '')))) ?: 1);

        $notes = $validated['notes'] ?? '';
        if ($purpose) {
            $notes = $notes ? ($purpose . ' | ' . $notes) : $purpose;
        }

        // Process routine booking fields
        $isRoutine = !empty($validated['is_routine']) || ($validated['booking_type'] ?? '') === 'routine';
        $tripMode = $validated['trip_mode'] ?? ($isRoutine ? 'routine' : 'incidental');
        $bookingType = $isRoutine ? 'routine' : 'incidental';
        $recurringDays = '';
        if (!empty($validated['recurring_days'])) {
            $recurringDays = is_array($validated['recurring_days']) ? implode(', ', $validated['recurring_days']) : (string)$validated['recurring_days'];
        }
        $routineSchedule = $validated['routine_schedule'] ?? null;
        if ($isRoutine && empty($routineSchedule)) {
            $freq = $validated['recurring_frequency'] ?? 'Mingguan';
            $days = $recurringDays ?: 'Jadwal Pasti';
            $routineSchedule = "Rutin {$freq} ({$days})";
        }

        // Jika mode ojol_instant, order langsung masuk status approved agar pool driver langsung bisa ambil pesanan!
        $status = ($tripMode === 'ojol_instant') ? 'approved' : 'pending';

        $booking = VehicleBooking::create([
            'user_id'                    => $userId,
            'borrower_name'              => $borrowerName,
            'borrower_nip'               => $borrowerNip,
            'borrower_division'          => $borrowerDivision,
            'vehicle_id'                 => $validated['vehicle_id'] ?? null,
            'destination'                => $destinationStr,
            'pickup_location'            => $validated['pickup_location'] ?? 'Lobby / Kantor Utama',
            'pickup_lat'                 => $validated['pickup_lat'] ?? null,
            'pickup_lng'                 => $validated['pickup_lng'] ?? null,
            'destination_lat'            => $validated['destination_lat'] ?? null,
            'destination_lng'            => $validated['destination_lng'] ?? null,
            'distance_km'                => $validated['distance_km'] ?? null,
            'estimated_duration_minutes' => $validated['estimated_duration_minutes'] ?? null,
            'trip_mode'                  => $tripMode,
            'purpose'                    => $purpose ?: null,
            'start_date'                 => !empty($validated['start_date']) ? $validated['start_date'] : now(),
            'end_date'                   => !empty($validated['end_date']) ? $validated['end_date'] : ($request->input('end_date') ?: null),
            'passengers_count'           => $count,
            'passengers'                 => $validated['passengers'] ?? ($borrowerName . ' & Tim'),
            'status'                     => $status,
            'booking_type'               => $bookingType,
            'is_routine'                 => $isRoutine,
            'routine_schedule'           => $routineSchedule,
            'recurring_frequency'        => $validated['recurring_frequency'] ?? ($isRoutine ? 'weekly' : null),
            'recurring_days'             => $recurringDays ?: null,
            'google_maps_url'            => $request->input('google_maps_url') ?: null,
            'notes'                      => $notes ?: ('Divisi: ' . $borrowerDivision),
        ]);

        $booking->load(['user.division', 'vehicle']);

        // Kirim notifikasi Telegram untuk permohonan booking baru
        try {
            if ($status === 'approved') {
                TelegramService::notifyVehicleBookingApproved($booking);
            } else {
                TelegramService::notifyNewVehicleBooking($booking);
            }
        } catch (\Throwable $t) {
            \Illuminate\Support\Facades\Log::warning('Telegram booking notify error: ' . $t->getMessage());
        }

        if ($tripMode === 'ojol_instant') {
            $successMsg = 'Panggilan driver berhasil dikirim! Titik jemput dan rute Maps telah terhubung langsung ke antrean driver.';
        } elseif ($isRoutine) {
            $successMsg = 'Permohonan booking kunjungan rutin terjadwal berhasil diajukan! Jadwal rutin telah dicatat untuk diverifikasi admin & pool.';
        } else {
            $successMsg = 'Permohonan peminjaman mobil berhasil diajukan! Menunggu persetujuan admin.';
        }

        return response()->json([
            'status'  => 'success',
            'message' => $successMsg,
            'booking' => $booking
        ], 201);
    }

    /**
     * Driver mengambil / mengklaim orderan langsung (Ojol Style)
     */
    public function acceptBooking(Request $request, $id)
    {
        $user = $request->user();
        $booking = VehicleBooking::findOrFail($id);

        $driverName = $user ? $user->name : ($request->input('driver_name') ?: 'Pengemudi Operasional');
        $driverId = $user ? $user->id : $request->input('driver_id');

        $booking->driver_id = $driverId;
        $booking->driver_name = $driverName;
        $booking->status = 'approved';
        $booking->save();
        $booking->load(['vehicle', 'driver', 'user.division']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pesanan berhasil diambil oleh ' . $driverName . '! Siap menuju titik jemput.',
            'booking' => $booking
        ]);
    }

    /**
     * Sopir Scan & Berangkat Mengantarkan Pegawai
     */
    public function depart(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'    => 'required_without:license_plate|nullable|exists:vehicles,id',
            'license_plate' => 'required_without:vehicle_id|nullable|string',
            'booking_id'    => 'nullable|exists:vehicle_bookings,id',
            'passengers'    => 'nullable|string|max:255',
            'division'      => 'nullable|string|max:150',
            'destination'   => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $driverName = $user ? $user->name : 'Pengemudi Operasional';

        $vehicle = null;
        if (!empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
        } elseif (!empty($validated['license_plate'])) {
            $plate = strtoupper(trim($validated['license_plate']));
            $vehicle = Vehicle::where('license_plate', $plate)->first();
        }

        if (!$vehicle) {
            return response()->json(['status' => 'error', 'message' => 'Kendaraan tidak ditemukan'], 404);
        }

        if ($vehicle->status_key === 'departed') {
            return response()->json(['status' => 'error', 'message' => "Kendaraan {$vehicle->name} ({$vehicle->license_plate}) sedang dalam status JALAN oleh {$vehicle->current_driver_name}."], 422);
        }

        // Cek apakah ada booking_id spesifik atau booking yang disetujui/pending terkait kendaraan ini
        $booking = null;
        if (!empty($validated['booking_id'])) {
            $booking = VehicleBooking::with('user.division')->find($validated['booking_id']);
        } else {
            $booking = VehicleBooking::with('user.division')
                ->where('vehicle_id', $vehicle->id)
                ->whereIn('status', ['approved', 'pending'])
                ->latest()
                ->first();
        }

        $destination = !empty($validated['destination']) ? $validated['destination'] : ($booking?->destination ?? 'Perjalanan Dinas Kantor');
        $passengers = !empty($validated['passengers']) ? $validated['passengers'] : ($booking?->passengers ?? ($booking?->user?->name ?? 'Pegawai Kantor'));
        $division = !empty($validated['division']) ? $validated['division'] : ($booking?->user?->division?->name ?? ($user?->division?->name ?? 'Operasional Kantor'));

        // Update status mobil menjadi DEPARTED (Jalan)
        $vehicle->status = 'Sedang Digunakan';
        $vehicle->status_key = 'departed';
        $vehicle->current_driver_id = $user?->id;
        $vehicle->current_driver_name = $driverName;
        $vehicle->current_passengers = $passengers;
        $vehicle->current_division = $division;
        $vehicle->current_destination = $destination;
        $vehicle->trip_started_at = now();
        $vehicle->trip_returned_at = null;
        $vehicle->save();

        if ($booking) {
            $booking->update([
                'vehicle_id'  => $booking->vehicle_id ?: $vehicle->id,
                'status'      => 'in_progress',
                'driver_id'   => $user?->id,
                'driver_name' => $driverName,
                'notes'       => ($booking->notes ? $booking->notes . ' | ' : '') . "Dikonfirmasi Pengemudi: {$driverName}",
            ]);
        } else {
            // Buat record di vehicle_bookings agar masuk laporan
            $firstEmp = User::where('role', 'user')->first();
            VehicleBooking::create([
                'user_id'          => ($user && $user->role === 'user') ? $user->id : ($firstEmp ? $firstEmp->id : 82),
                'borrower_name'    => $passengers ?: ($firstEmp?->name ?? 'Pegawai Kantor'),
                'borrower_division'=> $division ?: ($vehicle->current_division ?: 'Operasional Kantor'),
                'driver_id'        => $user?->id,
                'driver_name'      => $driverName,
                'vehicle_id'       => $vehicle->id,
                'destination'      => $destination,
                'start_date'       => now(),
                'end_date'         => null,
                'passengers_count' => count(array_filter(explode(',', (string)$passengers))) ?: 1,
                'passengers'       => $passengers,
                'status'           => 'in_progress',
                'notes'            => "Pengemudi: {$driverName} | Pegawai: {$passengers} | Divisi: {$vehicle->current_division}",
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Scan Berangkat Berhasil! Kendaraan {$vehicle->name} status di dashboard sekarang: JALAN.",
            'vehicle' => $vehicle,
            'booking' => $booking
        ]);
    }

    /**
     * Sopir Scan Saat Mulai Perjalanan Kembali ke Kantor
     */
    public function returnTrip(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'    => 'required_without:license_plate|nullable|exists:vehicles,id',
            'license_plate' => 'required_without:vehicle_id|nullable|string',
        ]);

        $user = $request->user();

        $vehicle = null;
        if (!empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
        } elseif (!empty($validated['license_plate'])) {
            $plate = strtoupper(trim($validated['license_plate']));
            $vehicle = Vehicle::where('license_plate', $plate)->first();
        }

        if (!$vehicle) {
            return response()->json(['status' => 'error', 'message' => 'Kendaraan tidak ditemukan'], 404);
        }

        // Update status mobil menjadi RETURNING (Pulang ke pool kantor)
        $vehicle->status = 'Perjalanan Pulang';
        $vehicle->status_key = 'returning';
        $vehicle->save();

        return response()->json([
            'status'  => 'success',
            'message' => "Scan Kembali Berhasil! Kendaraan {$vehicle->name} status di dashboard sekarang: PERJALANAN PULANG.",
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Sopir Scan Saat Sudah Sampai di Kantor (Parkir di Pool)
     */
    public function arrive(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'    => 'required_without:license_plate|nullable|exists:vehicles,id',
            'license_plate' => 'required_without:vehicle_id|nullable|string',
        ]);

        $vehicle = null;
        if (!empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
        } elseif (!empty($validated['license_plate'])) {
            $plate = strtoupper(trim($validated['license_plate']));
            $vehicle = Vehicle::where('license_plate', $plate)->first();
        }

        if (!$vehicle) {
            return response()->json(['status' => 'error', 'message' => 'Kendaraan tidak ditemukan'], 404);
        }

        // Tutup active booking jika ada
        VehicleBooking::where('vehicle_id', $vehicle->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first()
            ?->update([
                'status'   => 'completed',
                'end_date' => now()
            ]);

        // Reset status mobil menjadi AVAILABLE (Parkir di Pool Kantor, tanpa sopir tetap)
        $vehicle->status = 'Tersedia';
        $vehicle->status_key = 'available';
        $vehicle->current_destination = 'Pool Kantor';
        $vehicle->current_driver_id = null;
        $vehicle->current_driver_name = null;
        $vehicle->current_passengers = null;
        $vehicle->current_division = null;
        $vehicle->trip_returned_at = now();
        $vehicle->save();

        return response()->json([
            'status'  => 'success',
            'message' => "Scan Sampai Kantor Berhasil! Kendaraan {$vehicle->name} status di dashboard sekarang: POOL KANTOR (Tersedia).",
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Ambil data jadwal kunjungan rutin untuk kalender sopir
     */
    public function routineBookings(Request $request)
    {
        $user = $request->user();
        $driverId = $request->input('driver_id', $user?->id);
        $driverName = $request->input('driver_name', $user?->name);
        $filter = $request->input('filter', 'all'); // 'all', 'mine', 'pool'
        $status = $request->input('status', 'all'); // 'all', 'approved', 'pending'

        $query = VehicleBooking::with(['vehicle', 'driver', 'user.division'])
            ->where(function ($q) {
                $q->where('is_routine', true)
                  ->orWhere('booking_type', 'routine')
                  ->orWhereNotNull('routine_schedule');
            });

        // Filter status jika diminta
        if ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'pending') {
            $query->where('status', 'pending');
        } else {
            // Tampilkan yang aktif/relevan (termasuk completed agar trip selesai terlihat di kalender)
            $query->whereIn('status', ['approved', 'pending', 'in_progress', 'completed']);
        }

        // Filter kepemilikan tugas
        if ($filter === 'mine' && ($driverId || $driverName)) {
            $query->where(function ($q) use ($driverId, $driverName) {
                if ($driverId) $q->where('driver_id', $driverId);
                if ($driverName) $q->orWhere('driver_name', $driverName);
            });
        } elseif ($filter === 'pool') {
            $query->where(function ($q) {
                $q->whereNull('driver_id')
                  ->where(function ($sq) {
                      $sq->whereNull('driver_name')
                         ->orWhere('driver_name', '');
                  });
            });
        }

        $routines = $query->orderBy('start_date', 'asc')->get();

        // Hitung statistik kalender
        $allRoutines = VehicleBooking::where(function ($q) {
            $q->where('is_routine', true)
              ->orWhere('booking_type', 'routine')
              ->orWhereNotNull('routine_schedule');
        })->whereIn('status', ['approved', 'pending', 'in_progress', 'completed'])->get();

        $myCount = 0;
        $poolCount = 0;
        foreach ($allRoutines as $item) {
            $isMine = ($driverId && $item->driver_id == $driverId) || ($driverName && $item->driver_name === $driverName);
            if ($isMine) {
                $myCount++;
            } elseif (empty($item->driver_id) && empty($item->driver_name)) {
                $poolCount++;
            }
        }

        // Ambil permohonan insidental terjadwal (semua driver & pool, agar sopir dapat melihat seluruh agenda dinas)
        $incidentalQuery = VehicleBooking::with(['vehicle', 'driver', 'user.division'])
            ->where(function ($q) {
                $q->where('is_routine', false)
                  ->orWhereNull('is_routine');
            })
            ->where(function ($q) {
                $q->where('booking_type', '!=', 'routine')
                  ->orWhereNull('booking_type');
            })
            ->whereIn('status', ['approved', 'in_progress', 'completed']);

        if ($filter === 'mine' && ($driverId || $driverName)) {
            $incidentalQuery->where(function ($q) use ($driverId, $driverName) {
                if ($driverId) $q->where('driver_id', $driverId);
                if ($driverName) $q->orWhere('driver_name', $driverName);
            });
        } elseif ($filter === 'pool') {
            $incidentalQuery->where(function ($q) {
                $q->whereNull('driver_id')
                  ->where(function ($sq) {
                      $sq->whereNull('driver_name')
                         ->orWhere('driver_name', '');
                  });
            });
        }

        $incidentals = $incidentalQuery->orderBy('start_date', 'asc')
            ->limit(150)
            ->get();

        return response()->json([
            'status'      => 'success',
            'data'        => $routines,
            'incidentals' => $incidentals,
            'summary'     => [
                'total_routines' => $allRoutines->count(),
                'my_routines'    => $myCount,
                'pool_routines'  => $poolCount,
            ]
        ]);
    }
}
