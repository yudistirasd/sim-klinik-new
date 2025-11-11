<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsesmenKeperawatanRequest;
use App\Models\AsesmenKeperawatan;
use App\Models\AsesmenMedis;
use App\Models\DiagnosaPasien;
use App\Models\Kunjungan;
use App\Models\ProsedurePasien;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function dtDiagnosa()
    {
        $data = DB::table('diagnosa_pasien as dp')
            ->join('icd10', 'icd10.id', '=', 'dp.icd10_id')
            ->select([
                'dp.id',
                'icd10.code',
                'icd10.display_en',
                'icd10.display_id',
            ]);


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return "
                                <button type='button' class='btn btn-danger btn-icon' onclick='confirmDelete(`" . route('api.pemeriksaan.destroy.diagnosa-pasien', $row->id) . "`, diagnosaPasienTable.ajax.reload)'>
                                    <i class='ti ti-trash'></i>
                                </button>
                            ";
            })
            ->rawColumns([
                'action',
                'noregistrasi'
            ])
            ->make(true);
    }

    public function dtProsedure()
    {
        $data = DB::table('prosedure_pasien as pp')
            ->join('icd9', 'icd9.id', '=', 'pp.icd9_id')
            ->select([
                'pp.id',
                'icd9.code',
                'icd9.display_en',
                'icd9.display_id',
            ]);


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return "
                                <button type='button' class='btn btn-danger btn-icon' onclick='confirmDelete(`" . route('api.pemeriksaan.destroy.prosedure-pasien', $row->id) . "`, prosedurePasienTable.ajax.reload)'>
                                    <i class='ti ti-trash'></i>
                                </button>
                            ";
            })
            ->rawColumns([
                'action',
                'noregistrasi'
            ])
            ->make(true);
    }

    public function index(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien', 'ruangan', 'dokter']);
        $pasien = $kunjungan->pasien;
        $asesmenKeperawatan = AsesmenKeperawatan::where('kunjungan_id', $kunjungan->id)->with('petugas')->first();
        $asesmenMedis = AsesmenMedis::where('kunjungan_id', $kunjungan->id)->first();

        return view('pemeriksaan.index', compact([
            'pasien',
            'kunjungan',
            'asesmenKeperawatan',
            'asesmenMedis'
        ]));
    }

    public function storeAsesmenKeperawatan(AsesmenKeperawatanRequest $request)
    {
        AsesmenKeperawatan::updateOrCreate([
            'pasien_id' => $request->pasien_id,
            'kunjungan_id' => $request->kunjungan_id
        ], $request->except(['pasien_id', 'kunjungan_id']));

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Asesmen Keperawatan']));
    }

    public function storeAsesmenMedis(Request $request)
    {
        AsesmenMedis::updateOrCreate([
            'pasien_id' => $request->pasien_id,
            'kunjungan_id' => $request->kunjungan_id
        ], $request->except([
            'pasien_id',
            'kunjungan_id',
            'berat',
            'tinggi',
            'nadi',
            'suhu',
            'respirasi',
            'tekanan_darah',
        ]));

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Asesmen Medis']));
    }

    public function storeDiagnosaPasien(Request $request)
    {
        DiagnosaPasien::updateOrCreate([
            'pasien_id' => $request->pasien_id,
            'kunjungan_id' => $request->kunjungan_id,
            'icd10_id' => $request->icd10_id
        ]);

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Diagnosa Pasien']));
    }

    public function destroyDiagnosaPasien(DiagnosaPasien $diagnosa)
    {
        $diagnosa->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'Diagnosa Pasien']));
    }

    public function storeProsedurePasien(Request $request)
    {
        ProsedurePasien::updateOrCreate([
            'pasien_id' => $request->pasien_id,
            'kunjungan_id' => $request->kunjungan_id,
            'icd9_id' => $request->icd9_id
        ]);

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Prosedure Pasien']));
    }

    public function destroyProsedurePasien(ProsedurePasien $prosedure)
    {
        $prosedure->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'Prosedure Pasien']));
    }
}
