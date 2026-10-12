<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    // Daftar semua pengajuan (Admin melihat semua, User melihat miliknya sendiri)
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Borrowing::with(['user', 'division', 'items.product']);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return response()->json($query->latest()->get());
    }

    // Buat Pengajuan Peminjaman Baru
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->division_id) {
            return response()->json(['message' => 'Anda harus terdaftar dalam divisi untuk mengajukan peminjaman.'], 400);
        }

        $request->validate([
            'request_note' => 'nullable|string',
            'items'        => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $user) {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock < $item['quantity']) {
                    return response()->json(['message' => "Stok produk {$product->name} tidak mencukupi."], 400);
                }
            }

            $borrowing = Borrowing::create([
                'user_id'      => $user->id,
                'division_id'  => $user->division_id,
                'status'       => 'completed',
                'request_note' => $request->request_note,
                'requested_at' => now(),
                'approved_at'  => now(),
                'completed_at' => now(),
            ]);

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

            return response()->json([
                'message' => 'Pengambilan barang ATK berhasil dicatat dan langsung selesai.',
                'data'    => $borrowing->load('items.product')
            ], 201);
        });
    }

    // Detail Pengajuan
    public function show(Borrowing $borrowing)
    {
        return response()->json($borrowing->load(['user', 'division', 'items.product']));
    }

    // Update Status Peminjaman (Approved, Rejected, Completed) oleh Admin
    public function updateStatus(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed'
        ]);

        return DB::transaction(function () use ($request, $borrowing) {
            $status = $request->status;

            if ($status === 'approved' && $borrowing->status === 'pending') {
                // Potong Stok Produk saat disetujui
                foreach ($borrowing->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product->stock < $item->quantity) {
                        return response()->json(['message' => "Stok produk {$product->name} tidak mencukupi."], 400);
                    }
                    $product->decrement('stock', $item->quantity);
                    
                    // Update status stok jika minim
                    if ($product->stock == 0) {
                        $product->update(['status' => 'out_of_stock']);
                    } elseif ($product->stock <= 10) {
                        $product->update(['status' => 'low_stock']);
                    }
                }
                $borrowing->approved_at = now();
            }

            if ($status === 'completed') {
                $borrowing->completed_at = now();
            }

            $borrowing->status = $status;
            $borrowing->save();

            return response()->json(['message' => "Status peminjaman diubah menjadi {$status}", 'data' => $borrowing]);
        });
    }
}