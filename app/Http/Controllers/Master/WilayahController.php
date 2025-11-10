<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function provinsi(Request $request)
    {
        $provinsi = DB::table('provinsi')
            ->when($request->filled('keyword'), fn($q) => $q->where('name', 'ilike', "%{$request->keyword}%"))
            ->limit(10)
            ->get(['id', 'name as text']);

        return response()->json($provinsi);
    }

    public function kabupaten(Request $request)
    {
        $kabupaten = DB::table('kabupaten')
            ->where('provinsi_id', $request->provinsi_id)
            ->when($request->filled('keyword'), fn($q) => $q->where('name', 'ilike', "%{$request->keyword}%"))
            ->limit(10)
            ->get(['id', 'name as text']);

        return response()->json($kabupaten);
    }

    public function kecamatan(Request $request)
    {
        $kecamatan = DB::table('kecamatan')
            ->where('kabupaten_id', $request->kabupaten_id)
            ->when($request->filled('keyword'), fn($q) => $q->where('name', 'ilike', "%{$request->keyword}%"))
            ->limit(10)
            ->get(['id', 'name as text']);

        return response()->json($kecamatan);
    }

    public function kelurahan(Request $request)
    {
        $kelurahan = DB::table('kelurahan')
            ->where('kecamatan_id', $request->kecamatan_id)
            ->when($request->filled('keyword'), fn($q) => $q->where('name', 'ilike', "%{$request->keyword}%"))
            ->limit(10)
            ->get(['id', 'name as text']);

        return response()->json($kelurahan);
    }
}
