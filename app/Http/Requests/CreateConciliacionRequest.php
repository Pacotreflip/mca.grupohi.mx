<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateConciliacionRequest extends Request
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
            'idsindicato' => 'required|exists:sca.sindicatos,IdSindicato',
            'idempresa'   => 'required|exists:sca.empresas,IdEmpresa',
            'fecha'       => 'required|date_format:"Y-m-d"',
            'folio'       => 'required|string'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'idsindicato.exists'   => 'No existe un :attribute con el identificador ingresado',
            'idempresa.exists'   => 'No existe un :attribute con el identificador ingresado',
        ];

        return $messages;
    }
}