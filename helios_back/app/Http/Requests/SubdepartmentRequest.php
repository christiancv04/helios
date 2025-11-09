<?php

namespace App\Http\Requests;

use App\Traits\HasResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubdepartmentRequest extends FormRequest
{
    use HasResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iddepartment' =>  ['required', 'exists:tbl_department,id'],
            'idsubdepartment' =>  ['required', 'exists:tbl_department,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'iddepartment.required' => 'El campo departamento es obligatorio.',
            'iddepartment.exists' => 'El departamento seleccionado no existe en el sistema.',

            'idsubdepartment.required' => 'El campo subdepartamento es obligatorio.',
            'idsubdepartment.exists' => 'El subdepartamento seleccionado no existe en el sistema.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $message = $errors[0] ?? 'Error de validación.';
        throw new HttpResponseException(
            $this->errorResponse($message, 400)
        );
    }
}
