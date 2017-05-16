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

class PDFConfiguracionDiaria extends Rotation
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


    /**
     *
     */
    function Header()
    {
        $this->title();
        $this->logo();
        $y_inicial = $this->getY();
        $this->details();
        $y_final = $this->getY();
        $this->setY($y_inicial);
        $alto = abs($y_final - $y_inicial);
        $this->cuadroRedondeado($y_inicial,$alto);
        $this->setY($y_inicial);
        $this->details($y_inicial);
        $this->ln();


        if ($this->encola == 'items'){
            $this->encabezadoTabla();
        }
    }


    private function title()
    {
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('CONFIGURACIÓN DE TELÉFONOS-IMPRESORAS'), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.45 * $this->WidthTotal, 0.7, utf8_decode('Detalles de la configuración.'), 0, 1, 'L');


    }

    private function logo()
    {
        //$this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);
        if (Proyecto::find(Context::getId())->tiene_logo == 2) {
            $dataURI = "data:image/png;base64," . Proyecto::find(Context::getId())->logo;
            $dataPieces = explode(',', $dataURI);
            $encodedImg = $dataPieces[1];
            $decodedImg = base64_decode($encodedImg);


            //  Check if image was properly decoded
            if ($decodedImg !== false) {

                //  Save image to a temporary location
                if (file_put_contents(public_path('img/logo_temp.png'), $decodedImg) !== false) {

                    //  Open new PDF document and print image
                    //$this->image($dataURI, $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);
                    $this->image(public_path('img/logo_temp.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);
                    //dd("image");
                    //  Delete image from server
                    unlink(public_path('img/logo_temp.png'));
                }
            }
        } else {
            $this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);
        }
    }
    public function encabezadoTabla(){

        $this->SetWidths(array(0));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('1,1,1'));
        $this->SetRounds(array('0'));
        $this->SetRadius(array(0));
        $this->SetHeights(array(0));
        $this->Row(Array(''));
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->SetTextColors(array('255,255,255'));
        $this->CellFitScale($this->WidthTotal, 1, utf8_decode('TELÉFONOS CONFIGURADOS'), 0, 1, 'L');

        $this->SetWidths(array(0));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('1,1,1'));
        $this->SetRounds(array('0'));
        $this->SetRadius(array(0));
        $this->SetHeights(array(0));
        $this->Row(Array(''));
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->SetTextColors(array('255,255,255'));
        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF', 'DF'));
        $this->SetWidths(array(

            0.58 * $this->WidthTotal,
            0.42 * $this->WidthTotal
        ));
        $this->SetRounds(array('1', '2'));
        $this->SetRadius(array(0.2, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0'));
        $this->SetHeights(array(0.5));
        $this->SetAligns(array('C', 'C'));
        $this->Row(array(
            utf8_decode("TELEFÓNO"),
            utf8_decode("IMPRESORA")
        ));

        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF', 'DF', 'DF'));
        $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF', 'DF', 'DF'));
        $this->SetWidths(array(
            0.02 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal
        ));
        $this->SetRounds(array('', '', '', '', '', '', '', ''));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.5));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array(
            "#",
            "NOMBRE",
            "USUARIO INTRANET",
            "ORIGEN / TIRO",
            utf8_decode("UBICACIÓN"),
            "PERFIL",
            "TURNO"));
        $this->SetRounds(array('', '', '', '', '', '', '', '', ''));
        $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0));
        $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.35));
        $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'C'));

        $this->SetWidths(array(
            0.02 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal,
            0.14 * $this->WidthTotal
        ));

    }
    private function details()
    {

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.30 * $this->WidthTotal, 0.45, utf8_decode('FECHA DE CONSULTA:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.55 * $this->WidthTotal, 0.5, date("m-d-Y"), '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.30 * $this->WidthTotal, 0.45, utf8_decode('NUM. TELÉFONOS CONFIGURADOS:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.55 * $this->WidthTotal, 0.5, count($this->data['configuracion_diaria']), '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.30 * $this->WidthTotal, 0.45, utf8_decode('PROYECTO:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.55 * $this->WidthTotal, 0.5, utf8_decode(Proyecto::find(Context::getId())->descripcion), '', 1, 'L');

    }

    public function cuadroRedondeado($y_inicial,$alto){
        $this->SetWidths(array(0.5 * $this->WidthTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.2));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('0,0,0'));
        $this->SetHeights(array($alto));
        $this->SetStyles(array('DF'));
        $this->SetAligns("L");
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->setY($y_inicial);
        $this->Row(array(""));
    }
    function items()
    {

        $num=1;
        $numItems = count($this->data['configuracion_diaria']);
        $a=0;
        $this->encabezadoTabla();


        foreach ($this->data['configuracion_diaria'] as $key => $item) {
            $this->SetFont('Arial', '', 5);
            $this->SetWidths(array(
                0.02 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal
            ));


            $this->encola = "items";
            $this->SetRounds(array('', '', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'C'));

            $this->SetWidths(array(
                0.02 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal,
                0.14 * $this->WidthTotal
            ));
            if ($key + 1 == $numItems ) {
                $this->SetRounds(array('4', '', '', '', '', '', '', '3'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0.2));
            }

            $this->encola = "items";
            $this->Row(array(
                    $key + 1,
                    $item['nombre'],
                    $item['usuario'],
                    $item['origen'],
                    $item['ubicacion'],
                    $item['perfil'],
                    $item['turno']
                )
            );
            $this->encola = "";
        }

    }

    function Footer()
    {
        $this->SetY($this->GetPageHeight() - 1);
        $this->SetFont('Arial', '', $this->txtFooterTam);
        $this->SetTextColor('0', '0', '0');
        $this->Cell(6.5, .4, utf8_decode('Fecha de Consulta: ' . date('Y-m-d g:i a')), 0, 0, 'L');
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(13.5, .4, '', 0, 0, 'C');
        $this->Cell(6.5, .4, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->SetY($this->GetPageHeight() - 1.3);
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(6.5, .4, utf8_decode('Formato generado desde el módulo de Control de Acarreos.'), 0, 0, 'L');
    }

    function create()
    {
        $this->SetMargins(1, 0.5, 1);
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetAutoPageBreak(true, 1.5);
        $this->items();
        $this->Ln(0.75);
        $this->Output('I', 'ConsultaViajesNetos.pdf', 1);
        exit;
    }


}