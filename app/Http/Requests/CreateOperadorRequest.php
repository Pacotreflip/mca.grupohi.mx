<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOperadorRequest extends Request
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
            'Nombre' => 'required|string',
            'Direccion' => 'required|string',
            'NoLicencia' => 'required|string',
            'VigenciaLicencia'  => 'required|date_format:"Y-m-d"'
        ];
    }
}
