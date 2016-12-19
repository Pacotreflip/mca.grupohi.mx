<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditEtapaRequest extends Request
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
            'Descripcion' => 'required|unique:sca.etapasproyectos,Descripcion,'.$this->route('etapas').',IdEtapaProyecto'
        ];
    }
    
    public function messages() {
        return [
            'Descripcion.unique' => 'Ya existe una Etapa con la siguientes Descripción: ' . $this->Descripcion,
        ];
    }
}
