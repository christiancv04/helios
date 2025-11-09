<?php

namespace App\Http\Requests;

use App\Traits\HasResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DepartmentRequest extends FormRequest
{
    use HasResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:45'],
            'level' => ['nullable', 'integer', 'in:1,2'],
            'ambassador' => ['nullable', 'string'],
            'idcompany' => ['required', 'exists:tbl_company,id'],
        ];

        if ($this->isMethod('PUT')) {
            foreach ($rules as $field => &$rule) {
                if ($field !== 'idcompany') {
                    $rule = array_diff($rule, ['required']);
                    $rule[] = 'nullable';
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del departamento es obligatorio.',
            'name.string' => 'El nombre del departamento debe ser un texto válido.',
            'name.max' => 'El nombre del departamento no debe exceder los 45 caracteres.',

            'level.integer' => 'El nivel debe ser un número entero.',
            'level.in' => 'El nivel seleccionado no es válido. Solo se permite 1 (Departamento) o 2 (Subdepartamento).',

            'ambassador.string' => 'El nombre del embajador debe ser un texto válido.',

            'idcompany.required' => 'Debe seleccionar una empresa asociada.',
            'idcompany.exists' => 'La empresa seleccionada no existe en el sistema.',
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
