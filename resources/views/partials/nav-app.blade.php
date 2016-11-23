@if($currentProyecto)
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ $currentProyecto->descripcion }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>{!! link_to_route('proyectos', trans('strings.change_project')) !!}</li>
    </ul>
  </li>
@else
  <li><a href="{{ route('proyectos') }}">{{ trans('strings.projects') }}</a></li>
@endif