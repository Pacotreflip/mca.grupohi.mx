<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h1>Inicie Sesión</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <!-- Usuario Form Input -->
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input class="form-control" required="required" autofocus="autofocus" name="usuario" type="text" id="usuario">
            </div>

            <!-- Password Form Input -->
            <div class="form-group">
                <label for="clave">Clave:</label>
                <input class="form-control" required="required" name="clave" type="password" id="clave">
            </div>

            <div class="checkbox">
                <label>
                    <input name="remember_me" type="checkbox" value="1"> Recordar mi sesión en este equipo
                </label>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Iniciar sesión">
            </div>
        </form>
    </div>
</div>