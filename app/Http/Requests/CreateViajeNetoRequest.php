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
        return [
            'FechaLlegada'  => 'required|date_format:"Y-m-d"',
            'HoraLlegada'   => 'required|date_format:H:i',
            'IdCamion'      => 'required|exists:sca.camiones,IdCamion',
            'IdOrigen'      => 'required|exists:sca.origenes,IdOrigen',
            'IdTiro'        => 'required|exists:sca.tiros,IdTiro',
            'IdMaterial'    => 'required|exists:sca.materiales,IdMaterial',
            'Observaciones' => 'string'
        ];
    }
}
