Array.prototype.removeValue = function(name, value){
   var array = $.map(this, function(v,i){
      return v[name] === value ? null : v;
   });
   this.length = 0; //clear original array
   this.push.apply(this, array); //push all elements except the one we want to delete
}

Vue.component('viajes-manual', {
    data: function() {
        return {
            'num_viajes' : 1,
            'form': {
                'viajes' : [{
                    Codigo       : '',
                    FechaLlegada : App.timeStamp(1),
                    HoraLlegada  : App.timeStamp(2),
                    IdCamion     : '',
                    Cubicacion   : '',
                    IdOrigen     : '',
                    IdTiro       : '',
                    IdMaterial   : '',
                    Motivo       : '',
                    Tiros        : []
                }],
                'errors': []
            },
            'guardando': false,
            'cargando': false,
        }
    },
    
    directives: { 
        datepicker: {
            inserted: function(el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d'
                });
                $(el).val(App.timeStamp(1));
            }
        }
    },
 
    methods: {         
        
        setTiros: function(viaje) {
            viaje.Tiros = [];
            if(viaje.IdOrigen) {
                this.$http.get(App.host + '/origenes/' + viaje.IdOrigen + '/tiros').then((response) => {
                    viaje.Tiros = response.body;
                    viaje.IdTiro = '';
                }, (response) => {
                    App.setErrorsOnForm(this.form, response.body);
                });
            } else {
                viaje.IdTiro = '';
            }
        },

        setCamion: function(viaje) {
            viaje.Cubicacion = '';
            if(viaje.IdCamion) {
                this.$http.get(App.host + '/camiones/' + viaje.IdCamion).then((response) => {
                    viaje.Cubicacion = response.body.CubicacionParaPago;
                }, (error) => {
                    App.setErrorsOnForm(this.form, error.body);
                });
            } else {
                viaje.Cubicacion = '';
            }
        },

        addViaje: function(e) {
            e.preventDefault();

            this.form.viajes.push({
                Codigo       : '',
                FechaLlegada : App.timeStamp(1),
                HoraLlegada  : App.timeStamp(2),
                IdCamion     : '',
                Cubicacion   : '',
                IdOrigen     : '',
                IdTiro       : '',
                IdMaterial   : '',
                Motivo       : '',
                Tiros        : []
            });
        },
        
        removeViaje: function(index, e) {
            e.preventDefault();
            if(index != (this.form.viajes.length -1)) {
                var last_val = $('.FechaLlegada' + (this.form.viajes.length - 1)).val();
                for (var i = index; i < (this.form.viajes.length - 1); i++) {
                    var new_val = $('.FechaLlegada' + (index + 1)).val();
                    $('.FechaLlegada' + index).val(new_val);
                }
                this.form.viajes.splice(index, 1);
                $('.FechaLlegada' + (this.form.viajes.length - 1)).val(last_val);
            } else {
                this.form.viajes.splice(index, 1);
            }
        },
        
        registrar: function() {

            var _this = this;
            this.guardando = true;
            this.form.errors = [];
            var data = $('.form_carga_manual').serialize();
            var url = App.host + '/viajes_netos/manual';

            $.ajax({
                url : url,
                type : 'POST',
                data : data,
                success: function(response) {
                    swal({
                        type: 'success',
                        title: '',
                        text: response.message,
                        showConfirmButton: true
                    });
                    _this.guardando = false;
                },
                error: function(error) {
                    _this.guardando = false;
                    console.log(error);
                    App.setErrorsOnForm(_this.form, error.responseJSON);
                }
            });
        },
        
        confirmarRegistro: function (e) {
            e.preventDefault();

            swal({
                title: "¿Desea continuar con el registro?", 
                text: "¿Esta seguro de que la información es correcta?", 
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.registrar() );
        },
    }
});