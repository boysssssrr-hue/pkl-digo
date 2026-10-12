<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DriverTripController;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Token / Login)
|--------------------------------------------------------------------------
*/
Route::post('/login', [ApiController::class, 'login'])->name('login');
Route::post('/check-user-reset', [ApiController::class, 'checkUserForReset']);
Route::post('/reset-password', [ApiController::class, 'resetPassword']);

// --- Akses Data Alat & Fasilitas (Read Public untuk Tampilan Real-Time) ---
Route::get('/assets/all', [AssetController::class, 'all']);
Route::get('/products', [ApiController::class, 'products']);
Route::get('/products/barcode/{barcode}', [ApiController::class, 'productByBarcode']);
Route::get('/rooms', [AssetController::class, 'getRooms']);
Route::get('/vehicles', [AssetController::class, 'getVehicles']);
Route::get('/drivers', function () {
    return response()->json(\App\Models\User::where('role', 'sopir')->select('id', 'name', 'nip', 'status')->get());
});
Route::get('/cleaning-tools', [AssetController::class, 'getCleaningTools']);
Route::get('/driver/bookings', [DriverTripController::class, 'bookings']);
Route::get('/driver/routine-bookings', [DriverTripController::class, 'routineBookings']);
Route::get('/driver/history', [DriverTripController::class, 'history']);
Route::post('/upload-image', [AssetController::class, 'uploadImage']);
Route::post('/profile/photo', [ApiController::class, 'updateAvatar']);
Route::post('/profile/update', [ApiController::class, 'updateProfile']);

// --- Akses Riwayat & Limit User Personal (Public Fallback) ---
Route::get('/users/{user}/history', [ApiController::class, 'history']);
Route::get('/users/{user}/limits', [ApiController::class, 'divisionLimits']);
Route::get('/users/{user}/vehicle-bookings', [DriverTripController::class, 'userBookings']);
Route::get('/users/{user}/frequent-destinations', [DriverTripController::class, 'frequentDestinations']);
Route::get('/users/{user}/room-bookings', [AssetController::class, 'userRoomBookings']);
Route::post('/borrowings', [ApiController::class, 'storeBorrowing']);
Route::get('/cleaning-requests', [AssetController::class, 'getCleaningRequests']);
Route::post('/cleaning-requests', [AssetController::class, 'storeCleaningRequest']);
Route::get('/users/{user}/cleaning-history', [AssetController::class, 'getCleaningRequests']);

// --- Peminjaman Fasilitas & Approval Status (Public & Direct Access) ---
Route::get('/room-bookings', [AssetController::class, 'getRoomBookings']);
Route::post('/room-bookings', [AssetController::class, 'storeRoomBooking']);
Route::patch('/room-bookings/{id}/status', [AssetController::class, 'updateRoomBookingStatus']);
Route::post('/vehicle-bookings', [DriverTripController::class, 'storeBooking']);
Route::patch('/vehicle-bookings/{id}/status', [DriverTripController::class, 'updateBookingStatus']);
Route::post('/driver/bookings/{id}/accept', [DriverTripController::class, 'acceptBooking']);
Route::post('/driver/trip/depart', [DriverTripController::class, 'depart']);
Route::post('/driver/trip/return', [DriverTripController::class, 'returnTrip']);
Route::post('/driver/trip/arrive', [DriverTripController::class, 'arrive']);

// --- Laporan Aktivitas Seluruh User (Connected to Database) ---
Route::get('/reports/all', [ReportController::class, 'all']);

// --- Manajemen Pengguna (Akses Data & Manajemen Admin) ---
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::put('/users/{id}/password', [UserController::class, 'updatePassword']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// --- Manajemen Divisi & Kuota ATK 6 Bulan ---
Route::get('/divisions', [DivisionController::class, 'index']);
Route::post('/divisions', [DivisionController::class, 'store']);
Route::put('/divisions/{id}', [DivisionController::class, 'update']);
Route::delete('/divisions/{id}', [DivisionController::class, 'destroy']);
Route::post('/divisions/{id}/limits', [DivisionController::class, 'setLimit']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Wajib Header: Authorization: Bearer <token>)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // --- 1. Autentikasi & Profil ---
    Route::get('/me', [ApiController::class, 'me']);
    Route::post('/logout', [ApiController::class, 'logout']);

    // --- 2. Produk (Products) ---
    Route::post('/products', [ApiController::class, 'storeProduct']);
    Route::put('/products/{product}', [ApiController::class, 'updateProduct']);
    Route::delete('/products/{product}', [ApiController::class, 'destroyProduct']);

    // --- 3. Peminjaman ATK (Borrowings) ---
    Route::get('/borrowings', [ApiController::class, 'borrowings']);
    Route::get('/borrowings/{borrowing}', [ApiController::class, 'showBorrowing']);
    Route::post('/borrowings', [ApiController::class, 'storeBorrowing']);
    Route::patch('/borrowings/{borrowing}/status', [ApiController::class, 'updateBorrowingStatus']);



    // --- 6. Mutasi Data Fasilitas & Alat (Rooms, Vehicles, Cleaning Tools) ---
    Route::post('/rooms', [AssetController::class, 'storeRoom']);
    Route::put('/rooms/{room}', [AssetController::class, 'updateRoom']);
    Route::delete('/rooms/{room}', [AssetController::class, 'destroyRoom']);

    Route::post('/vehicles', [AssetController::class, 'storeVehicle']);
    Route::put('/vehicles/{vehicle}', [AssetController::class, 'updateVehicle']);
    Route::delete('/vehicles/{vehicle}', [AssetController::class, 'destroyVehicle']);

    Route::post('/cleaning-tools', [AssetController::class, 'storeCleaningTool']);
    Route::put('/cleaning-tools/{tool}', [AssetController::class, 'updateCleaningTool']);
    Route::delete('/cleaning-tools/{tool}', [AssetController::class, 'destroyCleaningTool']);

    // --- 7. Aktivitas Sopir / Driver Real-Time Tracking & Notifikasi Booking ---
    Route::get('/driver/my-trip', [DriverTripController::class, 'myTrip']);
    Route::get('/driver/history', [DriverTripController::class, 'history']);
    Route::post('/driver/trip/depart', [DriverTripController::class, 'depart']);
    Route::post('/driver/trip/return', [DriverTripController::class, 'returnTrip']);
    Route::post('/driver/trip/arrive', [DriverTripController::class, 'arrive']);
    Route::post('/vehicle-bookings', [DriverTripController::class, 'storeBooking']);
    Route::patch('/vehicle-bookings/{id}/status', [DriverTripController::class, 'updateBookingStatus']);
    Route::post('/room-bookings', [AssetController::class, 'storeRoomBooking']);
    Route::patch('/room-bookings/{id}/status', [AssetController::class, 'updateRoomBookingStatus']);
});

// Fallback CRUD routes without strict auth for seamless local admin dashboard management
Route::prefix('v1/manage')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::post('/divisions', [DivisionController::class, 'store']);
    Route::put('/divisions/{id}', [DivisionController::class, 'update']);
    Route::delete('/divisions/{id}', [DivisionController::class, 'destroy']);
    Route::post('/divisions/{id}/limits', [DivisionController::class, 'setLimit']);

    Route::post('/rooms', [AssetController::class, 'storeRoom']);
    Route::put('/rooms/{room}', [AssetController::class, 'updateRoom']);
    Route::delete('/rooms/{room}', [AssetController::class, 'destroyRoom']);

    Route::post('/vehicles', [AssetController::class, 'storeVehicle']);
    Route::put('/vehicles/{vehicle}', [AssetController::class, 'updateVehicle']);
    Route::delete('/vehicles/{vehicle}', [AssetController::class, 'destroyVehicle']);

    Route::post('/cleaning-tools', [AssetController::class, 'storeCleaningTool']);
    Route::put('/cleaning-tools/{tool}', [AssetController::class, 'updateCleaningTool']);
    Route::delete('/cleaning-tools/{tool}', [AssetController::class, 'destroyCleaningTool']);

    Route::get('/products', [ApiController::class, 'products']);
    Route::get('/products/barcode/{barcode}', [ApiController::class, 'productByBarcode']);
    Route::post('/products', [ApiController::class, 'storeProduct']);
    Route::put('/products/{product}', [ApiController::class, 'updateProduct']);
    Route::delete('/products/{product}', [ApiController::class, 'destroyProduct']);

    Route::post('/upload-image', [AssetController::class, 'uploadImage']);

    // Public / Fallback Driver Trip & Booking endpoints for rapid local test
    Route::get('/driver/my-trip', [DriverTripController::class, 'myTrip']);
    Route::get('/driver/history', [DriverTripController::class, 'history']);
    Route::get('/driver/bookings', [DriverTripController::class, 'bookings']);
    Route::post('/driver/trip/depart', [DriverTripController::class, 'depart']);
    Route::post('/driver/trip/return', [DriverTripController::class, 'returnTrip']);
    Route::post('/driver/trip/arrive', [DriverTripController::class, 'arrive']);
    Route::post('/vehicle-bookings', [DriverTripController::class, 'storeBooking']);
    Route::patch('/vehicle-bookings/{id}/status', [DriverTripController::class, 'updateBookingStatus']);

    Route::get('/room-bookings', [AssetController::class, 'getRoomBookings']);
    Route::post('/room-bookings', [AssetController::class, 'storeRoomBooking']);
    Route::patch('/room-bookings/{id}/status', [AssetController::class, 'updateRoomBookingStatus']);
    Route::get('/users/{user}/room-bookings', [AssetController::class, 'userRoomBookings']);

    Route::get('/users/{user}/history', [ApiController::class, 'history']);
    Route::get('/users/{user}/vehicle-bookings', [DriverTripController::class, 'userBookings']);
    Route::get('/users/{user}/frequent-destinations', [DriverTripController::class, 'frequentDestinations']);
    Route::post('/borrowings', [ApiController::class, 'storeBorrowing']);
    Route::get('/cleaning-requests', [AssetController::class, 'getCleaningRequests']);
    Route::post('/cleaning-requests', [AssetController::class, 'storeCleaningRequest']);
    Route::get('/users/{user}/cleaning-history', [AssetController::class, 'getCleaningRequests']);
});