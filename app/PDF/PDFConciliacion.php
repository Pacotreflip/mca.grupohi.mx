<?php

namespace App\PDF;

use App\Models\Origen;
use App\Models\Proyecto;
use App\Facades\Context;
use App\Models\Tiro;
use Ghidev\Fpdf\Rotation;
use App\Models\Conciliacion\Conciliacion;
use DB;

class PDFConciliacion extends Rotation
{
    const TEMPLOGO = 'logo.png';

    /**
     * @var Conciliacion
     */
    protected $conciliacion;

    /**
     * @var Array
     */
    protected $material, $camion;

    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = '';

    /**
     * Conciliacion constructor.
     * @param string $orientation
     * @param string $unit
     * @param string $size
     * @param Conciliacion $conciliacion
     */
    public function __construct($orientation = 'P', $unit = 'cm', $size = 'A4', Conciliacion $conciliacion)
    {
        parent::__construct($orientation, $unit, $size);

        $this->SetAutoPageBreak(true, 1.5);
        $this->conciliacion = $conciliacion;
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
        $this->setY($y_inicial);
        $this->setX($x_inicial);

        $this->details();

        //Posiciones despues de details
        $y_final_1 = $this->getY();
        $this->setY($y_inicial);

        //Tabla de Detalles de Viajes Manuales
        $this->details_manuales($x_inicial);

        //Posiciones despues de Detalles de Viajes Manuales
        $y_final_2 = $this->getY();
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

        //Round Details Manuales
        $this->SetWidths(array(0.45  * $this->WidthTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.2));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('0,0,0'));
        $this->SetHeights(array($alto1));
        $this->SetStyles(array('DF'));
        $this->SetAligns("L");
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->setY($y_inicial);
        $this->setX($x_inicial+ 0.45 * $this->WidthTotal + 2);
        $this->Row(array(""));

        //Tabla de detalles de viajes manuales
        $this->setY($y_inicial);
        $this->setX(0.35 * $this->WidthTotal + 0.5);
        $this->details_manuales($x_inicial);

        //obtener Y despues de las dos tablas
        $this->setY($y_final_1 > $y_final_2 ? $y_final_1 : $y_final_2 );
        $this->Ln(1.1);

        /*$this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WidthTotal, 0.7, utf8_decode('Rutas'), 0, 1, 'L');

        //Obtener Posiciones despues de title de rutas.
        $y_inicial = $this->getY();
        $x_inicial = $this->getX();
        $this->setY($y_inicial);
        $this->setX($x_inicial);

        $this->rutas();

        //Posiciones despues de details
        $y_final = $this->getY();
        $this->setY($y_inicial);
        $alto = abs($y_final - $y_inicial);

        //Round Rutas.
        $this->SetWidths(array($this->WidthTotal));
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

        $this->setY($y_inicial);
        $this->setX($x_inicial);
        $this->rutas();
       */

        if ($this->encola != '') {

            if($this->encola == 'nuevo_material') {
                $this->SetWidths(array($this->WidthTotal));
                $this->SetFont('Arial', '', 6);
                $this->SetStyles(array('DF'));
                $this->SetRounds(array('12'));
                $this->SetRadius(array(0.2));
                $this->SetFills(array('180,180,180'));
                $this->SetTextColors(array('0,0,0'));
                $this->SetHeights(array(0.6));
                $this->SetAligns(array('L'));
            } else {
                $this->SetWidths(array($this->WidthTotal));
                $this->SetFont('Arial', '', 6);
                $this->SetStyles(array('DF'));
                $this->SetRounds(array('12'));
                $this->SetRadius(array(0.2));
                $this->SetFills(array('180,180,180'));
                $this->SetTextColors(array('0,0,0'));
                $this->SetHeights(array(0.6));
                $this->SetAligns(array('L'));
                $this->Row(array('MATERIAL : ' . utf8_decode($this->material->material)));

                $this->SetFont('Arial', '', 5);
                $this->SetWidths(array(0.125 * $this->WidthTotal, 0.075 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.05 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
                $this->SetRounds(array('4', '', '', '', '', '', '', '', '', '3'));
                $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
                $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.6));
                $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
                $this->Row(array(utf8_decode('CAMIÓN'), 'FECHA', 'ORIGEN', 'DESTINO', 'TURNO', 'CUBIC.', 'VIAJES', 'DIST.', utf8_decode('VOLÚMEN'), 'IMPORTE'));

                $this->SetRounds(array('', '', '', '', '', '', '', '', '', ''));
                $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            }
            if ($this->encola == 'items') {
                $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.35));
                $this->SetAligns(array('L', 'L', 'L', 'L', 'C', 'R', 'R', 'R', 'R', 'R'));
            } else if ($this->encola == 'subtotal_camion') {
                $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                $this->SetFills(array('221,221,221', '221,221,221', '221,221,221', '221,221,221', '221,221,221'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.35));
                $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
            } else if ($this->encola == 'subtotal_material') {
                $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                $this->SetFont('Arial', '', 6.5);
                $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF'));
                $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.6));
                $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
            } else if ($this->encola == 'total') {
                $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                $this->SetFont('Arial', '', 6.5);
                $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF'));
                $this->SetFills(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetTextColors(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                $this->SetHeights(array(0.6));
                $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
                $this->SetRounds(array('', '', '', '', ''));
                $this->SetRadius(array(0, 0, 0, 0, 0));

            }
        }
    }


    private function title()
    {
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('CONCILIACIÓN #' . $this->conciliacion->idconciliacion), 0, 1, 'L', 0);

        /*$this->SetFont('Arial', '', $this->txtSubtitleTam - 1);
        $this->CellFitScale(0.6 * $this->WidthTotal, 0.35, utf8_decode('Conciliación #' . $this->conciliacion->idconciliacion), 0, 1, 'L', 0);
        */
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);

        //Detalles de la Asignación (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.45 * $this->WidthTotal, 0.7, utf8_decode('Detalles de la Conciliación'), 0, 0, 'L');
        $this->Cell(2);
        $this->Cell(0.45 * $this->WidthTotal, .7, 'Detalles de Viajes Manuales', 0, 1, 'L');
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
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('FECHA:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, $this->conciliacion->fecha_conciliacion, '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('FOLIO:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, $this->conciliacion->Folio, '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('RANGO DE FECHAS:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, "DEL " . $this->conciliacion->fecha_inicial . " AL " . $this->conciliacion->fecha_final, '', 1, 'L');


        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('PROYECTO:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, utf8_decode(Proyecto::find(Context::getId())->descripcion), '', 1, 'L');



        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('EMPRESA:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, utf8_decode($this->conciliacion->empresa), '', 1, 'L');

        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.45, utf8_decode('SINDICATO:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.35 * $this->WidthTotal, 0.5, utf8_decode($this->conciliacion->sindicato->NombreCorto), '', 1, 'L');

    }

    private function details_manuales($x_inicial) {
        $this->setX($x_inicial + 0.45 * $this->WidthTotal + 2);
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.24 * $this->WidthTotal, 0.5, utf8_decode('IMPORTE DE VIAJES MANUALES:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.26 * $this->WidthTotal, 0.5, "$ ". $this->conciliacion->importe_viajes_manuales_f . " (" . $this->conciliacion->porcentaje_importe_viajes_manuales . "%)"   , '', 1, 'L');

        $this->setX($x_inicial + 0.45 * $this->WidthTotal + 2);
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.24 * $this->WidthTotal, 0.5, utf8_decode('VOLÚMEN DE VIAJES MANUALES:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.26 * $this->WidthTotal, 0.5, $this->conciliacion->volumen_viajes_manuales_f . " M3 (" . $this->conciliacion->porcentaje_volumen_viajes_manuales . "%)", '', 1, 'L');
    }

    private function rutas()
    {
        $rutas = '';
        foreach ($this->conciliacion->rutas as $key => $ruta) {
            $rutas .= $ruta->present()->descripcionRuta . ($key == $this->conciliacion->rutas->count() - 1 ? '' : '   //   ');

        }

        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->MultiCell($this->WidthTotal, 0.5, utf8_decode($rutas), '', 1, '');
    }

    function items()
    {

        foreach ($this->conciliacion->materiales() as $material) {

            $this->material = $material;
            $numItems = count($this->conciliacion->viajes()->where('IdMaterial', $this->material->IdMaterial));
            $i = 1;

            $this->SetWidths(array($this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF'));
            $this->SetRounds(array('12'));
            $this->SetRadius(array(0.2));
            $this->SetFills(array('180,180,180'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('L'));
            $this->Row(array('MATERIAL : ' . utf8_decode($this->material->material)));

            $this->SetFont('Arial', '', 5);
            $this->SetWidths(array(0.125 * $this->WidthTotal, 0.075 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.05 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
            $this->SetRounds(array('4', '', '', '', '', '', '', '', '', '3'));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array(utf8_decode('CAMIÓN'), 'FECHA', 'ORIGEN', 'DESTINO', 'TURNO', 'CUBIC.', 'VIAJES', 'DIST.', 'VOLUMEN', 'IMPORTE'));

            $this->SetRounds(array('', '', '', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('L', 'L', 'L', 'L', 'C', 'R', 'R', 'R', 'R', 'R'));

            foreach ($this->conciliacion->camiones() as $camion) {
                foreach (DB::connection('sca')->select(DB::raw('SELECT
                    count(v.IdViaje) as NumViajes,
				v.FechaLlegada,
				v.IdMaterial,
				v.IdOrigen,
				v.IdTiro,
				v.CubicacionCamion, 
				v.Distancia as Distancia,
                                sum(v.CubicacionCamion) as CubicacionCamionSum,
				sum(v.VolumenPrimerKM) as Vol1KM,
				sum(v.VolumenKMSubsecuentes) as VolSub,
				sum(v.VolumenKMAdicionales) as VolAdic,
				sum(v.ImportePrimerKM) as Imp1Km,
				sum(v.ImporteKMSubsecuentes) as ImpSub,
				sum(v.ImporteKMAdicionales) as ImpAdc,
				sum(v.Importe) as Importe FROM conciliacion_detalle c LEFT JOIN viajes v USING (IdViaje)
			WHERE 
				c.idconciliacion=' . $this->conciliacion->idconciliacion . '
                AND v.IdCamion=' . $camion->IdCamion . '
                AND v.idMaterial=' . $material->IdMaterial . '
                AND c.estado=1' . " " . '
			GROUP BY 
				v.FechaLlegada, 
				v.CubicacionCamion,
				v.IdMaterial,
				v.IdOrigen,
				v.IdTiro;')) as $key => $row) {

                    $this->SetFont('Arial', '', 6);
                    $this->SetWidths(array(0.125 * $this->WidthTotal, 0.075 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.05 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                    //$this->SetRounds(array('', '', '', '', '', '', '', '', '', ''));
                    //$this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
                    $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                    $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                    $this->SetHeights(array(0.35));
                    $this->SetAligns(array('L', 'L', 'L', 'L', 'C', 'R', 'R', 'R', 'R', 'R'));

                    $this->SetWidths(array(0.125 * $this->WidthTotal, 0.075 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.1875 * $this->WidthTotal, 0.05 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                    //for($cont = 0; $cont < 8; $cont++){
                    $this->encola = "items";
                    $this->Row(array($key == 0 ? $camion->Economico : '', $row->FechaLlegada, utf8_decode(Origen::find($row->IdOrigen)->Descripcion), utf8_decode(Tiro::find($row->IdTiro)->Descripcion), '1', $row->CubicacionCamion, $row->NumViajes, $row->Distancia, number_format(($row->CubicacionCamionSum), 2, '.', ','), number_format(utf8_decode($row->Importe), 2, '.', ',')));
                    //}
                    $i++;
                }

                //Subtotal Por Camión
                $subtotal_camion = DB::connection('sca')->select(DB::raw('SELECT 
						count(v.IdViaje) as NumViajes, 
						v.FechaLlegada, 
						v.IdMaterial, 
						v.IdOrigen, 
						v.IdTiro, 
						v.CubicacionCamion, 
						v.Distancia as Distancia, 
                                                sum(v.CubicacionCamion) as CubicacionCamionSum,
						sum(v.VolumenPrimerKM) as Vol1KM, 
						sum(v.VolumenKMSubsecuentes) as VolSub, 
						sum(v.VolumenKMAdicionales) as VolAdic, 
						sum(v.ImportePrimerKM) as Imp1Km, 
						sum(v.ImporteKMSubsecuentes) as ImpSub, 
						sum(v.ImporteKMAdicionales) as ImpAdc, 
						sum(v.Importe) as Importe 
					FROM conciliacion_detalle c LEFT JOIN viajes v USING (IdViaje) 
					WHERE 
						c.idconciliacion=' . $this->conciliacion->idconciliacion . ' 
						and v.IdCamion=' . $camion->IdCamion . ' 
						and v.IdMaterial=' . $material->IdMaterial . '
                        and c.estado=1' . " " . '
						GROUP BY v.IdCamion;'));

                if ($subtotal_camion) {
                    $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
                    $this->SetFills(array('221,221,221', '221,221,221', '221,221,221', '221,221,221', '221,221,221'));
                    $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                    $this->SetHeights(array(0.35));
                    $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
                    $this->encola = "subtotal_camion";
                    $this->Row(array(utf8_decode('SUBTOTAL CAMIÓN'), $subtotal_camion[0]->NumViajes, '', number_format(($subtotal_camion[0]->CubicacionCamionSum), 2, '.', ','), number_format(utf8_decode($subtotal_camion[0]->Importe), 2, '.', ',')));
                    if($i < $numItems) {
                        $this->encola = 'items';
                    } else {
                        $this->encola = '';
                    }
                }
            }

            //Subtotal Material
            $subtotal_material = DB::connection('sca')->select(DB::raw('SELECT 
							count(v.IdViaje) as NumViajes, 
							v.FechaLlegada, 
							v.IdMaterial, 
							v.IdOrigen, 
							v.IdTiro, 
							v.CubicacionCamion, 
							v.Distancia as Distancia, 
                                                        sum(v.CubicacionCamion) as CubicacionCamionSum,
							sum(v.VolumenPrimerKM) as Vol1KM, 
							sum(v.VolumenKMSubsecuentes) as VolSub, 
							sum(v.VolumenKMAdicionales) as VolAdic, 
							sum(v.ImportePrimerKM) as Imp1Km, 
							sum(v.ImporteKMSubsecuentes) as ImpSub, 
							sum(v.ImporteKMAdicionales) as ImpAdc, 
							sum(v.Importe) as Importe 
						FROM 
							conciliacion_detalle c LEFT JOIN viajes v USING (IdViaje) 
						WHERE 
							c.idconciliacion=' . $this->conciliacion->idconciliacion . '
                        AND v.IdMaterial=' . $material->IdMaterial . '
                        AND c.estado=1' . " " . '
								
						GROUP BY 
							c.idconciliacion,
							v.IdMaterial;'))[0];

            $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6.5);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF'));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
            $this->SetRounds(array('4', '', '', '', '3'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0.2));
            $this->encola = 'subtotal_material';
            $this->Row(array('SUBTOTAL MATERIAL : ' . utf8_decode($this->material->material), $subtotal_material->NumViajes, '', number_format(($subtotal_material->CubicacionCamionSum), 2, '.', ','), number_format(utf8_decode($subtotal_material->Importe), 2, '.', ',')));
            $this->ln(0.25);
            $this->encola = 'nuevo_material';
        }

        //Total
        $total = DB::connection('sca')->select(DB::raw('SELECT count(v.IdViaje) as NumViajes, v.FechaLlegada, v.IdMaterial, v.IdOrigen, v.IdTiro, v.CubicacionCamion, v.Distancia as Distancia, sum(v.CubicacionCamion) as CubicacionCamionSum, sum(v.VolumenPrimerKM) as Vol1KM, sum(v.VolumenKMSubsecuentes) as VolSub, sum(v.VolumenKMAdicionales) as VolAdic, sum(v.ImportePrimerKM) as Imp1Km, sum(v.ImporteKMSubsecuentes) as ImpSub, sum(v.ImporteKMAdicionales) as ImpAdc, sum(v.Importe) as Importe FROM conciliacion_detalle c LEFT JOIN viajes v USING (IdViaje) WHERE c.estado=1 AND c.idconciliacion=' . $this->conciliacion->idconciliacion . ' GROUP BY c.idconciliacion;'))[0];
        $this->SetWidths(array(0.6875 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.0625 * $this->WidthTotal, 0.09375 * $this->WidthTotal, 0.09375 * $this->WidthTotal));
        $this->SetFont('Arial', '', 6.5);
        $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'DF'));
        $this->SetFills(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetTextColors(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
        $this->SetHeights(array(0.6));
        $this->SetAligns(array('L', 'R', 'R', 'R', 'R'));
        $this->SetRounds(array('', '', '', '', ''));
        $this->SetRadius(array(0, 0, 0, 0, 0));
        $this->encola = 'total';
        $this->Row(array('TOTAL', $total->NumViajes, '', number_format(($total->CubicacionCamionSum), 2, '.', ','), number_format(utf8_decode($total->Importe), 2, '.', ',')));
        $this->encola = '';
    }

    function Footer()
    {
        $this->SetY(-4);
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
        
        $this->SetY($this->GetPageHeight() - 1);
        $this->SetFont('Arial', '', $this->txtFooterTam);
        $this->SetTextColor('0', '0', '0');
        $this->Cell(6.5, .4, utf8_decode('Fecha de Consulta: ' . date('Y-m-d g:i a')), 0, 0, 'L');
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(6.5, .4, '', 0, 0, 'C');
        $this->Cell(6.5, .4, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->SetY($this->GetPageHeight() - 1.3);
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(6.5, .4, utf8_decode('Formato generado desde el módulo de Control de Acarreos.'), 0, 0, 'L');

        if($this->conciliacion->estado == -1 || $this->conciliacion->estado == -2){
            $this->SetFont('Arial','',80);
            $this->SetTextColor(204,204,204);
            $this->RotatedText(2,20,utf8_decode("CONCILIACIÓN"),45);
            $this->RotatedText(7,20,"CANCELADA",45);
            $this->SetTextColor('0,0,0');
        } else if($this->conciliacion->estado == 0 || $this->conciliacion->estado == 1) {
            $this->SetFont('Arial','',80);
            $this->SetTextColor(204,204,204);
            $this->RotatedText(2,20,utf8_decode("PENDIENTE DE"),45);
            $this->RotatedText(7,20,utf8_decode("APROBACIÓN"),45);
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
        $this->SetAutoPageBreak(true,4.5);
        $this->items();
        $this->Output('I', 'Conciliacion.pdf', 1);
        exit;
    }
}
