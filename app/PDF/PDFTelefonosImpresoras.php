<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 15/05/2017
 * Time: 10:34 AM
 */

namespace App\PDF;

use App\Models\Proyecto;
use App\Facades\Context;
use Ghidev\Fpdf\Rotation;

class PDFTelefonosImpresoras extends Rotation
{

    /**
     * @var Array
     */
    protected $data;

    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = '';

    /**
     * PDFViajesNetos constructor.
     * @param string $orientation
     * @param array|string $unit
     * @param string $size
     * @param array $data
     */
    public function __construct($orientation = 'P', $unit = 'cm', $size = 'A4', Array $data)
    {
        parent::__construct($orientation, $unit, $size);

        $this->SetAutoPageBreak(true, 1.5);
        $this->data = $data;
        $this->WidthTotal = $this->GetPageWidth() - 2;
        $this->txtTitleTam = 18;
        $this->txtSubtitleTam = 13;
        $this->txtSeccionTam = 9;
        $this->txtContenidoTam = 7;
        $this->txtFooterTam = 6;
    }



}