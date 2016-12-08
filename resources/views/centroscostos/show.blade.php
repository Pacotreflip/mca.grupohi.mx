<tr bgcolor="#86F784" id="{{$centro->IdCentroCosto}}" class="treegrid-{{$centro->IdCentroCosto}} treegrid-parent-{{$centro->IdPadre}}">
    <td>{{$centro->Descripcion}}</td>
    <td>{{$centro->Cuenta}}</td>
    <td>
        <a href="{{route('centroscostos.create', $centro)}}" class="btn btn-success btn-xs centrocosto_create" type="button">
            <i class="fa fa-plus-circle"></i>
        </a>
    </td>
    <td>{{$centro->present()->estatus}}</td>
</tr>