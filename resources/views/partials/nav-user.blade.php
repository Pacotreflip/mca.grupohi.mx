@if(Auth::check())
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-user fa-fw"></i> {{ Auth::user()->present()->nombreCompleto }} <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" role="menu">
      <li><a href="/auth/logout"><i class="fa fa-sign-out fa-fw"></i>{{ trans('strings.logout') }}</a></li>
    </ul>
  </li>
@else
  <li><a href="/auth/login"><i class="fa fa-sign-in fa-fw"></i>{{ trans('strings.login') }}</a></li>
@endif