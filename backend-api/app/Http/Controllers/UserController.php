<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Ambil seluruh data pengguna beserta divisinya
     */
    public function index()
    {
        $users = User::with('division')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($users);
    }

    /**
     * Tambah user baru (misal role Sopir, OB, Pegawai, Admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'              => 'required|string|unique:users,nip|max:50',
            'name'             => 'required|string|unique:users,name|max:150',
            'password'         => 'required|string|min:6',
            'role'             => 'required|string|in:admin,user,sopir,ob',
            'division_id'      => 'nullable|exists:divisions,id',
            'status'           => 'nullable|string|in:active,inactive',
            'telegram_chat_id' => 'nullable|string|unique:users,telegram_chat_id',
        ], [
            'nip.unique'              => 'NIP / Username sudah terdaftar dalam sistem, silakan gunakan NIP lain.',
            'name.unique'             => 'Nama pengguna sudah terdaftar dalam sistem, mohon gunakan nama yang berbeda.',
            'telegram_chat_id.unique' => 'ID Telegram sudah terdaftar dan digunakan oleh pengguna lain.',
        ]);

        $user = User::create([
            'nip'              => $validated['nip'],
            'name'             => $validated['name'],
            'password'         => Hash::make($validated['password']),
            'visible_password' => $validated['password'],
            'role'             => $validated['role'],
            'division_id'      => $validated['division_id'] ?? null,
            'status'           => $validated['status'] ?? 'active',
            'telegram_chat_id' => $validated['telegram_chat_id'] ?? null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Pengguna {$user->name} berhasil ditambahkan dengan role {$user->role}.",
            'data'    => $user->load('division')
        ], 201);
    }

    /**
     * Update detail pengguna (Role, Divisi, Status, Nama)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nip'              => 'sometimes|string|max:50|unique:users,nip,' . $user->id,
            'name'             => 'sometimes|string|max:150|unique:users,name,' . $user->id,
            'role'             => 'sometimes|string|in:admin,user,sopir,ob',
            'division_id'      => 'nullable|exists:divisions,id',
            'status'           => 'sometimes|string|in:active,inactive',
            'avatar'           => 'nullable|string',
            'telegram_chat_id' => 'nullable|string|unique:users,telegram_chat_id,' . $user->id,
        ], [
            'nip.unique'              => 'NIP / Username sudah digunakan oleh pengguna lain.',
            'name.unique'             => 'Nama pengguna sudah digunakan oleh pengguna lain.',
            'telegram_chat_id.unique' => 'ID Telegram sudah digunakan oleh pengguna lain.',
        ]);

        // Lindungi admin utama
        if ($user->nip === '12345' && isset($validated['role']) && $validated['role'] !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Role Administrator utama (NIP: 12345) tidak dapat diubah.'
            ], 403);
        }

        $user->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => "Data pengguna {$user->name} berhasil diperbarui.",
            'data'    => $user->load('division')
        ]);
    }

    /**
     * Update password pengguna (Nama dan NIP/Username paten/tidak dapat diubah)
     */
    public function updatePassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($id);

        // Update password yang sudah di-hash dan simpan visible_password untuk kebutuhan audit admin
        $user->password = Hash::make($validated['password']);
        $user->visible_password = $validated['password'];
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => "Password untuk pengguna {$user->name} (NIP: {$user->nip}) berhasil diperbarui.",
            'data'    => $user->load('division')
        ]);
    }

    /**
     * Hapus akun pengguna (Admin utama dilindungi)
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Lindungi admin utama dari penghapusan
        if ($user->nip === '12345' || $user->role === 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Administrator utama (NIP: 12345) dilindungi sistem dan tidak dapat dihapus.'
            ], 403);
        }

        // Hapus data pengguna
        $userName = $user->name;
        $userNip = $user->nip;

        // Bersihkan data relasi jika ada
        if (method_exists($user, 'borrowings')) {
            $user->borrowings()->delete();
        }

        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => "Pengguna {$userName} (NIP: {$userNip}) berhasil dihapus dari sistem."
        ]);
    }
}