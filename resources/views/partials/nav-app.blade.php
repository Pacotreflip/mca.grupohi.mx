@if($currentProyecto)
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ $currentProyecto->descripcion }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>{!! link_to_route('proyectos', trans('strings.change_project')) !!}</li>
    </ul>
  </li>
  
  <li class="dropdown">
    <a tabindex="0" href="#" class="dropdown-toggle" data-toggle="dropdown" data-submenu>
        {{ trans('strings.catalogs') }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{{ route('camiones.index') }}">{{ trans('strings.camiones') }}</a></li>
        <li><a href="{{ route('marcas.index') }}">{{ trans('strings.brands') }}</a></li>
        <li><a href="{{ route('materiales.index') }}">{{ trans('strings.materials') }}</a></li>
        <li><a href="{{ route('origenes.index') }}">{{ trans('strings.origins') }}</a></li>
        <li><a href="{{ route('rutas.index') }}">{{ trans('strings.rutas') }}</a></li>
        <li><a href="{{ route('sindicatos.index') }}">{{ trans('strings.sindicatos') }}</a></li>
        <li><a href="{{ route('tiros.index') }}">{{ trans('strings.tiros') }}</a></li>
        <li class="dropdown-submenu">
            <a tabindex="0" class="dropdown-toggle" data-toggle="dropdown">{{ trans('strings.tarifas') }}</a>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="{{ route('tarifas_material.index') }}">{{ trans('strings.tarifas_material') }}</a></li>
                <li><a tabindex="-1" href="{{ route('tarifas_peso.index') }}">{{ trans('strings.tarifas_peso') }}</a></li>
                <li><a tabindex="-1" href="{{ route('tarifas_tiporuta.index') }}">{{ trans('strings.tarifas_tiporuta') }}</a></li>
            </ul>
        </li>
    </ul>
  </li>
@else
  <li><a href="{{ route('proyectos') }}">{{ trans('strings.projects') }}</a></li>
@endif
@section('scripts')
<script>
    $('[data-submenu]').submenupicker();
</script>
@stop