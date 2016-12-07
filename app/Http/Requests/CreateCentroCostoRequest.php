<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCentroCostoRequest extends Request
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
            'Descripcion' => 'required|unique:sca.centroscosto,Descripcion',
            'Cuenta' => 'required'
        ];
    }
    
    public function messages() {
        return [
            'Descripcion.unique' => 'Ya existe un Centro de costo con la siguiente descripción: ' . $this->Descripcion,
        ];
    }
}
