<?php

namespace App\PDF;

use App\Models\Cortes\Corte;
use App\Models\Proyecto;
use App\Facades\Context;
use App\Models\Transformers\ViajeNetoCorteTransformer;
use App\Models\Transformers\ViajeNetoTransformer;
use Ghidev\Fpdf\Rotation;

class PDFCorte extends Rotation
{
    const TEMPLOGO = 'logo.png';

    /**
     * @var Corte
     */
    protected $corte;

    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = '';

    /**
     * PDFCorte constructor.
     * @param string $orientation
     * @param array|string $unit
     * @param string $size
     * @param Corte $corte
     * @internal param array $data
     */
    public function __construct($orientation = 'P', $unit = 'cm', $size = 'A4', Corte $corte)
    {
        parent::__construct($orientation, $unit, $size);

        $this->SetAutoPageBreak(true, 1.5);
        $this->corte = $corte;
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

        if ($this->encola == 'items_confirmados') {
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES CONFIRMADOS ('. $this->corte->viajes_netos_confirmados()->count().')'), 0, 1, 'L');
            $this->SetFont('Arial', 'B', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF','DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF'));
            $this->widths_items_confirmados();
            $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '', '', '', '', '', '','2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C','C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array(
                "#",
                utf8_decode("Camión"),
                utf8_decode("Código"),
                "Fecha y Hora Llegada",
                "Origen",
                "Tiro",
                "Material",
                utf8_decode("Cubic. (m3)"),
                "Importe",
                utf8_decode("Checador Primer Toque"),
                utf8_decode("Checador Segundo Toque"),
                "Origen Nuevo",
                "Tiro Nuevo",
                "Material Nuevo",
                "Cubic. Nueva (m3)",
                utf8_decode("Justificación")));

            $this->SetRounds(array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L', 'L', 'L', 'L', 'R', 'L'));
        }

        if ($this->encola == "items_no_confirmados") {
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES NO CONFIRMADOS ('.$this->corte->viajes_netos_no_confirmados()->count().')'), 0, 1, 'L');
            $this->SetFont('Arial', 'B', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF','DF', 'DF', 'DF', 'DF', 'DF', 'DF'));
            $this->widths_items_no_confirmados();
            $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '','2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array(
                "#",
                utf8_decode("Camión"),
                utf8_decode("Código"),
                "Fecha y Hora Llegada",
                "Origen",
                "Tiro",
                "Material",
                utf8_decode("Cubic. (m3)"),
                "Importe",
                utf8_decode("Checador Primer Toque"),
                utf8_decode("Checador Segundo Toque")));

            $this->SetRounds(array('', '', '', '', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L'));
        }
    }


    private function title()
    {
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('CORTE DE CHECADOR'), 0, 1, 'L', 0);

        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);

        //Detalles (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.45 * $this->WidthTotal, 0.7, utf8_decode('Detalles'), 0, 1, 'L');
    }

    public function widths_items_confirmados() {
        $this->SetWidths(array(
            0.035 * 19.59,
            0.053 * 19.59,
            0.083 * 19.59,
            0.063 * 19.59,
            0.083 * 19.59,
            0.083 * 19.59,
            0.083 * 19.59,
            0.05 * 19.59,
            0.073 * 19.59,
            0.1 * 19.59,
            0.1 * 19.59,
            0.083 * 19.59,
            0.083 * 19.59,
            0.083 * 19.59,
            0.05 * 19.59,
            0.175 * 19.59
        ));
    }

    public function widths_items_no_confirmados() {
        $this->SetWidths(array(
            0.078 * 19.59,
            0.096 * 19.59,
            0.125 * 19.59,
            0.105 * 19.59,
            0.125 * 19.59,
            0.125 * 19.59,
            0.125 * 19.59,
            0.093 * 19.59,
            0.11 * 19.59,
            0.14 * 19.59,
            0.14 * 19.59
        ));
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

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('CHECADOR:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode($this->corte->checador->present()->nombreCompleto), '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('FECHA y HORA DEL CORTE:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode($this->corte->timestamp->format('d-M-Y h:i:s a')), '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.2 * $this->WidthTotal, 0.45, utf8_decode('NÚMERO DE VIAJES:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.3 * $this->WidthTotal, 0.5, utf8_decode($this->corte->corte_detalles->count()), '', 1, 'L');
    }

    function items_confirmados()
    {
        $numItems = count($this->corte->viajes_netos_confirmados());

        $this->SetWidths(array(0));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('1,1,1'));
        $this->SetRounds(array('0'));
        $this->SetRadius(array(0));
        $this->SetHeights(array(0));
        $this->Row(Array(''));
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->SetTextColors(array('255,255,255'));
        $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES CONFIRMADOS ('. $this->corte->viajes_netos_confirmados()->count().')'), 0, 1, 'L');
        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF', 'FD', 'DF','DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'DF'));
        $this->widths_items_confirmados();
        $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2'));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.3));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array(
            "#",
            utf8_decode("Camión"),
            utf8_decode("Código"),
            "Fecha y Hora Llegada",
            "Origen",
            "Tiro",
            "Material",
            utf8_decode("Cubic. (m3)"),
            "Importe",
            utf8_decode("Checador Primer Toque"),
            utf8_decode("Checador Segundo Toque"),
            "Origen Nuevo",
            "Tiro Nuevo",
            "Material Nuevo",
            "Cubic. Nueva (m3)",
            utf8_decode("Justificación")));

        foreach (ViajeNetoCorteTransformer::transform($this->corte->viajes_netos_confirmados()) as $key => $item) {
            $this->SetFont('Arial', '', 5);
            $this->widths_items_confirmados();

            $this->encola = "items_confirmados";
            $this->SetRounds(array('', '', '', '', '','', '', '', '', '','', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L', 'L', 'L', 'L', 'R', 'L'));

            if ($key + 1 == $numItems ) {
                $this->SetRounds(array('4', '', '', '', '', '',  '', '', '', '', '', '', '', '', '', '3'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
            }
            $this->widths_items_confirmados();


            $this->encola = "items_confirmados";
//                for($cont = 0; $cont < 50; $cont ++){


            $this->Row(array($key + 1,
                $item['camion'],
                $item['codigo'],
                $item['timestamp_llegada'],
                utf8_decode($item['origen']),
                utf8_decode($item['tiro']),
                utf8_decode($item['material']),
                $item['cubicacion'],
                "$ ".number_format($item['importe'], 2, '.', ','),
                utf8_decode($item['registro_primer_toque']),
                utf8_decode($item['registro']),
                utf8_decode($item['corte_cambio']['origen_nuevo']),
                utf8_decode($item['corte_cambio']['tiro_nuevo']),
                utf8_decode($item['corte_cambio']['material_nuevo']),
                utf8_decode($item['corte_cambio']['cubicacion_nueva']),
                utf8_decode($item['corte_cambio']['justificacion'])
            ));
//                }
            $this->encola = "";
        }
    }

    function items_no_confirmados()
    {
        $numItems = count($this->corte->viajes_netos_no_confirmados());

        $this->SetWidths(array(0));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('1,1,1'));
        $this->SetRounds(array('0'));
        $this->SetRadius(array(0));
        $this->SetHeights(array(0));
        $this->Row(Array(''));
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->SetTextColors(array('255,255,255'));
        $this->CellFitScale($this->WidthTotal, 1, utf8_decode('VIAJES NO CONFIRMADOS ('.$this->corte->viajes_netos_no_confirmados()->count().')'), 0, 1, 'L');
        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF', 'FD', 'DF','DF', 'DF', 'DF', 'DF'));
        $this->widths_items_no_confirmados();
        $this->SetRounds(array('1', '', '', '', '', '', '', '', '', '', '2'));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180','180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0','0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.3));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array(
            "#",
            utf8_decode("Camión"),
            utf8_decode("Código"),
            "Fecha y Hora Llegada",
            "Origen",
            "Tiro",
            "Material",
            utf8_decode("Cubic. (m3)"),
            "Importe",
            utf8_decode("Checador Primer Toque"),
            utf8_decode("Checador Segundo Toque")));

        foreach (ViajeNetoCorteTransformer::transform($this->corte->viajes_netos_no_confirmados()) as $key => $item) {
            $this->SetFont('Arial', '', 5);
            $this->widths_items_no_confirmados();

            $this->encola = "items_no_confirmados";
            $this->SetRounds(array('', '', '', '', '','', '', '', '', '',''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L','L', 'R', 'R', 'L', 'L'));

            if ($key + 1 == $numItems ) {
                $this->SetRounds(array('4', '', '', '', '', '', '', '', '', '', '3'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.2));
            }
            $this->widths_items_no_confirmados();


            $this->encola = "items_no_confirmados";

            $this->Row(array($key + 1,
                $item['camion'],
                $item['codigo'],
                $item['timestamp_llegada'],
                utf8_decode($item['origen']),
                utf8_decode($item['tiro']),
                utf8_decode($item['material']),
                $item['cubicacion'],
                "$ ".number_format($item['importe'], 2, '.', ','),
                utf8_decode($item['registro_primer_toque']),
                utf8_decode($item['registro'])
            ));
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

        if($this->corte->estatus == 1) {
            $this->SetFont('Arial', '', 75);
            $this->SetTextColor(204, 204, 204);
            $this->RotatedText(1.5, 21, utf8_decode("PENDIENTE DE CIERRE"), 39);
            $this->SetTextColor('0,0,0');
        }

    }

    function RotatedText($x,$y,$txt,$angle)
    {
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    function create() {
        $this->SetMargins(1, 0.5, 1);
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetAutoPageBreak(true,2);
        $this->items_confirmados();
        $this->Ln(0.75);
        $this->items_no_confirmados();
        $this->Output('I', "Corte{$this->corte->id}.pdf", 1);
        exit;
    }
}