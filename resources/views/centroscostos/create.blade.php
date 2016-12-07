<div class="modal fade" id="centrocosto_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Se Agregara el Centro de Costo a <strong>"{{isset($centro) ? $centro->Descripcion : 'Proyecto' }}"</strong></h4>
    </div> 
    <div class="modal-body" id="modal-body">
    {!! Form::open(['route' => 'centroscostos.store', 'id' => 'centrocosto_store_form']) !!} 
    <input type="hidden" name="IdPadre" value="{{isset($centro) ? $centro->IdCentroCosto : 0}}"/>
      <div class="row">    
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('Descripcion', 'Descripción:', ['class' => 'label-control']) !!}
            {!! Form::text('Descripcion', null, ['class' => 'form-control', 'placeholder' => 'Descripción...']) !!}
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('Cuenta', 'Cuenta:', ['class' => 'label-control']) !!}
            {!! Form::text('Cuenta', null, ['class' => 'form-control', 'placeholder' => 'Cuenta...']) !!}
          </div>
        </div>
      </div>
      <div class="row" id="errores"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="storeCentroCosto(this)">Guardar</button>
      <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
    </div>
    {!! Form::close() !!}
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>