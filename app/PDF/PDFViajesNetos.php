<?php

namespace App\PDF;

use App\Models\Proyecto;
use App\Facades\Context;
use Ghidev\Fpdf\Rotation;

class PDFViajesNetos extends Rotation
{
    const TEMPLOGO = 'logo.png';

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

        //Obtener Posiciones despues de title y logo.
        $y_inicial = $this->getY();
        $x_inicial = $this->getX();

        $this->details();

        //Posiciones despues de details
        $y_final_1 = $this->getY();
        $this->setY($y_inicial);

        $alto1 = abs($y_final_1 - $y_inicial);

        //Round Detalis.
        $this->SetWidths(array(0.5 * $this->WidthTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.2));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('0,0,0'));
        $this->SetHeights(array($alto1));
        $this->SetStyles(array('DF'));
        $this->SetAligns("L");
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->setY($y_inicial);
        $this->Row(array(""));

        $this->setY($y_inicial);
        $this->setX($x_inicial);
        $this->details();

        //obtener Y despues de tabla de detalles
        $this->setY($y_final_1 );
        $this->Ln(0.5);

        if ($this->encola == 'items') {
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES'), 0, 1, 'L');
            $this->SetFont('Arial', '', 5);
            $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF','DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'FD', 'DF', 'DF','DF'));
        $this->SetWidths(array(
            0.02 * $this->WidthTotal,
            0.04 * $this->WidthTotal,
            0.045 * $this->WidthTotal,
            0.065 * $this->WidthTotal,
            0.075 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.03 * $this->WidthTotal,
            0.0675 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.12 * $this->WidthTotal
        ));
        $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '', '', '', '','', '2'));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.5));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array(
            "#",
            "Tipo",
            utf8_decode("Camión"),
            utf8_decode("Código"),
            "Fecha y Hora Llegada",
            "Origen",
            "Tiro",
            "Material",
            utf8_decode("Cub."),
            "Importe",
            utf8_decode("Registró"),
            utf8_decode("Autorizó"),
            utf8_decode("Validó"),
            "Estado",
            "Conflicto"));

            $this->SetFont('Arial', '', 5);
            $this->SetWidths(array(
                0.02 * $this->WidthTotal,
                0.04 * $this->WidthTotal,
                0.045 * $this->WidthTotal,
                0.065 * $this->WidthTotal,
                0.075 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.03 * $this->WidthTotal,
                0.0675 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.12 * $this->WidthTotal
            ));
            $this->encola = "items";
            $this->SetRounds(array('', '', '', '', '', '', '', '','', '', '', '', '','', ''));
            $this->SetRadius(array(0, 0, 0,0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255','255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L','L','L','L'));
        }
    }


    private function title()
    {
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('REPORTE DE VIAJES'), 0, 1, 'L', 0);

        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);

        //Detalles (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.45 * $this->WidthTotal, 0.7, utf8_decode('Detalles'), 0, 1, 'L');
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

    private function details()
    {
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('PROYECTO:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode(Proyecto::find(Context::getId())->descripcion), '', 1, 'L');

        foreach($this->data['tipos'] as $key => $tipo) {
            if($key == 0) {
                $this->SetFont('Arial', 'B', $this->txtContenidoTam);
                $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('TIPO DE VIAJES :'), '', 0, 'LB');
                $this->SetFont('Arial', '', $this->txtContenidoTam);
                $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode($tipo), '', 1, 'L');
            } else {
                $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode(''), '', 0, 'LB');
                $this->SetFont('Arial', '', $this->txtContenidoTam);
                $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode($tipo), '', 1, 'L');
            }

        }
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('ESTADO DE LOS VIAJES:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, $this->data['estado'], '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('RANGO DE FECHAS:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, $this->data['rango'], '', 1, 'L');

         }

    function items()
    {
        $numItems = count($this->data['viajes_netos']);

        $this->SetWidths(array(0));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('1,1,1'));
        $this->SetRounds(array('0'));
        $this->SetRadius(array(0));
        $this->SetHeights(array(0));
        $this->Row(Array(''));
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->SetTextColors(array('255,255,255'));
        $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES'), 0, 1, 'L');
        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF','DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'FD', 'DF', 'DF','DF'));
        $this->SetWidths(array(
            0.02 * $this->WidthTotal,
            0.04 * $this->WidthTotal,
            0.045 * $this->WidthTotal,
            0.065 * $this->WidthTotal,
            0.075 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.06 * $this->WidthTotal,
            0.03 * $this->WidthTotal,
            0.0675 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.09 * $this->WidthTotal,
            0.12 * $this->WidthTotal
        ));
        $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '', '', '', '','', '2'));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.5));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array(
            "#",
            "Tipo",
            utf8_decode("Camión"),
            utf8_decode("Código"),
            "Fecha y Hora Llegada",
            "Origen",
            "Tiro",
            "Material",
            utf8_decode("Cub."),
            "Importe",
            utf8_decode("Registró"),
            utf8_decode("Autorizó"),
            utf8_decode("Validó"),
            "Estado",
            "Conflicto"));

        foreach ($this->data['viajes_netos'] as $key => $item) {
            $this->SetFont('Arial', '', 5);
            $this->SetWidths(array(
                0.02 * $this->WidthTotal,
                0.04 * $this->WidthTotal,
                0.045 * $this->WidthTotal,
                0.065 * $this->WidthTotal,
                0.075 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.03 * $this->WidthTotal,
                0.0675 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.12 * $this->WidthTotal
            ));
            $this->encola = "items";
            $this->SetRounds(array('', '', '', '', '', '', '', '','', '', '', '', '','', ''));
            $this->SetRadius(array(0, 0, 0,0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255','255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L','L','L','L'));

            if ($key + 1 == $numItems ) {
                $this->SetRounds(array('4', '', '', '', '', '', '', '', '', '', '', '', '','', '3'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0.2));
            }
            $this->SetWidths(array(
                0.02 * $this->WidthTotal,
                0.04 * $this->WidthTotal,
                0.045 * $this->WidthTotal,
                0.065 * $this->WidthTotal,
                0.075 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.06 * $this->WidthTotal,
                0.03 * $this->WidthTotal,
                0.0675 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.09 * $this->WidthTotal,
                0.12 * $this->WidthTotal
            ));

            $this->encola = "items";
//                for($cont = 0; $cont < 50; $cont ++){
            $this->Row(array($key + 1,
                utf8_decode($item['tipo']),
                $item['camion'],
                $item['codigo'],
                $item['timestamp_llegada'],
                utf8_decode($item['origen']),
                utf8_decode($item['tiro']),
                utf8_decode($item['material']),
                $item['cubicacion'] . " m3",
                "$ ".number_format($item['importe'], 2, '.', ','),
                utf8_decode($item['registro']),
                utf8_decode($item['autorizo']),
                utf8_decode($item['valido']),
                utf8_decode($item['estado']),
                utf8_decode($item['conflicto_pdf'])
                )
                    );
//                }
            $this->encola = "";
        }
    }

    function Footer()
    {
       /* $this->SetY(-4);
        $this->SetTextColor('0', '0', '0');
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(180, 180, 180);
        $this->Cell(3.92, .4, utf8_decode('Elaboró'), 'TRLB', 0, 'C', 1);
        $this->Cell(3.915);
        $this->Cell(3.92, .4, utf8_decode('Revisó'), 'TRLB', 0, 'C', 1);
        $this->Cell(3.915);
        $this->Cell(3.92, .4, utf8_decode('Autorizó'), 'TRLB', 0, 'C', 1);
        $this->Ln();


        $this->Cell(3.92, 1.2, '', 'TRLB', 0, 'C');
        $this->Cell(3.915);
        $this->Cell(3.92, 1.2, '', 'TRLB', 0, 'C');
        $this->Cell(3.915);
        $this->Cell(3.92, 1.2, '', 'TRLB', 0, 'C');
        $this->Ln();
        //$this->SetFillColor(180, 180, 180);
        $this->Cell(3.92, .4, $this->conciliacion->registro, 'TRLB', 0, 'C', 1);
        $this->Cell(3.915);
        $this->Cell(3.92, .4, $this->conciliacion->cerro, 'TRLB', 0, 'C', 1);
        $this->Cell(3.915);
        $this->Cell(3.92, .4, $this->conciliacion->aprobo, 'TRLB', 0, 'C', 1);
       */
        
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

    function create() {
        $this->SetMargins(1, 0.5, 1);
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetAutoPageBreak(true,1.5);
        $this->items();
        $this->Ln(0.75);
        $this->Output('I', 'ConsultaViajesNetos.pdf', 1);
        exit;
    }
}