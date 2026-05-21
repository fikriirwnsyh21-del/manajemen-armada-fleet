<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     * Eager load kendaraan and supir.
     * GET /api/rute → 200
     */
    public function index(): JsonResponse
    {
        try {
            $rute = Rute::with(['kendaraan', 'supir'])->orderBy('id', 'desc')->paginate(10);
            return response()->json($rute, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/rute → 201 | 422
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'asal'          => 'required|string|max:100',
                'tujuan'        => 'required|string|max:100',
                'jarak'         => 'required|numeric|min:0',
                'kendaraan_id'  => 'required|exists:kendaraan,id',
                'supir_id'      => 'required|exists:supir,id',
            ]);

            $rute = Rute::create($validated);
            $rute->load(['kendaraan', 'supir']);

            return response()->json([
                'message' => 'Data rute berhasil ditambahkan',
                'data'    => $rute,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/rute/{id} → 200 | 404
     */
    public function show($id): JsonResponse
    {
        try {
            $rute = Rute::with(['kendaraan', 'supir'])->find($id);

            if (!$rute) {
                return response()->json(['message' => 'Data rute tidak ditemukan'], 404);
            }

            return response()->json($rute, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/rute/{id} → 200 | 404 | 422
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $rute = Rute::find($id);

            if (!$rute) {
                return response()->json(['message' => 'Data rute tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'asal'          => 'required|string|max:100',
                'tujuan'        => 'required|string|max:100',
                'jarak'         => 'required|numeric|min:0',
                'kendaraan_id'  => 'required|exists:kendaraan,id',
                'supir_id'      => 'required|exists:supir,id',
            ]);

            $rute->update($validated);
            $rute->load(['kendaraan', 'supir']);

            return response()->json([
                'message' => 'Data rute berhasil diperbarui',
                'data'    => $rute,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/rute/{id} → 200 | 404
     */
    public function destroy($id): JsonResponse
    {
        try {
            $rute = Rute::find($id);

            if (!$rute) {
                return response()->json(['message' => 'Data rute tidak ditemukan'], 404);
            }

            $rute->delete();

            return response()->json(['message' => 'Data rute berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}
