<div class="text-right">
  {!! Form::model(Request::only('buscar'), ['method' => 'GET', 'class' => 'form-inline']) !!}
    <div class="input-group">
      {!! Form::text('buscar', null, ['class' => 'form-control input-sm', 'placeholder' => 'buscar...']) !!}
      <span class="input-group-btn">
        <button class="btn btn-sm btn-primary" type="submit">Buscar</button>
      </span>
    </div>
  {!! Form::close() !!}
  <br>
</div>