<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trans('strings.app_name') }}</title>
    <link rel="stylesheet" href="{{ asset(elixir('css/app.css')) }}">
    @include('scripts.globals')

@yield('styles')
  </head>
  <body>
    @include('partials.nav')

    <div class="container" style="width: 98%;">
        @include('flash::message')
        @yield('content')
    </div>
    <br>
    <script src="{{ asset(elixir("js/all.js")) }}"></script>
    <script>
      $(function () {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });    
      });
      $('[data-submenu]').submenupicker();
    </script>
    @yield('scripts')
  </body>
</html>