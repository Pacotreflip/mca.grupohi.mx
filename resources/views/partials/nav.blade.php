<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" 
        data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">
          <figure class="nav-company pull-left">
            <img src="{{ asset('img/company-icon.png') }}" alt="Grupo Hermes Infraestructura"/>
          </figure>
          {{ trans('strings.app_name') }}
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      @if (Auth::check())
        <ul class="nav navbar-nav">
          @include ('partials.nav-app')
        </ul>
      @endif

      <ul class="nav navbar-nav navbar-right">
        @include ('partials.nav-user')
      </ul>
    </div>
  </div>
</nav>