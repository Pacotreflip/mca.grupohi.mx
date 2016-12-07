<tr id="{{$centro->IdCentroCosto}}" class="treegrid-{{$centro->IdCentroCosto}} treegrid-parent-{{$centro->IdPadre}}">
    <td>
        @for ($i = 1; $i < $centro->getLevel(); $i++)<span class="treegrid-indent"></span>@endfor<span class="treegrid-expander"></span>
        {{ $centro->Descripcion }}
    </td>
    <td>{{ $centro->Cuenta }}</td>
    <td>
        <a href="{{ route('centroscostos.create', $centro) }}" class="btn btn-success btn-xs centrocosto_create" type="button">
            <i class="fa fa-plus-circle"></i>
        </a>
    </td>
    <td>{{$centro->Estatus}}</td>
</tr>