<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PerawatanController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     * Eager load kendaraan data.
     * GET /api/perawatan → 200
     */
    public function index(): JsonResponse
    {
        try {
            $perawatan = Perawatan::with('kendaraan')->orderBy('id', 'desc')->paginate(10);
            return response()->json($perawatan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/perawatan → 201 | 422
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kendaraan_id' => 'required|exists:kendaraan,id',
                'tanggal'      => 'required|date',
                'jenis'        => 'required|string|max:100',
                'biaya'        => 'required|integer|min:0',
            ]);

            $perawatan = Perawatan::create($validated);
            $perawatan->load('kendaraan');

            return response()->json([
                'message' => 'Data perawatan berhasil ditambahkan',
                'data'    => $perawatan,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/perawatan/{id} → 200 | 404
     */
    public function show($id): JsonResponse
    {
        try {
            $perawatan = Perawatan::with('kendaraan')->find($id);

            if (!$perawatan) {
                return response()->json(['message' => 'Data perawatan tidak ditemukan'], 404);
            }

            return response()->json($perawatan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/perawatan/{id} → 200 | 404 | 422
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $perawatan = Perawatan::find($id);

            if (!$perawatan) {
                return response()->json(['message' => 'Data perawatan tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'kendaraan_id' => 'required|exists:kendaraan,id',
                'tanggal'      => 'required|date',
                'jenis'        => 'required|string|max:100',
                'biaya'        => 'required|integer|min:0',
            ]);

            $perawatan->update($validated);
            $perawatan->load('kendaraan');

            return response()->json([
                'message' => 'Data perawatan berhasil diperbarui',
                'data'    => $perawatan,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/perawatan/{id} → 200 | 404
     */
    public function destroy($id): JsonResponse
    {
        try {
            $perawatan = Perawatan::find($id);

            if (!$perawatan) {
                return response()->json(['message' => 'Data perawatan tidak ditemukan'], 404);
            }

            $perawatan->delete();

            return response()->json(['message' => 'Data perawatan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}
