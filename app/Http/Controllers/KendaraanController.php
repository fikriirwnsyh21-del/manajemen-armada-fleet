<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     * GET /api/kendaraan → 200
     */
    public function index(): JsonResponse
    {
        try {
            $kendaraan = Kendaraan::orderBy('id', 'desc')->paginate(10);
            return response()->json($kendaraan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Return all kendaraan without pagination (for dropdown).
     * GET /api/kendaraan-list → 200
     */
    public function list(): JsonResponse
    {
        try {
            $kendaraan = Kendaraan::where('status', 'Aktif')->orderBy('nopol', 'asc')->get();
            return response()->json($kendaraan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/kendaraan → 201 | 422
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nopol'  => 'required|string|max:20|unique:kendaraan,nopol',
                'merk'   => 'required|string|max:50',
                'tipe'   => 'required|string|max:50',
                'tahun'  => 'required|digits:4|integer|min:1900|max:2099',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'])],
            ]);

            $kendaraan = Kendaraan::create($validated);

            return response()->json([
                'message' => 'Data kendaraan berhasil ditambahkan',
                'data'    => $kendaraan,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/kendaraan/{id} → 200 | 404
     */
    public function show($id): JsonResponse
    {
        try {
            $kendaraan = Kendaraan::find($id);

            if (!$kendaraan) {
                return response()->json(['message' => 'Data kendaraan tidak ditemukan'], 404);
            }

            return response()->json($kendaraan, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/kendaraan/{id} → 200 | 404 | 422
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $kendaraan = Kendaraan::find($id);

            if (!$kendaraan) {
                return response()->json(['message' => 'Data kendaraan tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'nopol'  => ['required', 'string', 'max:20', Rule::unique('kendaraan')->ignore($kendaraan->id)],
                'merk'   => 'required|string|max:50',
                'tipe'   => 'required|string|max:50',
                'tahun'  => 'required|digits:4|integer|min:1900|max:2099',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'])],
            ]);

            $kendaraan->update($validated);

            return response()->json([
                'message' => 'Data kendaraan berhasil diperbarui',
                'data'    => $kendaraan,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/kendaraan/{id} → 200 | 404
     */
    public function destroy($id): JsonResponse
    {
        try {
            $kendaraan = Kendaraan::find($id);

            if (!$kendaraan) {
                return response()->json(['message' => 'Data kendaraan tidak ditemukan'], 404);
            }

            $kendaraan->delete();

            return response()->json(['message' => 'Data kendaraan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}
