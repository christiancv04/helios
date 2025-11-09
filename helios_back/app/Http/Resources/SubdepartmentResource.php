<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubdepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $department = $this->relationLoaded('department') ? $this->whenLoaded('department') : null;
        $subdepartment = $this->relationLoaded('subdepartment') ? $this->whenLoaded('subdepartment') : null;

        return [
            'id' => $this->id,
            'iddepartment' => $this->iddepartment,
            $this->mergeWhen($department, fn() => [
                'department_name' => $department->name,
                'department_n_employees' => $department->n_employees,
                'department_level' => $department->level,
                'department_ambassador' => $department->ambassador,
            ]),
            'status' => $this->status,
            'status_label' => $this->status_label,
            'idsubdepartment' => $this->idsubdepartment,
            $this->mergeWhen($subdepartment, fn() => [
                'subdepartment_name' => $subdepartment->name,
                'subdepartment_n_employees' => $subdepartment->n_employees,
                'subdepartment_level' => $subdepartment->level,
                'subdepartment_ambassador' => $subdepartment->ambassador,
                'subdepartment_n_subdepartment' => count($subdepartment->subdepartments)
            ]),
        ];
    }
}
