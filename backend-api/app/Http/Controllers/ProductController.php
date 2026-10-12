<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('id', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode'     => 'required|string|unique:products,barcode',
            'name'        => 'required|string|unique:products,name|max:150',
            'category'    => 'required|string|max:100',
            'unit'        => 'required|string|max:30',
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:available,low_stock,out_of_stock',
            'image'       => 'nullable|string'
        ], [
            'name.unique'    => 'Nama barang ATK sudah terdaftar dalam sistem. Mohon gunakan nama yang berbeda.',
            'barcode.unique' => 'Kode barcode ATK sudah terdaftar dalam sistem. Gunakan kode barcode yang unik.',
        ]);

        $product = Product::create($validated);
        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $product], 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode'     => 'sometimes|string|unique:products,barcode,' . $product->id,
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

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}