<?php

namespace App\Http\Controllers;

use App\Models\Supir;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SupirController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     * GET /api/supir → 200
     */
    public function index(): JsonResponse
    {
        try {
            $supir = Supir::orderBy('id', 'desc')->paginate(10);
            return response()->json($supir, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Return all supir without pagination (for dropdown).
     * GET /api/supir-list → 200
     */
    public function list(): JsonResponse
    {
        try {
            $supir = Supir::where('status', 'Aktif')->orderBy('nama', 'asc')->get();
            return response()->json($supir, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/supir → 201 | 422
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nama'   => 'required|string|max:100',
                'sim'    => 'required|string|max:20',
                'no_hp'  => 'required|string|max:15',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            ]);

            $supir = Supir::create($validated);

            return response()->json([
                'message' => 'Data supir berhasil ditambahkan',
                'data'    => $supir,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/supir/{id} → 200 | 404
     */
    public function show($id): JsonResponse
    {
        try {
            $supir = Supir::find($id);

            if (!$supir) {
                return response()->json(['message' => 'Data supir tidak ditemukan'], 404);
            }

            return response()->json($supir, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/supir/{id} → 200 | 404 | 422
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $supir = Supir::find($id);

            if (!$supir) {
                return response()->json(['message' => 'Data supir tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'nama'   => 'required|string|max:100',
                'sim'    => 'required|string|max:20',
                'no_hp'  => 'required|string|max:15',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            ]);

            $supir->update($validated);

            return response()->json([
                'message' => 'Data supir berhasil diperbarui',
                'data'    => $supir,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/supir/{id} → 200 | 404
     */
    public function destroy($id): JsonResponse
    {
        try {
            $supir = Supir::find($id);

            if (!$supir) {
                return response()->json(['message' => 'Data supir tidak ditemukan'], 404);
            }

            $supir->delete();

            return response()->json(['message' => 'Data supir berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}
