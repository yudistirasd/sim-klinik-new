<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsesmenKeperawatanRequest;
use App\Http\Requests\StoreResepRequest;
use App\Models\AsesmenKeperawatan;
use App\Models\AsesmenMedis;
use App\Models\CPPT;
use App\Models\DiagnosaPasien;
use App\Models\Kunjungan;
use App\Models\PelayananPasien;
use App\Models\ProsedurePasien;
use App\Models\Resep;
use App\Models\ResepDetail;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class PemeriksaanController extends Controller
{
    public function dtDiagnosa(Request $request)
    {
        $currentUser = Auth::user();


        $data = DB::table('diagnosa_pasien as dp')
            ->join('icd10', 'icd10.id', '=', 'dp.icd10_id')
            ->select([
                'dp.id',
                'dp.created_by',
                'icd10.code',
                'icd10.display_en',
                'icd10.display_id',
            ])
            ->where('dp.pasien_id', $request->pasien_id)
            ->where('dp.kunjungan_id', $request->kunjungan_id);


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($currentUser) {
                if ($currentUser->id != $row->created_by) {
                    return '';
                }

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

    public function dtProsedure(Request $request)
    {
        $currentUser = Auth::user();

        $data = DB::table('prosedure_pasien as pp')
            ->join('icd9', 'icd9.id', '=', 'pp.icd9_id')
            ->select([
                'pp.id',
                'pp.created_by',
                'icd9.code',
                'icd9.display_en',
                'icd9.display_id',
            ])
            ->where('pp.pasien_id', $request->pasien_id)
            ->where('pp.kunjungan_id', $request->kunjungan_id);


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($currentUser) {
                if ($currentUser->id != $row->created_by) {
                    return '';
                }

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

    public function dtCppt(Request $request)
    {
        $data = DB::table('cppt as a')
            ->join('users as b', 'b.id', '=', 'a.created_by')
            ->select([
                'a.*',
                'b.name as petugas'
            ])
            ->where('a.pasien_id', $request->pasien_id);

        $currentUser = Auth::user();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('keterangan', function ($row) {
                $soap = "
                 <p><b>S</b> : {$row->subjective}</p>
                 <p><b>O</b> : {$row->objective}</p>
                 <p><b>A</b> : {$row->asesmen}</p>
                 <p><b>P</b> : {$row->asesmen}</p>
                ";

                if (!empty($row->edukasi)) {
                    $soap .= "<p><b>Edukasi Pasien</b> : {$row->edukasi}</p>";
                }

                $soap .= "<p><i class='ti ti-stethoscope fs-4'></i> <b>{$row->petugas}</b></p>";

                return $soap;
            })
            ->addColumn('action', function ($row) use ($currentUser) {
                $btn = "";
                if ($currentUser->id == $row->created_by) {
                    $btn =  "<button type='button' class='btn btn-warning btn-icon' onclick='editCppt(`" . json_encode($row) . "`, cpptPasienTable.ajax.reload)'>
                                <i class='ti ti-edit'></i>
                            </button>
                            <button type='button' class='btn btn-danger btn-icon' onclick='confirmDelete(`" . route('api.pemeriksaan.destroy.cppt', $row->id) . "`, cpptPasienTable.ajax.reload)'>
                                <i class='ti ti-trash'></i>
                            </button>
                        ";
                }

                return $btn;
            })
            ->rawColumns([
                'action',
                'keterangan'
            ])
            ->make(true);
    }

    public function dtTindakan(Request $request)
    {

        $currentUser = Auth::user();

        $data = PelayananPasien::query()->with('produk')
            ->where('kunjungan_id', $request->kunjungan_id);

        $total = PelayananPasien::where('kunjungan_id', $request->kunjungan_id)
            ->sum('harga');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('harga', function ($row) {
                return formatUang($row->harga);
            })
            ->addColumn('action', function ($row) use ($currentUser) {
                if ($currentUser->role == 'perawat') {
                    return "";
                }
                return "
                                <button type='button' class='btn btn-danger btn-icon' onclick='confirmDelete(`" . route('api.pemeriksaan.destroy.tindakan', $row->id) . "`, tindakanPasienTable.ajax.reload)'>
                                    <i class='ti ti-trash'></i>
                                </button>
                            ";
            })
            ->rawColumns([
                'action',
            ])
            ->with('total', formatUang($total))
            ->make(true);
    }

    public function dtResep(Request $request)
    {
        $currentUser = Auth::user();

        $resep = Resep::with(['dokter'])->where('kunjungan_id', $request->kunjungan_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $details = DB::table('resep as rs')
            ->join('resep_detail as rd', 'rd.resep_id', '=', 'rs.id')
            ->join('produk as pr', 'pr.id', '=', 'rd.produk_id')
            ->join('aturan_pakai_obat as ap', 'ap.id', '=', 'rd.aturan_pakai_id')
            ->select([
                'rs.id as resep_id',
                'rs.status',
                'rs.nomor',
                DB::raw("pr.name || ' ' || pr.dosis || ' ' || pr.satuan as obat"),
                'pr.sediaan',
                'pr.satuan as satuan_dosis',
                'rd.id',
                'rd.signa',
                'rd.qty',
                'rd.lama_hari',
                'rd.receipt_number',
                'rd.jenis_resep',
                'rd.tipe_racikan',
                'rd.jumlah_racikan',
                'rd.kemasan_racikan',
                'rd.total_dosis_obat',
                'rd.dosis_per_racikan',
                'rd.dosis_per_satuan',
                'rd.catatan',
                'ap.name as aturan_pakai',
            ])
            ->where('rs.kunjungan_id', $request->kunjungan_id)
            ->get();

        $resep->map(function ($row) use ($details) {
            $row->tanggal = Carbon::parse($row->created_at)->translatedFormat('d F Y');
            // non racikan
            $items = $details->where('resep_id', $row->id)->where('jenis_resep', 'non_racikan');

            // racikan
            $headerRacikan = $details->where('resep_id', $row->id)->where('jenis_resep', 'racikan')->groupBy('receipt_number')->map->first();

            foreach ($headerRacikan as $header) {
                $item = (object) [
                    'jenis_resep'       => $header->jenis_resep,
                    'receipt_number'      => $header->receipt_number,
                    'tipe_racikan'        => $header->tipe_racikan,
                    'jumlah_racikan'      => $header->jumlah_racikan,
                    'kemasan_racikan'     => $header->kemasan_racikan,
                    'signa'               => $header->signa,
                    'aturan_pakai'        => $header->aturan_pakai,
                    'catatan' => $header->catatan,
                    'obat' => "Racikan " . tipeRacikan($header->tipe_racikan),
                    'komposisi' => $details->where('receipt_number', $header->receipt_number)
                        ->where('jenis_resep', 'racikan')
                ];


                $items->push($item);
            }
            $row->items = $items->sortBy('receipt_number');
            return $row;
        });

        $view = view('pemeriksaan.tabs._resep_table', compact('resep', 'currentUser'))->render();

        return $this->sendResponse(data: $view);
    }

    public function index(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien', 'ruangan', 'dokter']);
        $pasien = $kunjungan->pasien;
        $asesmenKeperawatan = AsesmenKeperawatan::where('kunjungan_id', $kunjungan->id)->with('petugas')->first();
        $asesmenMedis = AsesmenMedis::where('kunjungan_id', $kunjungan->id)->first();
        $resep = Resep::where('kunjungan_id', $kunjungan->id)
            ->where('status', 'ORDER')
            ->first();

        return view('pemeriksaan.index', compact([
            'pasien',
            'kunjungan',
            'asesmenKeperawatan',
            'asesmenMedis',
            'resep'
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
            'icd10_id' => $request->icd10_id,
            'created_by' => $request->created_by
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
            'icd9_id' => $request->icd9_id,
            'created_by' => $request->created_by
        ]);

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Prosedure Pasien']));
    }

    public function destroyProsedurePasien(ProsedurePasien $prosedure)
    {
        $prosedure->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'Prosedure Pasien']));
    }

    public function storeCppt(Request $request)
    {
        if ($request->filled('id')) {
            CPPT::find($request->id)
                ->update($request->except('id'));
        } else {
            CPPT::create($request->except('id'));
        }

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'CPPT']));
    }

    public function destroyCppt(CPPT $cppt)
    {
        $cppt->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'CPPT']));
    }

    public function storeTindakan(Request $request)
    {
        PelayananPasien::updateOrCreate([
            'pasien_id' => $request->pasien_id,
            'kunjungan_id' => $request->kunjungan_id,
            'produk_id' => $request->produk_id,
        ], [
            'harga' => $request->tarif
        ]);

        return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Tindakan']));
    }

    public function destroyTindakan(PelayananPasien $tindakan)
    {
        $tindakan->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'Tindakan']));
    }

    public function storeResep(StoreResepRequest $request)
    {
        DB::beginTransaction();

        try {

            $resep = Resep::where('kunjungan_id', $request->kunjungan_id)
                ->where('pasien_id', $request->pasien_id)
                ->where('dokter_id', $request->dokter_id)
                ->where('status', 'ORDER')
                ->first();

            if (empty($resep)) {
                $resep = Resep::create([
                    'tanggal' => $request->tanggal,
                    'pasien_id' => $request->pasien_id,
                    'kunjungan_id' => $request->kunjungan_id,
                    'dokter_id' => $request->dokter_id
                ]);
            }

            if ($request->jenis_resep == 'non_racikan') {
                ResepDetail::create([
                    'resep_id' => $resep->id,
                    'produk_id' => $request->produk_id,
                    'signa' => $request->signa,
                    'frekuensi' => $request->frekuensi,
                    'unit_dosis' => $request->unit_dosis,
                    'lama_hari' => $request->lama_hari,
                    'qty' => $request->qty,
                    'aturan_pakai_id' => $request->aturan_pakai_id,
                    'embalase' => $request->embalase,
                    'jasa_resep' => $request->jasa_resep
                ]);
            }

            if ($request->jenis_resep == 'racikan') {
                $receipt_number = ResepDetail::where('resep_id', $resep->id)
                    ->max('receipt_number') + 1;

                $komposisiRacikan = $request->komposisi_racikan;

                foreach ($komposisiRacikan as $key => $komposisi) {
                    $komposisi = (object) $komposisi;
                    $embalase = null;
                    $jasaResep = null;

                    // jasa resep & embalase untuk non racikan, disimpan di row pertama komposisi obat
                    if ($key == 0) {
                        $embalase = $request->embalase;
                        $jasaResep = $request->jasa_resep;
                    }

                    // hitung qty berdasarkan total_dosis_obat dan jumlah_racikan
                    if ($request->tipe_racikan == 'non_dtd') {
                        // dibulatkan keatas 2 desimal
                        $dosis_per_racikan = ceil($komposisi->total_dosis_obat / $request->jumlah_racikan * 100) / 100;
                        $qty = ceil($komposisi->total_dosis_obat / $komposisi->dosis_per_satuan);
                    }

                    if ($request->tipe_racikan == 'dtd') {
                        $dosis_per_racikan = $komposisi->dosis_per_racikan;
                        $qty = ceil($komposisi->dosis_per_racikan / $komposisi->dosis_per_satuan * $request->jumlah_racikan);
                    }

                    $data = [
                        'jenis_resep' => $request->jenis_resep,
                        'receipt_number' => $receipt_number,
                        'resep_id' => $resep->id,
                        'produk_id' => $komposisi->produk_id,
                        'signa' => $request->signa,
                        'frekuensi' => $request->frekuensi,
                        'unit_dosis' => $request->unit_dosis,
                        'aturan_pakai_id' => $request->aturan_pakai_id,
                        'tipe_racikan' => $request->tipe_racikan,
                        'jumlah_racikan' => $request->jumlah_racikan,
                        'kemasan_racikan' => $request->kemasan_racikan,
                        'total_dosis_obat' => $komposisi->total_dosis_obat,
                        'dosis_per_racikan' => $dosis_per_racikan,
                        'dosis_per_satuan' => $komposisi->dosis_per_satuan,
                        'qty' => $qty,
                        'embalase' => $embalase,
                        'jasa_resep' => $jasaResep,
                        'catatan' => $request->catatan
                    ];

                    ResepDetail::create($data);
                }
            }


            $resep = $resep->refresh();

            DB::commit();

            return $this->sendResponse(message: __('http-response.success.store', ['Attribute' => 'Obat']), data: $resep);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError(message: __('http-response.error.store', ['Attribute' => 'Obat']), errors: $th->getMessage(), traces: $th->getTrace());
        }
    }

    public function destroyResepDetail(Resep $resep, $receiptNumber)
    {

        ResepDetail::where('resep_id', $resep->id)
            ->where('receipt_number', $receiptNumber)
            ->delete();

        return $this->sendResponse(message: __('http-response.success.delete', ['Attribute' => 'Obat']));
    }
}
