<?php

namespace App\Http\Controllers;

use App\Models\CleaningTool;
use App\Models\CleaningRequest;
use App\Models\Product;
use App\Models\Room;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Ambil ringkasan seluruh asset untuk Manajemen Alat
    public function all()
    {
        return response()->json([
            'atk' => Product::latest()->get(),
            'rooms' => Room::latest()->get(),
            'vehicles' => Vehicle::latest()->get(),
            'cleaning_tools' => CleaningTool::latest()->get(),
        ]);
    }

    // ==================== RUANGAN ====================
    public function getRooms(Request $request)
    {
        $query = Room::query();
        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('floor', 'like', "%{$s}%");
            });
        }
        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        $rooms = $query->orderBy('id', 'asc')->get();

        $now = now();
        foreach ($rooms as $room) {
            if ($room->status === 'Pemeliharaan') {
                continue;
            }

            // Cek apakah ada jadwal peminjaman yang telah disetujui untuk hari ini
            $activeBooking = \App\Models\RoomBooking::with(['user.division'])
                ->where('room_id', $room->id)
                ->where('status', 'approved')
                ->where(function ($q) use ($now) {
                    $q->where(function ($sub) use ($now) {
                        $sub->where('start_time', '<=', $now)
                            ->where('end_time', '>=', $now);
                    })
                    ->orWhereDate('start_time', $now->toDateString())
                    ->orWhereDate('created_at', $now->toDateString());
                })
                ->latest('id')
                ->first();

            if ($activeBooking) {
                $room->status = 'Sedang Digunakan';
                $room->current_user = $activeBooking->user?->name ?? 'Pegawai Instansi';
                $room->current_division = $activeBooking->user?->division?->name ?? 'Divisi Umum';
                $room->current_agenda = $activeBooking->agenda ?? 'Rapat Kedinasan';

                $st = $activeBooking->start_time ? \Carbon\Carbon::parse($activeBooking->start_time)->format('H:i') : '09:00';
                $et = $activeBooking->end_time ? \Carbon\Carbon::parse($activeBooking->end_time)->format('H:i') : '11:30';
                $room->current_time = "{$st} - {$et} WIB";
                $room->active_booking_id = $activeBooking->id;
            } else {
                if ($room->status !== 'Sedang Digunakan') {
                    $room->status = 'Tersedia';
                }
            }
        }

        return response()->json($rooms);
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|unique:rooms,code|max:50',
            'name'        => 'required|string|unique:rooms,name|max:150',
            'capacity'    => 'required|integer|min:1',
            'floor'       => 'required|string|max:100',
            'facilities'  => 'nullable|string',
            'status'      => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ], [
            'name.unique' => 'Nama ruangan sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'code.unique' => 'Kode ruangan sudah terdaftar dalam sistem.',
        ]);

        $room = Room::create($validated);
        return response()->json(['message' => 'Ruangan berhasil ditambahkan', 'data' => $room], 201);
    }

    public function updateRoom(Request $request, Room $room)
    {
        $validated = $request->validate([
            'code'        => 'sometimes|string|max:50|unique:rooms,code,' . $room->id,
            'name'        => 'sometimes|string|max:150|unique:rooms,name,' . $room->id,
            'capacity'    => 'sometimes|integer|min:1',
            'floor'       => 'sometimes|string|max:100',
            'facilities'  => 'nullable|string',
            'status'      => 'sometimes|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ], [
            'name.unique' => 'Nama ruangan sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'code.unique' => 'Kode ruangan sudah digunakan oleh ruangan lain.',
        ]);

        $room->update($validated);

        // Jika ruangan dibuka / tersedia kembali, selesaikan booking aktif ruangan tersebut
        if (isset($validated['status']) && $validated['status'] === 'Tersedia') {
            \App\Models\RoomBooking::where('room_id', $room->id)
                ->where('status', 'approved')
                ->update(['status' => 'completed']);
        }

        return response()->json(['message' => 'Ruangan berhasil diperbarui', 'data' => $room]);
    }

    public function destroyRoom(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Ruangan berhasil dihapus']);
    }

    // ==================== MOBIL / KENDARAAN ====================
    public function getVehicles(Request $request)
    {
        $query = Vehicle::query();
        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('license_plate', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%");
            });
        }
        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        $vehicles = $query->orderBy('id', 'asc')->get();

        foreach ($vehicles as $v) {
            // Jika mobil sedang di pool atau tidak ada trip aktif, cek apakah ada jadwal booking yang sudah disetujui admin
            if (empty($v->status_key) || $v->status_key === 'available') {
                $approvedBooking = \App\Models\VehicleBooking::with(['user.division', 'driver'])
                    ->where('vehicle_id', $v->id)
                    ->where('status', 'approved')
                    ->latest('id')
                    ->first();

                if ($approvedBooking) {
                    $v->has_scheduled_booking = true;
                    $v->scheduled_destination = $approvedBooking->destination;
                    $v->scheduled_division = $approvedBooking->user?->division?->name ?? 'Operasional';
                    $v->scheduled_driver = $approvedBooking->driver?->name ?? $approvedBooking->driver_name ?? $v->current_driver_name ?? 'Pengemudi Siaga';
                    $v->scheduled_user = $approvedBooking->user?->name ?? 'Pegawai';
                    $v->scheduled_time = $approvedBooking->start_date ? \Carbon\Carbon::parse($approvedBooking->start_date)->format('H:i') . ' WIB' : 'Hari Ini';
                }
            }
        }

        return response()->json($vehicles);
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:50',
            'name'          => 'required|string|unique:vehicles,name|max:150',
            'brand'         => 'required|string|max:100',
            'transmission'  => 'required|string|max:50',
            'capacity'      => 'required|integer|min:1',
            'fuel_type'     => 'required|string|max:50',
            'status'        => 'required|string',
            'status_key'    => 'nullable|string',
            'color'         => 'nullable|string',
            'body_color'    => 'nullable|string',
            'description'   => 'nullable|string',
            'image'         => 'nullable|string',
        ], [
            'license_plate.unique' => 'Plat nomor kendaraan sudah terdaftar dalam sistem.',
            'name.unique'          => 'Nama kendaraan sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
        ]);

        $vehicle = Vehicle::create($validated);
        return response()->json(['message' => 'Kendaraan berhasil ditambahkan', 'data' => $vehicle], 201);
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate'        => 'sometimes|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
            'name'                 => 'sometimes|string|max:150|unique:vehicles,name,' . $vehicle->id,
            'brand'                => 'sometimes|string|max:100',
            'transmission'         => 'sometimes|string|max:50',
            'capacity'             => 'sometimes|integer|min:1',
            'fuel_type'            => 'sometimes|string|max:50',
            'status'               => 'sometimes|string',
            'status_key'           => 'nullable|string',
            'color'                => 'nullable|string',
            'body_color'           => 'nullable|string',
            'current_destination'  => 'nullable|string',
            'current_driver_name'  => 'nullable|string',
            'current_passengers'   => 'nullable|string',
            'current_division'     => 'nullable|string',
            'description'          => 'nullable|string',
            'image'                => 'nullable|string',
        ], [
            'license_plate.unique' => 'Plat nomor kendaraan sudah digunakan oleh kendaraan lain.',
            'name.unique'          => 'Nama kendaraan sudah digunakan oleh kendaraan lain.',
        ]);

        $vehicle->update($validated);
        return response()->json(['message' => 'Kendaraan berhasil diperbarui', 'data' => $vehicle]);
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        $vehicle->delete();
        return response()->json(['message' => 'Kendaraan berhasil dihapus']);
    }

    // ==================== CLEANING SERVICE ====================
    public function getCleaningTools(Request $request)
    {
        $query = CleaningTool::query();
        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%");
            });
        }
        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        return response()->json($query->latest()->get());
    }

    public function storeCleaningTool(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|unique:cleaning_tools,code|max:50',
            'name'        => 'required|string|unique:cleaning_tools,name|max:150',
            'category'    => 'required|string|max:100',
            'quantity'    => 'required|integer|min:1',
            'unit'        => 'required|string|max:50',
            'location'    => 'required|string|max:150',
            'status'      => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ], [
            'name.unique' => 'Nama alat kebersihan sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'code.unique' => 'Kode alat kebersihan sudah terdaftar dalam sistem.',
        ]);

        $tool = CleaningTool::create($validated);
        return response()->json(['message' => 'Alat cleaning service berhasil ditambahkan', 'data' => $tool], 201);
    }

    public function updateCleaningTool(Request $request, CleaningTool $tool)
    {
        $validated = $request->validate([
            'code'        => 'sometimes|string|max:50|unique:cleaning_tools,code,' . $tool->id,
            'name'        => 'sometimes|string|max:150|unique:cleaning_tools,name,' . $tool->id,
            'category'    => 'sometimes|string|max:100',
            'quantity'    => 'sometimes|integer|min:1',
            'unit'        => 'sometimes|string|max:50',
            'location'    => 'sometimes|string|max:150',
            'status'      => 'sometimes|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ], [
            'name.unique' => 'Nama alat kebersihan sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'code.unique' => 'Kode alat kebersihan sudah digunakan oleh alat lain.',
        ]);

        $tool->update($validated);
        return response()->json(['message' => 'Alat cleaning service berhasil diperbarui', 'data' => $tool]);
    }

    public function destroyCleaningTool(CleaningTool $tool)
    {
        $tool->delete();
        return response()->json(['message' => 'Alat cleaning service berhasil dihapus']);
    }

    /**
     * Ambil riwayat peminjaman / pengambilan alat kebersihan oleh OB
     */
    public function getCleaningRequests(Request $request)
    {
        $user = $request->user();
        $userId = $request->input('user_id', $user?->id);

        $query = CleaningRequest::with(['user', 'cleaningTool'])->latest();
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return response()->json($query->limit(50)->get());
    }

    /**
     * Catat scan dan pengambilan alat kebersihan oleh OB
     */
    public function storeCleaningRequest(Request $request)
    {
        $user = $request->user();
        $userId = $request->input('user_id', $user?->id ?? 4);

        $validated = $request->validate([
            'cleaning_tool_id' => 'required|exists:cleaning_tools,id',
            'location'         => 'required|string|max:150',
            'service_type'     => 'nullable|string|max:150',
            'notes'            => 'nullable|string',
            'quantity'         => 'nullable|integer|min:1',
        ]);

        $tool = CleaningTool::find($validated['cleaning_tool_id']);

        $cleaningRequest = CleaningRequest::create([
            'user_id'          => $userId,
            'cleaning_tool_id' => $tool->id,
            'service_type'     => $validated['service_type'] ?? 'Pembersihan Ruangan & Sanitasi',
            'location'         => $validated['location'],
            'requested_date'   => now(),
            'status'           => 'completed',
            'notes'            => $validated['notes'] ?? ('Pengambilan alat kebersihan oleh petugas OB'),
        ]);

        // Kurangi stok jika tersedia
        if ($tool) {
            $qtyToDeduct = $validated['quantity'] ?? 1;
            if ($tool->quantity >= $qtyToDeduct) {
                $tool->decrement('quantity', $qtyToDeduct);
            }
            if ($tool->quantity == 0) {
                $tool->status = 'Sedang Digunakan';
                $tool->save();
            }
        }

        $cleaningRequest->load(['cleaningTool', 'user']);

        return response()->json([
            'status'  => 'success',
            'message' => "Pengambilan alat kebersihan {$tool?->name} berhasil dicatat!",
            'data'    => $cleaningRequest
        ], 201);
    }

    /**
     * Upload foto barang (ATK, Ruangan, Mobil, Kebersihan)
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image'    => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'category' => 'nullable|string',
        ]);

        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension() ?: 'png';
        $cat = $request->input('category', 'general');
        $safeCat = in_array($cat, ['atk', 'rooms', 'vehicles', 'cleaning', 'avatars', 'profile']) ? $cat : 'uploads';

        $fileName = $safeCat . '_' . time() . '_' . uniqid() . '.' . $ext;

        $backendUploadDir = public_path('images/uploads');
        if (!file_exists($backendUploadDir)) {
            @mkdir($backendUploadDir, 0755, true);
        }
        $file->move($backendUploadDir, $fileName);

        $frontendUploadDir = base_path('../frontend-app/public/images/uploads');
        if (!file_exists($frontendUploadDir)) {
            @mkdir($frontendUploadDir, 0755, true);
        }
        @copy($backendUploadDir . '/' . $fileName, $frontendUploadDir . '/' . $fileName);

        $url = '/images/uploads/' . $fileName;

        return response()->json([
            'status'  => 'success',
            'message' => 'Gambar berhasil diunggah',
            'url'     => $url,
        ]);
    }

    // ==================== PEMINJAMAN RUANGAN (ROOM BOOKINGS) ====================
    public function getRoomBookings(Request $request)
    {
        $query = \App\Models\RoomBooking::with(['room', 'user.division'])->latest();
        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        return response()->json([
            'status' => 'success',
            'data'   => $query->get()
        ]);
    }

    public function userRoomBookings($userId)
    {
        $bookings = \App\Models\RoomBooking::with(['room', 'user.division'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $bookings
        ]);
    }

    public function storeRoomBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'agenda'     => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time'   => 'nullable|date',
            'attendees'  => 'nullable|integer',
            'notes'      => 'nullable|string',
        ]);

        $user = $request->user();
        $userId = $request->input('user_id', $user ? $user->id : 1);

        $notes = $validated['notes'] ?? '';
        if (!empty($validated['attendees'])) {
            $notes = "Peserta: {$validated['attendees']} orang" . ($notes ? " | {$notes}" : '');
        }

        $booking = \App\Models\RoomBooking::create([
            'user_id'    => $userId,
            'room_id'    => $validated['room_id'],
            'agenda'     => $validated['agenda'],
            'start_time' => !empty($validated['start_time']) ? $validated['start_time'] : now(),
            'end_time'   => !empty($validated['end_time']) ? $validated['end_time'] : now()->addHours(2),
            'status'     => 'pending',
            'notes'      => $notes,
        ]);

        $booking->load(['room', 'user.division']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Permohonan peminjaman ruangan berhasil diajukan! Menunggu persetujuan admin.',
            'booking' => $booking,
            'data'    => $booking
        ], 201);
    }

    public function updateRoomBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending,completed',
            'notes'  => 'nullable|string',
        ]);

        $booking = \App\Models\RoomBooking::with(['room', 'user'])->findOrFail($id);
        $booking->status = $validated['status'];
        if (!empty($validated['notes'])) {
            $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . $validated['notes'];
        }
        $booking->save();

        // Sinkronisasi status ruangan fisik secara otomatis
        if ($booking->room) {
            if ($validated['status'] === 'approved') {
                $booking->room->update(['status' => 'Sedang Digunakan']);
            } elseif (in_array($validated['status'], ['completed', 'rejected'])) {
                $hasOtherActive = \App\Models\RoomBooking::where('room_id', $booking->room_id)
                    ->where('id', '!=', $booking->id)
                    ->where('status', 'approved')
                    ->where('end_time', '>=', now())
                    ->exists();

                if (!$hasOtherActive && $booking->room->status !== 'Pemeliharaan') {
                    $booking->room->update(['status' => 'Tersedia']);
                }
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Status peminjaman ruangan berhasil diperbarui: ' . $validated['status'],
            'data'    => $booking
        ]);
    }
}
