<?php

namespace Modules\Role\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:roles,name,'.$this->route('role')->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ingrese el nombre de la marca',
            'name.unique' => 'El nombre ingresado ya se encuentra registrado',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->isAdmin() || auth()->user()->can('update-role')){
            return true;
        }
        return false;
    }
}
