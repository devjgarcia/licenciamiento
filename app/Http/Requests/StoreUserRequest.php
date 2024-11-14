<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\User;
use App\Models\Role;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'         => 'nullable|integer',
            'name'       => 'required|max:60|string',
            'rif'        => 'required|max:15',
            'direction'  => 'nullable|max:255',
            'phone'      => 'nullable|max:20',
            'email'      => 'required|email|unique:'. User::class .',email,'. $this->id.',id',
            'password'   => 'required_if:id,0',
            'role_id'    => 'required|exists:'. Role::class .',id',
            'file_photo' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'photo'      => 'nullable',
            'status'     => 'required_unless:id,0'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Campo :attribute es obligatorio.',
            'max'      => 'Campo :attribute solo permite :max',
            'min'      => 'Campo :attribute debe contener mínimo :min caracteres',
            'email'    => 'Campo :attribute debe ser un correo válido',
            'unique'   => 'Ya existe un registro con este :attribute',
            'exists'   => 'El :attribute seleccionado no existe en los registros',
        ];
    }

    public function attributes()
    {
        return [
            'name'       => 'Nombres y apellidos',
            'rif'        => 'Doc de Identidad',
            'direction'  => 'Dirección',
            'phone'      => 'Teléfono',
            'email'      => 'Correo electrónico',
            'password'   => 'Contraseña',
            'role_id'    => 'Rol',
            'file_photo' => 'Foto de Perfil',
        ];
    }
}
