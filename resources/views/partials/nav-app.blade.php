@if($currentProyecto)
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ $currentProyecto->descripcion }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>{!! link_to_route('proyectos', 'Cambiar De Proyecto') !!}</li>
    </ul>
  </li>
  @if (Auth::user()->can(['control-catalogos'])) 
  <li class="dropdown">
    <a tabindex="0" href="#" class="dropdown-toggle" data-toggle="dropdown" data-submenu>
        Catálogos <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{{ route('centroscostos.index') }}">Centros De Costo</a></li>
        <li><a href="{{ route('camiones.index') }}">Camiones</a></li>
        <li><a href="{{ route('empresas.index') }}">Empresas</a></li>
        <li><a href="{{ route('etapas.index') }}">Etapas De Proyecto</a></li>
        <li class="dropdown-submenu">
            <a tabindex="0" class="dropdown-toggle" data-toggle="dropdown">Factores De Abundamiento</a>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="{{ route('fda_material.index') }}">Por Material</a></li>
                <li><a tabindex="-1" href="{{ route('fda_banco_material.index') }}">Por Banco Y Material</a></li>
            </ul>
        </li>
        <li><a href="{{ route('marcas.index') }}">Marcas</a></li>
        <li><a href="{{ route('materiales.index') }}">Materiales</a></li>
        <li><a href="{{ route('operadores.index') }}">Operadores</a></li>
        <li><a href="{{ route('origenes.index') }}">Origenes</a></li>
        <li><a href="{{ route('origenes_usuarios.index') }}">Origenes Por Usuario</a></li>
        <li><a href="{{ route('rutas.index') }}">Rutas</a></li>
        <li><a href="{{ route('sindicatos.index') }}">Sindicatos</a></li>
        <li><a href="{{ route('tiros.index') }}">Tiros</a></li>
        <li class="dropdown-submenu">
            <a tabindex="0" class="dropdown-toggle" data-toggle="dropdown">Tarifas</a>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="{{ route('tarifas_material.index') }}">Tarifas Por Material</a></li>
                <li><a tabindex="-1" href="{{ route('tarifas_peso.index') }}">Tarifas Por Peso</a></li>
                <li><a tabindex="-1" href="{{ route('tarifas_tiporuta.index') }}">Tarifas Por Tipo De Ruta</a></li>
            </ul>
        </li>
    </ul>
  </li>
  @endif
  <li class="dropdown">
    <a tabindex="0" href="#" class="dropdown-toggle" data-toggle="dropdown" data-submenu>
        Operación<span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">

        <li><a tabindex="-2" href="{{ route('viajes_netos.index') }}">Viajes</a></li>

        <li class="dropdown-submenu">
            <a tabindex="0" class="dropdown-toggle" data-toggle="dropdown">Registrar Viajes</a>  
            <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                    <a tabindex="-1" class="dropdown-toggle" data-toggle="dropdown">Carga Manual</a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->can(['ingresar-viajes-manuales']))
                        <li><a tabindex="-2" href="{{ route('viajes_netos.create', ['action' => 'manual']) }}">Ingresar Viajes</a></li>
                        @endif
                        @if(Auth::user()->can(['autorizar-viajes-manuales']))
                        <li><a tabindex="-2" href="{{ route('viajes_netos.edit', ['action' => 'autorizar']) }}">Autorizar Viajes</a></li>
                        @endif
                    </ul>
                </li>
                @if(Auth::user()->can(['ingresar-viajes-manuales-completos']))
                <li><a href="{{ route('viajes_netos.create', ['action' => 'completa']) }}">Carga Manual Completa</a></li>
                @endif
            </ul>
        </li>
        @if(Auth::user()->can(['validar-viajes']))
        <li><a href="{{ route('viajes_netos.edit', ['action' => 'validar']) }}">Validar Viajes</a></li>
        @endif
        @if (Auth::user()->can(['consultar-conciliacion'])) 
            <li><a href="{{ route('conciliaciones.index') }}">Conciliaciones</a></li>
        @endif
        @if(Auth::user()->can(['modificar-viajes']))
        <li><a href="{{ route('viajes_netos.edit', ['action' => 'modificar']) }}">Modificar Viajes</a></li>
        @endif
        @if(Auth::user()->can(['revertir-viajes']))
        <li><a href="{{ route('viajes.edit', ['action' => 'revertir']) }}">Revertir Viajes</a> </li>
        @endif
        @if (Auth::user()->can(['consultar-viajes-conflicto']))
        <li><a href="{{ route('viajes_netos.index', ['action' => 'en_conflicto']) }}">Viajes en Conflicto</a> </li>
        @endif
    </ul>
  </li>

  @if(Auth::user()->can(['consulta-viajes-netos']))
  <li class="dropdown">
      <a tabindex="0" href="#" class="dropdown-toggle" data-toggle="dropdown" data-submenu>
          Reportes<span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
          <li><a href="{{ route('reportes.viajes_netos.create') }}">Viajes Netos</a></li>
      </ul>
  </li>
  @endif


@else
  <li><a href="{{ route('proyectos') }}">Proyectos</a></li>
@endif
