<?php

namespace App\Http\Controllers;

use App\Models\BahanBakar;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BahanBakarController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     * Eager load kendaraan data.
     * GET /api/bahan-bakar → 200
     */
    public function index(): JsonResponse
    {
        try {
            $bahanBakar = BahanBakar::with('kendaraan')->orderBy('id', 'desc')->paginate(10);
            return response()->json($bahanBakar, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/bahan-bakar → 201 | 422
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kendaraan_id' => 'required|exists:kendaraan,id',
                'tanggal'      => 'required|date',
                'liter'        => 'required|numeric|min:0',
                'biaya'        => 'required|integer|min:0',
            ]);

            $bahanBakar = BahanBakar::create($validated);
            $bahanBakar->load('kendaraan');

            return response()->json([
                'message' => 'Data bahan bakar berhasil ditambahkan',
                'data'    => $bahanBakar,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/bahan-bakar/{id} → 200 | 404
     */
    public function show($id): JsonResponse
    {
        try {
            $bahanBakar = BahanBakar::with('kendaraan')->find($id);

            if (!$bahanBakar) {
                return response()->json(['message' => 'Data bahan bakar tidak ditemukan'], 404);
            }

            return response()->json($bahanBakar, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/bahan-bakar/{id} → 200 | 404 | 422
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $bahanBakar = BahanBakar::find($id);

            if (!$bahanBakar) {
                return response()->json(['message' => 'Data bahan bakar tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'kendaraan_id' => 'required|exists:kendaraan,id',
                'tanggal'      => 'required|date',
                'liter'        => 'required|numeric|min:0',
                'biaya'        => 'required|integer|min:0',
            ]);

            $bahanBakar->update($validated);
            $bahanBakar->load('kendaraan');

            return response()->json([
                'message' => 'Data bahan bakar berhasil diperbarui',
                'data'    => $bahanBakar,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/bahan-bakar/{id} → 200 | 404
     */
    public function destroy($id): JsonResponse
    {
        try {
            $bahanBakar = BahanBakar::find($id);

            if (!$bahanBakar) {
                return response()->json(['message' => 'Data bahan bakar tidak ditemukan'], 404);
            }

            $bahanBakar->delete();

            return response()->json(['message' => 'Data bahan bakar berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}
