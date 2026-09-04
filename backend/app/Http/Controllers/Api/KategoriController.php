<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Get all categories belonging to the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $categories = Kategori::where(
                'id_user',
                $request->user()->id
            )->get();

            return response()->json([
                'status' => true,
                'message' => 'Categories retrieved successfully.',
                'data' => $categories,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new category.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_kategori' => 'required|string|max:100',
                'jenis' => 'required|in:pemasukan,pengeluaran',
                'ikon' => 'nullable|string|max:100',
            ]);

            $category = Kategori::create([
                'id_user' => $request->user()->id,
                'nama_kategori' => $validated['nama_kategori'],
                'jenis' => $validated['jenis'],
                'ikon' => $validated['ikon'] ?? null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully.',
                'data' => $category,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get one category.
     */
    public function show(Request $request, $id)
    {
        try {
            $category = Kategori::where('id_kategori', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category retrieved successfully.',
                'data' => $category,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a category.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Kategori::where('id_kategori', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            $validated = $request->validate([
                'nama_kategori' => 'required|string|max:100',
                'jenis' => 'required|in:pemasukan,pengeluaran',
                'ikon' => 'nullable|string|max:100',
            ]);

            $category->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully.',
                'data' => $category,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a category.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $category = Kategori::where('id_kategori', $id)
                ->where('id_user', $request->user()->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.',
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}