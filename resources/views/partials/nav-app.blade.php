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
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ trans('strings.catalogs') }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{{ route('tiros.index') }}"><i class="fa fa-"></i>{{ trans('strings.tiros') }}</a></li>
        <li><a href="{{ route('marcas.index') }}"><i class="fa fa-"></i>{{ trans('strings.brands') }}</a></li>
        <li><a href="{{ route('materiales.index') }}">{{ trans('strings.materials') }}</a></li>
        <li><a href="{{ route('origenes.index') }}">{{ trans('strings.origins') }}</a></li>
        <li><a href="{{ route('rutas.index') }}">{{ trans('strings.rutas') }}</a></li>
        <li><a href="{{ route('sindicatos.index') }}">{{ trans('strings.sindicatos') }}</a></li>
    </ul>
  </li>
@else
  <li><a href="{{ route('proyectos') }}">{{ trans('strings.projects') }}</a></li>
@endif
