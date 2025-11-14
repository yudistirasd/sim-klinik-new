<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Controllers\Controller;
use App\Libraries\EasyTable\exFPDFPenjualanFaktur;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class TagihanPasienController extends Controller
{

    private $pdf;

    public function __construct()
    {
        $this->pdf = new exFPDFPenjualanFaktur();
    }

    public function index(Kunjungan $kunjungan)
    {
        $fileName = "BUKTI BAYAR  " . $kunjungan->noregistrasi;

        $this->pdf->setTitle($fileName);
        $this->pdf->setKunjungan($kunjungan, $fileName);
        $this->pdf->AddPage('L', array(148, 210));
        $this->pdf->setMargins(1, 1, 1);

        $this->pdf->Output('I', $fileName);
    }
}
