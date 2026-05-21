<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraan = Kendaraan::all();
        return response()->json($kendaraan, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nopol'  => 'required|unique:kendaraan',
            'merk'   => 'required',
            'tipe'   => 'required',
            'tahun'  => 'required|digits:4',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Perbaikan',
        ]);

        $kendaraan = Kendaraan::create($request->all());
        return response()->json($kendaraan, 201);
    }

    public function show($id)
    {
        $kendaraan = Kendaraan::find($id);
        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }
        return response()->json($kendaraan, 200);
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::find($id);
        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        $request->validate([
            'nopol'  => 'required|unique:kendaraan,nopol,'.$id,
            'merk'   => 'required',
            'tipe'   => 'required',
            'tahun'  => 'required|digits:4',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Perbaikan',
        ]);

        $kendaraan->update($request->all());
        return response()->json($kendaraan, 200);
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::find($id);
        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        $kendaraan->delete();
        return response()->json(['message' => 'Kendaraan berhasil dihapus'], 200);
    }
}
