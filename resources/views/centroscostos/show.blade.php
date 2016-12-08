<tr bgcolor="{{$type}}" id="{{$centro->IdCentroCosto}}" class="treegrid-{{$centro->IdCentroCosto}} treegrid-parent-{{$centro->IdPadre}}">
    <td>{{$centro->Descripcion}}</td>
    <td>{{$centro->Cuenta}}</td>
    <td>
        <a href="{{ route('centroscostos.edit', $centro) }}" class="btn btn-info btn-xs centrocosto_edit" type="button">
            <i class="fa fa-pencil-square-o"></i>
        </a>
        <a href="{{ route('centroscostos.create', $centro) }}" class="btn btn-success btn-xs centrocosto_create" type="button">
            <i class="fa fa-plus-circle"></i>
        </a>
        <a href="{{ route('centroscostos.destroy', $centro) }}" class="btn btn-danger btn-xs centrocosto_destroy" type="button">
            <i class="fa fa-minus-circle"></i>
        </a>
    </td>
    <td>
        <a href="{{ route('centroscostos.destroy', $centro) }}" class="btn btn-xs centrocosto_toggle {{ $centro->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}">
            {{ $centro->Estatus == 1 ? trans('strings.deactivate') : trans('strings.activate') }}
        </a>
    </td>
</tr>