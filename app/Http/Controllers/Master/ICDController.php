<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ICD10;
use App\Models\ICD9;
use DataTables;
use Illuminate\Http\Request;

class ICDController extends Controller
{
    public function icd10dt()
    {
        $data = ICD10::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return "
                                <button class='btn btn-primary btn-icon' id='diagnosa-{$row->id}' onclick='selectIcd10(" . json_encode($row) . ")'>
                                    <i class='ti ti-check'></i>
                                </button>
                            ";
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function icd9dt()
    {
        $data = ICD9::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return "
                                <button class='btn btn-primary btn-icon' id='prosedure-{$row->id}' onclick='selectIcd9(" . json_encode($row) . ")'>
                                    <i class='ti ti-check'></i>
                                </button>
                            ";
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }
}
