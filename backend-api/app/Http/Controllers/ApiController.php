<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Division;
use App\Models\DivisionItemLimit;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Autentikasi (Auth)
    |--------------------------------------------------------------------------
    */

    // Login menggunakan NPP / Username / NIP
    public function login(Request $request)
    {
        $identifier = trim($request->input('nip') ?? $request->input('username') ?? $request->input('npp') ?? '');

        if (empty($identifier)) {
            throw ValidationException::withMessages([
                'nip' => ['Kolom NPP / Username wajib diisi.'],
            ]);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $user = User::with('division')
            ->where(function ($q) use ($identifier) {
                $q->where('nip', $identifier)
                  ->orWhere('name', $identifier);
            })
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nip' => ['NPP / Username atau password yang Anda masukkan salah.'],
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Akun Anda sedang tidak aktif.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ]);
    }

    // Verifikasi identitas user untuk Lupa Password
    public function checkUserForReset(Request $request)
    {
        $identifier = trim($request->input('nip') ?? $request->input('username') ?? '');
        if (empty($identifier)) {
            return response()->json(['message' => 'NPP / NIP atau Username wajib diisi.'], 422);
        }

        $user = User::with('division')
            ->where(function ($q) use ($identifier) {
                $q->where('nip', $identifier)
                  ->orWhere('name', $identifier);
            })
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Pengguna dengan NIP / Username tersebut tidak ditemukan.'], 404);
        }

        return response()->json([
            'status'   => 'success',
            'user_id'  => $user->id,
            'name'     => $user->name,
            'nip'      => $user->nip,
            'division' => $user->division ? $user->division->name : 'Umum',
            'role'     => $user->role,
        ]);
    }

    // Atur ulang password pengguna
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nip'                   => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'password.min'          => 'Password minimal harus 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $identifier = trim($request->nip);
        $user = User::where('nip', $identifier)->orWhere('name', $identifier)->first();

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->visible_password = $request->password;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kata sandi berhasil diperbarui. Silakan masuk menggunakan kata sandi baru Anda.'
        ]);
    }

    // Ambil data user yang sedang login
    public function me(Request $request)
    {
        return response()->json($request->user()->load('division'));
    }

    // Logout dan hapus token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil logout']);
    }

    // Update foto profil user (Hanya foto profil yang dapat diubah)
    public function updateAvatar(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            if ($request->filled('user_id')) {
                $user = User::find($request->user_id);
            } elseif ($request->filled('nip')) {
                $user = User::where('nip', $request->nip)->first();
            }
        }

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sesi tidak valid atau pengguna tidak ditemukan.'
            ], 404);
        }

        $avatarUrl = null;

        // Jika user mengunggah file langsung
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            ]);

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $ext;

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

            $avatarUrl = '/images/uploads/' . $fileName;
        } elseif ($request->filled('avatar')) {
            $avatarUrl = $request->input('avatar');
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Foto profil belum dipilih atau format tidak valid.'
            ], 422);
        }

        $user->avatar = $avatarUrl;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto profil berhasil diperbarui.',
            'avatar'  => $avatarUrl,
            'user'    => $user->load('division'),
        ]);
    }

    // Update profil lengkap (Admin dapat mengubah foto profil, nama, NIP, dan password)
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            if ($request->filled('user_id')) {
                $user = User::find($request->user_id);
            } elseif ($request->filled('nip_origin')) {
                $user = User::where('nip', $request->nip_origin)->first();
            } elseif ($request->filled('nip')) {
                $user = User::where('nip', $request->nip)->first();
            }
        }

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sesi tidak valid atau pengguna tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:150|unique:users,name,' . $user->id,
            'nip'      => 'sometimes|string|max:50|unique:users,nip,' . $user->id,
            'password' => 'nullable|string|min:4',
            'avatar'   => 'nullable|string',
        ], [
            'name.unique'  => 'Nama pengguna sudah digunakan oleh akun lain.',
            'nip.unique'   => 'NIP sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal terdiri dari 4 karakter.',
        ]);

        // Jika mengunggah berkas foto langsung
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            ]);

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension() ?: 'png';
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $ext;

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

            $user->avatar = '/images/uploads/' . $fileName;
        } elseif ($request->has('avatar')) {
            $user->avatar = $request->avatar ?: null;
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('nip')) {
            $user->nip = $request->nip;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->visible_password = $request->password;
        }

        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'user'    => $user->load('division'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Produk (Products)
    |--------------------------------------------------------------------------
    */

    // Ambil daftar produk (bisa difilter pencarian / status)
    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('id', 'asc')->get());
    }

    // Ambil produk spesifik berdasarkan Barcode
    public function productByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk dengan barcode tersebut tidak ditemukan.'], 404);
        }

        return response()->json($product);
    }

    // Tambah Produk Baru
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'barcode'     => 'required|string|unique:products,barcode|max:100',
            'name'        => 'required|string|unique:products,name|max:150',
            'category'    => 'required|string|max:100',
            'unit'        => 'required|string|max:30',
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:available,low_stock,out_of_stock',
            'image'       => 'nullable|string'
        ], [
            'name.unique'    => 'Nama barang ATK sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'barcode.unique' => 'Kode barcode ATK sudah terdaftar dalam sistem.',
        ]);

        $product = Product::create($validated);
        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $product], 201);
    }

    // Edit Produk
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode'     => 'sometimes|string|max:100|unique:products,barcode,' . $product->id,
            'name'        => 'sometimes|string|max:150|unique:products,name,' . $product->id,
            'category'    => 'sometimes|string|max:100',
            'unit'        => 'sometimes|string|max:30',
            'description' => 'nullable|string',
            'stock'       => 'sometimes|integer|min:0',
            'status'      => 'sometimes|in:available,low_stock,out_of_stock',
            'image'       => 'nullable|string'
        ], [
            'name.unique'    => 'Nama barang ATK sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'barcode.unique' => 'Kode barcode ATK sudah digunakan oleh barang lain.',
        ]);

        $product->update($validated);
        return response()->json(['message' => 'Produk berhasil diperbarui', 'data' => $product]);
    }

    // Hapus Produk
    public function destroyProduct(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Peminjaman (Borrowings)
    |--------------------------------------------------------------------------
    */

    // Ambil daftar semua pengajuan peminjaman
    public function borrowings(Request $request)
    {
        $user = $request->user();
        $query = Borrowing::with(['user', 'division', 'items.product']);

        // Jika role user biasa, hanya tampilkan peminjaman miliknya sendiri
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return response()->json($query->latest()->get());
    }

    // Detail Peminjaman
    public function showBorrowing(Borrowing $borrowing)
    {
        return response()->json($borrowing->load(['user', 'division', 'items.product']));
    }

    // Ajukan Peminjaman Baru
    public function storeBorrowing(Request $request)
    {
        $user = $request->user();
        if (!$user && $request->has('user_id')) {
            $user = User::find($request->input('user_id'));
        }
        $isOb = $user && $user->role === 'ob';

        $divisionId = $user ? $user->division_id : null;
        if (!$divisionId) {
            $divisionId = Division::first()?->id ?? 1;
        }

        if (!$user && !$divisionId) {
            return response()->json(['message' => 'Anda belum terhubung ke divisi mana pun.'], 400);
        }

        $request->validate([
            'request_note'       => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $user, $isOb, $divisionId) {
            // Check kuota limit per barang untuk divisi user jika bukan OB
            if (!$isOb) {
                foreach ($request->items as $item) {
                    $limit = DivisionItemLimit::where('division_id', $divisionId)
                        ->where('product_id', $item['product_id'])
                        ->first();

                    if ($limit && $item['quantity'] > $limit->quantity_limit) {
                        $product = Product::find($item['product_id']);
                        return response()->json([
                            'message' => "Jumlah pinjam untuk {$product->name} melebihi batas kuota divisi ({$limit->quantity_limit} {$product->unit})."
                        ], 422);
                    }
                }
            }

            // Cek ketersediaan stok sebelum barang diambil
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stok {$product->name} tidak mencukupi (Tersedia: {$product->stock} {$product->unit})."
                    ], 400);
                }
            }

            // Buat record borrowing: Langsung berstatus 'completed' (Selesai) tanpa menunggu approval
            $borrowing = Borrowing::create([
                'user_id'      => $user ? $user->id : 1,
                'division_id'  => $divisionId,
                'status'       => 'completed',
                'request_note' => $request->request_note,
                'requested_at' => now(),
                'approved_at'  => now(),
                'completed_at' => now(),
            ]);

            // Simpan detail items dan langsung potong stok barang
            foreach ($request->items as $item) {
                BorrowingItem::create([
                    'borrowing_id' => $borrowing->id,
                    'product_id'   => $item['product_id'],
                    'quantity'     => $item['quantity'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);

                    if ($product->stock == 0) {
                        $product->update(['status' => 'out_of_stock']);
                    } elseif ($product->stock <= 10) {
                        $product->update(['status' => 'low_stock']);
                    }
                }
            }

            $message = 'Pengambilan barang ATK berhasil dicatat dan langsung selesai.';

            return response()->json([
                'message' => $message,
                'data'    => $borrowing->load('items.product')
            ], 201);
        });
    }

    // Admin Update Status Peminjaman (Approved, Rejected, Completed)
    public function updateBorrowingStatus(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed'
        ]);

        return DB::transaction(function () use ($request, $borrowing) {
            $newStatus = $request->status;

            // Jika disetujui (Approved), kurangi stok barang
            if ($newStatus === 'approved' && $borrowing->status === 'pending') {
                foreach ($borrowing->items as $item) {
                    $product = Product::find($item->product_id);

                    if ($product->stock < $item->quantity) {
                        return response()->json([
                            'message' => "Gagal menyetujui: Stok {$product->name} tersisa {$product->stock}."
                        ], 400);
                    }

                    // Potong stok
                    $product->decrement('stock', $item->quantity);

                    // Update status indikator ketersediaan barang
                    if ($product->stock == 0) {
                        $product->update(['status' => 'out_of_stock']);
                    } elseif ($product->stock <= 10) {
                        $product->update(['status' => 'low_stock']);
                    }
                }

                $borrowing->approved_at = Carbon::now();
            }

            if ($newStatus === 'completed') {
                $borrowing->completed_at = Carbon::now();
            }

            $borrowing->status = $newStatus;
            $borrowing->save();

            return response()->json([
                'message' => "Status peminjaman berhasil diubah menjadi {$newStatus}",
                'data'    => $borrowing->load('items.product')
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 4. User & Profil (Users)
    |--------------------------------------------------------------------------
    */

    // Ambil daftar semua user
    public function users()
    {
        return response()->json(User::with('division')->get());
    }

    // Riwayat peminjaman user tertentu
    public function history(User $user)
    {
        $history = Borrowing::with(['division', 'items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($history);
    }

    // Limit/kuota divisi dari user tersebut
    public function divisionLimits(User $user)
    {
        if (!$user->division_id) {
            return response()->json(['message' => 'User tidak terikat dengan divisi manapun.'], 404);
        }

        $limits = DivisionItemLimit::with('product')
            ->where('division_id', $user->division_id)
            ->get();

        return response()->json($limits);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Divisi & Limit Barang (Divisions)
    |--------------------------------------------------------------------------
    */

    // Daftar semua divisi beserta limit-nya
    public function divisions()
    {
        return response()->json(Division::with('limits.product')->get());
    }

    // Tambah Divisi Baru
    public function storeDivision(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|unique:divisions,name|max:150',
            'description' => 'nullable|string',
            'is_active'   => 'boolean'
        ]);

        $division = Division::create($validated);
        return response()->json(['message' => 'Divisi berhasil ditambahkan', 'data' => $division], 201);
    }

    // Update Divisi
    public function updateDivision(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:150|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean'
        ]);

        $division->update($validated);
        return response()->json(['message' => 'Divisi berhasil diperbarui', 'data' => $division]);
    }

    // Hapus Divisi
    public function destroyDivision(Division $division)
    {
        $division->delete();
        return response()->json(['message' => 'Divisi berhasil dihapus']);
    }

    // Set Kuota Pinjam Barang untuk Divisi
    public function setDivisionLimit(Request $request, Division $division)
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'quantity_limit' => 'required|integer|min:1',
            'period'         => 'required|in:daily,weekly,monthly'
        ]);

        $limit = DivisionItemLimit::updateOrCreate(
            ['division_id' => $division->id, 'product_id' => $validated['product_id']],
            ['quantity_limit' => $validated['quantity_limit'], 'period' => $validated['period']]
        );

        return response()->json(['message' => 'Limit barang divisi berhasil diperbarui', 'data' => $limit]);
    }
}