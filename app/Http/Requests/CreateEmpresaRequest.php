<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateEmpresaRequest extends Request
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
            'razonSocial' => 'required|unique:sca.empresas,razonSocial',
            'RFC' => 'required|unique:sca.empresas,RFC',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'razonSocial.unique'    => 'Ya existe una empresa con la siguiente Razón Social: ' . $this->razonSocial,
            'RFC.unique'            => 'Ya existe una empresa con el siguiente RFC: ' . $this->RFC,
        ];

        return $messages;
    }
}