<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Controllers\Controller;
use App\Libraries\EasyTable\EasyTable;
use App\Libraries\EasyTable\exFPDFPenjualanFaktur;
use App\Models\Kunjungan;
use App\Models\PelayananPasien;
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

        $table = new easyTable($this->pdf, '{10, 160, 25}', 'width:100%;border:1;');

        $table->easyCell('No', 'align:C;valign:M;font-style:B');
        $table->easyCell('Uraian', 'align:C;valign:M;font-style:B');
        $table->easyCell('Harga', 'align:C;valign:M;font-style:B');
        $table->printRow(true);

        $tindakan = PelayananPasien::query()->with('produk')
            ->where('kunjungan_id', $kunjungan->id)
            ->get();

        $total = 0;
        foreach ($tindakan as $key => $item) {
            $table->easyCell($key + 1, 'align:C;valign:M;');
            $table->easyCell($item->produk->name, 'align:L;valign:M');
            $table->easyCell(formatUang($item->harga), 'align:R;valign:M');
            $table->printRow();
            $total += $item->harga;
        }

        $table->easyCell('Total', 'align:R;valign:M;colspan:2;font-style:B');
        $table->easyCell(formatUang($total), 'align:R;valign:M;font-style:B');
        $table->printRow();
        $table->endTable();

        $this->pdf->Output('I', $fileName);
    }
}
