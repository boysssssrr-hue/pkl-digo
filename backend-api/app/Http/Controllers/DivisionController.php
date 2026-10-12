<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\DivisionItemLimit;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    /**
     * Ambil daftar seluruh divisi beserta relasi kuota barang (ATK) dan anggota
     */
    public function index()
    {
        $divisions = Division::with(['users', 'limits.product'])
            ->withCount('users')
            ->orderBy('id', 'asc')
            ->get();

        $products = Product::where('status', '!=', 'archived')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status'    => 'success',
            'divisions' => $divisions,
            'products'  => $products
        ]);
    }

    /**
     * Tambah divisi baru beserta kuota pengambilan ATK per 6 bulan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|unique:divisions,name|max:150',
            'member_count' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
            'limits'       => 'nullable|array'
        ], [
            'name.unique'  => 'Nama divisi/bidang sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $division = Division::create([
                'name'         => $validated['name'],
                'member_count' => $validated['member_count'],
                'description'  => $validated['description'] ?? null,
                'is_active'    => $request->boolean('is_active', true)
            ]);

            // Simpan kuota batas pengambilan ATK per 6 bulan untuk setiap barang
            if ($request->has('limits') && is_array($request->limits)) {
                foreach ($request->limits as $lim) {
                    if (!empty($lim['product_id']) && isset($lim['quantity_limit'])) {
                        $qty = max(0, (int) $lim['quantity_limit']);
                        if ($qty > 0) {
                            DivisionItemLimit::updateOrCreate(
                                [
                                    'division_id' => $division->id,
                                    'product_id'  => $lim['product_id']
                                ],
                                [
                                    'quantity_limit' => $qty,
                                    'period'         => '6_bulan'
                                ]
                            );
                        }
                    }
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => "Divisi {$division->name} dan alokasi kuota ATK 6 bulan berhasil ditambahkan.",
                'data'    => $division->load(['users', 'limits.product'])
            ], 201);
        });
    }

    /**
     * Tampilkan detail divisi tertentu
     */
    public function show($id)
    {
        $division = Division::with(['users', 'limits.product'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $division
        ]);
    }

    /**
     * Update data divisi dan alokasi kuota ATK per 6 bulan
     */
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'sometimes|string|unique:divisions,name,' . $division->id . '|max:150',
            'member_count' => 'sometimes|integer|min:1',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
            'limits'       => 'nullable|array'
        ], [
            'name.unique'  => 'Nama divisi/bidang sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
        ]);

        return DB::transaction(function () use ($request, $division, $validated) {
            $division->update([
                'name'         => $validated['name'] ?? $division->name,
                'member_count' => $validated['member_count'] ?? $division->member_count,
                'description'  => array_key_exists('description', $validated) ? $validated['description'] : $division->description,
                'is_active'    => $request->has('is_active') ? $request->boolean('is_active') : $division->is_active
            ]);

            // Sinkronisasi kuota batas pengambilan ATK per 6 bulan
            if ($request->has('limits') && is_array($request->limits)) {
                foreach ($request->limits as $lim) {
                    if (!empty($lim['product_id'])) {
                        $qty = max(0, (int) ($lim['quantity_limit'] ?? 0));
                        if ($qty > 0) {
                            DivisionItemLimit::updateOrCreate(
                                [
                                    'division_id' => $division->id,
                                    'product_id'  => $lim['product_id']
                                ],
                                [
                                    'quantity_limit' => $qty,
                                    'period'         => '6_bulan'
                                ]
                            );
                        } else {
                            // Jika kuota diset 0, hapus limit barang tersebut
                            DivisionItemLimit::where('division_id', $division->id)
                                ->where('product_id', $lim['product_id'])
                                ->delete();
                        }
                    }
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => "Data divisi {$division->name} dan kuota ATK berhasil diperbarui.",
                'data'    => $division->fresh()->load(['users', 'limits.product'])
            ]);
        });
    }

    /**
     * Hapus divisi dan bersihkan relasi
     */
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $divisionName = $division->name;

        return DB::transaction(function () use ($division, $divisionName) {
            // Lepaskan keterikatan user ke divisi ini
            User::where('division_id', $division->id)->update(['division_id' => null]);

            // Hapus limit kuota barang divisi
            $division->limits()->delete();

            // Hapus divisi
            $division->delete();

            return response()->json([
                'status'  => 'success',
                'message' => "Divisi {$divisionName} berhasil dihapus dari sistem."
            ]);
        });
    }

    /**
     * Mengatur kuota limit satuan barang untuk divisi per periode 6 bulan
     */
    public function setLimit(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'quantity_limit' => 'required|integer|min:1',
            'period'         => 'nullable|string'
        ]);

        $limit = DivisionItemLimit::updateOrCreate(
            [
                'division_id' => $division->id,
                'product_id'  => $validated['product_id']
            ],
            [
                'quantity_limit' => $validated['quantity_limit'],
                'period'         => $validated['period'] ?? '6_bulan'
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Limit kuota barang berhasil diperbarui.',
            'data'    => $limit->load('product')
        ]);
    }
}