<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateViajeNetoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        
        if($this->path() == 'viajes/netos/manual') {
            foreach($this->get('viajes', []) as $key => $viaje) {
                $rules['viajes.'.$key.'.FechaLlegada'] = 'required|date_format:"Y-m-d"';
                $rules['viajes.'.$key.'.HoraLlegada'] = 'required|date_format:H:i';
                $rules['viajes.'.$key.'.IdCamion'] = 'required|exists:sca.camiones,IdCamion';
                $rules['viajes.'.$key.'.IdOrigen'] = 'required|exists:sca.origenes,IdOrigen';
                $rules['viajes.'.$key.'.IdTiro'] = 'required|exists:sca.tiros,IdTiro';
                $rules['viajes.'.$key.'.IdMaterial'] = 'required|exists:sca.materiales,IdMaterial';
                $rules['viajes.'.$key.'.Observaciones'] = 'string';

            }
        } else if($this->path() == 'viajes/netos/completa') {
            foreach($this->get('viajes', []) as $key => $viaje) {
                $rules['viajes.'.$key.'.NumViajes'] = 'required|numeric|min:1';
                $rules['viajes.'.$key.'.FechaLlegada'] = 'required|date_format:"Y-m-d"';
                $rules['viajes.'.$key.'.IdCamion'] = 'required|exists:sca.camiones,IdCamion';
                $rules['viajes.'.$key.'.IdOrigen'] = 'required|exists:sca.origenes,IdOrigen';
                $rules['viajes.'.$key.'.IdRuta'] = 'required|exists:sca.rutas,IdRuta';
                $rules['viajes.'.$key.'.IdTiro'] = 'required|exists:sca.tiros,IdTiro';
                $rules['viajes.'.$key.'.IdMaterial'] = 'required|exists:sca.materiales,IdMaterial';
                $rules['viajes.'.$key.'.PrimerKm'] = 'required|numeric';
                $rules['viajes.'.$key.'.KmSub'] = 'required|numeric';
                $rules['viajes.'.$key.'.KmAd'] = 'required|numeric';
                $rules['viajes.'.$key.'.Turno'] = 'required|string';
                $rules['viajes.'.$key.'.Observaciones'] = 'string';
            }
        }
        
        return $rules;
    }
    
    public function messages() {
        $messages = [];
        if($this->path() == 'viajes/netos/manual') {
            foreach($this->get('viajes', []) as $key => $viaje) {
                $messages['viajes.'.$key.'.FechaLlegada.required'] = '(Viaje: '.$viaje['Id'].') El campo Fecha es obligatorio';
                $messages['viajes.'.$key.'.FechaLlegada.date_format'] = '(Viaje: '.$viaje['Id'].') La Fecha no corresponde al formato :format.';

                $messages['viajes.'.$key.'.HoraLlegada.required'] = '(Viaje: '.$viaje['Id'].') El campo Hora es obligatorio';
                $messages['viajes.'.$key.'.HoraLlegada.required'] = '(Viaje: '.$viaje['Id'].') La Hora no corresponde al formato :format.';

                $messages['viajes.'.$key.'.IdCamion.required'] = '(Viaje: '.$viaje['Id'].') El campo Camión es obligatorio';
                $messages['viajes.'.$key.'.IdCamion.exists'] = '(Viaje: '.$viaje['Id'].') El Camión es inválido.';

                $messages['viajes.'.$key.'.IdOrigen.required'] = '(Viaje: '.$viaje['Id'].') El campo Origen es obligatorio';
                $messages['viajes.'.$key.'.IdOrigen.exists'] = '(Viaje: '.$viaje['Id'].') El Origen es inválido.';

                $messages['viajes.'.$key.'.IdTiro.required'] = '(Viaje: '.$viaje['Id'].') El campo Tiro es obligatorio';
                $messages['viajes.'.$key.'.IdTiro.exists'] = '(Viaje: '.$viaje['Id'].') El Tiro es inválido.';

                $messages['viajes.'.$key.'.IdMaterial.required'] = '(Viaje: '.$viaje['Id'].') El campo Material es obligatorio';
                $messages['viajes.'.$key.'.IdMaterial.exists'] = '(Viaje: '.$viaje['Id'].') El Material es inválido.';

                $messages['viajes.'.$key.'.Observaciones.string'] = '(Viaje: '.$viaje['Id'].') El campo Observaciones debe ser una cadena de caracteres.';

            }
        } else if($this->path() == 'viajes/netos/completa') {
            foreach($this->get('viajes', []) as $key => $viaje) {
                $messages['viajes.'.$key.'.NumViajes.required'] = '(Viaje: '.$viaje['Id'].') El campo # es obligatorio';
                $messages['viajes.'.$key.'.NumViajes.numeric'] = '(Viaje: '.$viaje['Id'].') El campo # debe ser numérico';
                $messages['viajes.'.$key.'.NumViajes.numeric'] = '(Viaje: '.$viaje['Id'].') El campo # debe ser al menos 1';

                $messages['viajes.'.$key.'.FechaLlegada.required'] = '(Viaje: '.$viaje['Id'].') El campo Fecha es obligatorio';
                $messages['viajes.'.$key.'.FechaLlegada.date_format'] = '(Viaje: '.$viaje['Id'].') La Fecha no corresponde al formato :format.';

                $messages['viajes.'.$key.'.HoraLlegada.required'] = '(Viaje: '.$viaje['Id'].') El campo Hora es obligatorio';
                $messages['viajes.'.$key.'.HoraLlegada.required'] = '(Viaje: '.$viaje['Id'].') La Hora no corresponde al formato :format.';

                $messages['viajes.'.$key.'.IdCamion.required'] = '(Viaje: '.$viaje['Id'].') El campo Camión es obligatorio';
                $messages['viajes.'.$key.'.IdCamion.exists'] = '(Viaje: '.$viaje['Id'].') El Camión es inválido.';

                $messages['viajes.'.$key.'.IdOrigen.required'] = '(Viaje: '.$viaje['Id'].') El campo Origen es obligatorio';
                $messages['viajes.'.$key.'.IdOrigen.exists'] = '(Viaje: '.$viaje['Id'].') El Origen es inválido.';

                $messages['viajes.'.$key.'.IdTiro.required'] = '(Viaje: '.$viaje['Id'].') El campo Tiro es obligatorio';
                $messages['viajes.'.$key.'.IdTiro.exists'] = '(Viaje: '.$viaje['Id'].') El Tiro es inválido.';

                $messages['viajes.'.$key.'.IdRuta.required'] = '(Viaje: '.$viaje['Id'].') El campo Ruta es obligatorio';
                $messages['viajes.'.$key.'.IdRuta.exists'] = '(Viaje: '.$viaje['Id'].') La Ruta es inválido.';

                $messages['viajes.'.$key.'.IdMaterial.required'] = '(Viaje: '.$viaje['Id'].') El campo Material es obligatorio';
                $messages['viajes.'.$key.'.IdMaterial.exists'] = '(Viaje: '.$viaje['Id'].') El Material es inválido.';

                $messages['viajes.'.$key.'.PrimerKm.required'] = '(Viaje: '.$viaje['Id'].') El campo # es obligatorio';
                $messages['viajes.'.$key.'.PrimerKm.numeric'] = '(Viaje: '.$viaje['Id'].') El campo # debe ser numérico';

                $messages['viajes.'.$key.'.KmSub.required'] = '(Viaje: '.$viaje['Id'].') El campo 1er. Km es obligatorio';
                $messages['viajes.'.$key.'.KmSub.numeric'] = '(Viaje: '.$viaje['Id'].') El campo 1er. Km debe ser numérico';

                $messages['viajes.'.$key.'.KmAd.required'] = '(Viaje: '.$viaje['Id'].') El campo Km. Subs. es obligatorio';
                $messages['viajes.'.$key.'.KmAd.numeric'] = '(Viaje: '.$viaje['Id'].') El campo Km. Subs. debe ser numérico';

                $messages['viajes.'.$key.'.Turno.required'] = '(Viaje: '.$viaje['Id'].') El campo Km. Adic.es obligatorio';
                $messages['viajes.'.$key.'.Turno.string'] = '(Viaje: '.$viaje['Id'].') El campo Km. Adic. debe ser numérico';

                $messages['viajes.'.$key.'.Observaciones.string'] = '(Viaje: '.$viaje['Id'].') El campo Observaciones debe ser una cadena de caracteres.';
            }
        }
        return $messages;
    }
}
