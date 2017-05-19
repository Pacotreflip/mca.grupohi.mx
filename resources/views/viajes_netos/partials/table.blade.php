<div>
    <table>
        <tr>
            <td colspan="15"><div align="right"><span style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px; color: #000000; "><?PHP echo 'FECHA DE CONSULTA '.date("d-m-Y")."/".date("H:i:s",time()); ?></span></div></td>
        </tr>
        <tr>
            <td colspan="26"  align="center">
                <div align="left">
                    <span style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px; color: #000000; ">RANGO DE FECHAS :</span>
                    <span style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px; color: #666666; ">{{ $data['rango'] }}</span>
                </div>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2"><span style="font-size:12px;  color: #000000; font-family: Trebuchet MS; ">OBRA:</span>&nbsp;
                <span style="font-size:12px;  color: #666666; font-family: Trebuchet MS; "><?php echo \App\Models\Proyecto::find(\App\Facades\Context::getId())->descripcion ?></span>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <span style="font-size:12px;  color: #000000; font-family: Trebuchet MS; ">TIPO DE VIAJES:</span> &nbsp;<br>
                <span style="font-size:12px;  color: #666666; font-family: Trebuchet MS; ">
                    @foreach($data['tipos'] as $key => $tipo)
                    {{ $tipo }}<br>
                    @endforeach
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span style="font-size:12px;  color: #000000; font-family: Trebuchet MS; ">ESTADO DE LOS VIAJES:</span>
                <span style="font-size:12px;  color: #666666; font-family: Trebuchet MS; ">{{ $data['estado'] }}</span>
            </td>
        </tr>
    </table>
</div>
<br>
<div>
    <table border="1">
        <thead>
        <tr style="background-color: #f9f9f9">
            <th colspan="5">INFORMACI&Oacute;N GENERAL DEL VIAJE</th>
            <th colspan="4">DETALLES DEL VIAJE</th>
            <th colspan="2">CAMI&Oacute;N</th>
            <th colspan="3">EMPRESA</th>
            <th colspan="3">SINDICATO</th>
            <th colspan="3">CONCILIACI&Oacute;N</th>
            <th colspan="5">ESTADO DEL VIAJE</th>
        </tr>
        <tr style="background-color: #f9f9f9">
            <th> # </th>
            <th> TIPO </th>
            <th> FECHA LLEGADA </th>
            <th> HORA LLEGADA</th>
            <th> TICKET - C&Oacute;DIGO </th>

            <th> ORIGEN</th>
            <th> TIRO </th>
            <th> MATERIAL </th>
            <th> IMPORTE </th>

            <th> ECON&Oacute;MICO </th>
            <th> CUBICACI&Oacute;N	</th>

            <th> CAMI&Oacute;N </th>
            <th> VIAJE NETO </th>
            <th> VIAJE </th>
            <th> CAMI&Oacute;N </th>
            <th> VIAJE NETO </th>
            <th> VIAJE </th>

            <th> CONCILI&Oacute; </th>
            <th> FOLIO </th>
            <th> FECHA  </th>

            <th> REGISTR&Oacute; </th>
            <th> AUTORIZ&Oacute; </th>
            <th> VALID&Oacute; </th>
            <th> ESTADO </th>
            <th> CONFLICTO </th>
        </tr>
        </thead>
        <tbody>
            @foreach($data['viajes_netos'] as $key => $item)
            <tr>
                <td>{{ $key+ 1 }}</td>
                <td>{{ $item->tipo }}</td>
                <td>{{ $item->FechaLlegada }}</td>
                <td>{{ $item->HoraLlegada }}</td>
                <td>{{ $item->Code }}</td>
                <td>{{ $item->origen }}</td>
                <td>{{ $item->tiro }}</td>
                <td>{{ $item->material }}</td>
                <td>{{ "$ ".number_format($item->importe, 2, '.', ',') }}</td>
                <td>{{ $item->camion }}</td>
                <td>{{ $item->cubicacion . " m3" }}</td>
                <td>{{ $item->empresa_camion }}</td>
                <td>{{ $item->empresa_viajeneto }}</td>
                <td>{{ $item->empresa_viaje }}</td>
                <td>{{ $item->sindicato_camion }}</td>
                <td>{{ $item->sindicato_viajeneto }}</td>
                <td>{{ $item->sindicato_viaje }}</td>
                <td>{{ $item->concilio }}</td>
                <td>{{ $item->id_conciliacion }}</td>
                <td>{{ $item->fecha_conciliacion }}</td>
                <td>{{ $item->registro }}</td>
                <td>{{ $item->autorizo }}</td>
                <td>{{ $item->valido }}</td>
                <td>{{ $item->estado }}</td>
                <td>{{ $item->conflicto_pdf }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>