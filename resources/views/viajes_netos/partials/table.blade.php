<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <td colspan="15"><div align="right"><font color="#000000" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;"><?PHP echo 'FECHA DE CONSULTA '.date("d-m-Y")."/".date("H:i:s",time()); ?></font></div></td>
        </tr>
        <tr>
            <td colspan="32"  align="center">
                <div align="left">
                    <font color="#000000" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;">RANGO DE FECHAS :</font>
                    <font color="#666666" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;">{{ $data['rango'] }}</font>
                </div>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2"><font color="#000000" face="Trebuchet MS" style="font-size:12px; ">OBRA:</font>&nbsp;<font color="#666666" face="Trebuchet MS" style="font-size:12px; "><?php echo \App\Models\Proyecto::find(\App\Facades\Context::getId())->descripcion ?></font></td>
        </tr>

        <tr>
            <td colspan="2">
                <font color="#000000" face="Trebuchet MS" style="font-size:12px; ">TIPO DE VIAJES:</font> &nbsp;<br>
                <font color="#666666" face="Trebuchet MS" style="font-size:12px; ">
                    @foreach($data['tipos'] as $key => $tipo)
                    {{ $tipo }}<br>
                    @endforeach
                </font>
            </td>
        </tr>
    </table>
</div>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-hover small">
        <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Cami&oacute;n</th>
            <th>C&oacute;digo</th>
            <th>Fecha y Hora Llegada</th>
            <th>Origen</th>
            <th>Tiro</th>
            <th>Material</th>
            <th>Cubicaci&oacute;n</th>
            <th>Importe</th>
            <th>Registr&oacute;</th>
            <th>Autoriz&oacute;</th>
            <th>Valid&oacute;</th>
            <th>Estado</th>
            <th>Conflicto</th>
        </tr>
        </thead>
        <tbody>
            @foreach($data['viajes_netos'] as $key => $item)
            <tr>
                <td>{{ $key+ 1 }}</td>
                <td>{{ $item['tipo'] }}</td>
                <td>{{ $item['camion'] }}</td>
                <td>{{ $item['codigo'] }}</td>
                <td>{{ $item['timestamp_llegada'] }}</td>
                <td>{{ $item['origen'] }}</td>
                <td>{{ $item['tiro'] }}</td>
                <td>{{ $item['material'] }}</td>
                <td>{{ $item['cubicacion'] . " m3" }}</td>
                <td>{{ "$ ".number_format($item['importe'], 2, '.', ',') }}</td>
                <td>{{ $item['registro'] }}</td>
                <td>{{ $item['autorizo'] }}</td>
                <td>{{ $item['valido'] }}</td>
                <td>{{ $item['estado'] }}</td>
                <td>{{ $item['conflicto_pdf'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>