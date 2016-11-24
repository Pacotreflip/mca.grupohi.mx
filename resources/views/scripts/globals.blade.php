<!-- App Globals -->
<script>
    window.App = {
        // Token CSRF de Laravel
        csrfToken: '{{ csrf_token() }}',
        host: '{{ url("/") }}',

        // ID del Usuario Actual
        userId: {!! Auth::check() ? Auth::id() : 'null' !!},

        // Transformar los errores y asignarlos al formulario
        setErrorsOnForm: function (form, errors) {
            if (typeof errors === 'object') {
                form.errors = _.flatten(_.toArray(errors));
            } else {
                var ind1 = errors.indexOf('<span class="exception_message">');
                var cad1 = errors.substring(ind1);
                var ind2 = cad1.indexOf('</span>');
                var cad2 = cad1.substring(32,ind2);
                if(cad2 != ""){
                    form.errors.push( cad2);
                }else{
                    form.errors.push('Un error grave ocurrió. Por favor intente otra vez.');
                }
                
            }
        }
    }
</script>