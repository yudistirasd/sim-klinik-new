<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\DB;

class ProdukStokController extends Controller
{
    public function dt()
    {
        $data = DB::table('produk_stok as ps')
            ->join('produk as pr', 'pr.id', '=', 'ps.produk_id')
            ->select([
                DB::raw("pr.name || ' ' || pr.dosis || ' ' || pr.satuan || ' ' || pr.sediaan as name"),
                'expired_date',
                'barcode',
                'harga_beli',
                'harga_jual_resep',
                'harga_jual_bebas',
                'harga_jual_apotek',
                DB::raw('sum(ps.ready) as ready')
            ])
            ->groupBy([
                'pr.name',
                'pr.dosis',
                'pr.satuan',
                'pr.sediaan',
                'harga_beli',
                'harga_jual_resep',
                'harga_jual_bebas',
                'harga_jual_apotek',
                'expired_date',
                'barcode',
            ]);

        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('pr.name', 'ilike', $keyword . '%');
            })
            ->editColumn('harga_beli', fn($row) => formatUang($row->harga_beli, true))
            ->editColumn('harga_jual_resep', fn($row) => formatUang($row->harga_jual_resep, true))
            ->editColumn('harga_jual_bebas', fn($row) => formatUang($row->harga_jual_bebas, true))
            ->editColumn('harga_jual_apotek', fn($row) => formatUang($row->harga_jual_apotek, true))
            ->editColumn('expired_date', fn($row) => empty($row->expired_date) ? '-' : $row->expired_date)
            ->addIndexColumn()
            ->make(true);
    }
    public function index()
    {
        return view('farmasi.stok.index');
    }
}
