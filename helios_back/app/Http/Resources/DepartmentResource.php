<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subdepartments = $this->relationLoaded('subdepartments') ? $this->whenLoaded('subdepartments') : null;
        $upperDepartment = $this->relationLoaded('upperDepartment') ? $this->whenLoaded('upperDepartment') : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'n_employees' => $this->n_employees,
            'level' => $this->level,
            'ambassador' => $this->ambassador,
            'status' => $this->status,
            'idcompany' => $this->idcompany,

            'status_label' => $this->status_label,
            'level_label' => $this->level_label,

            $this->mergeWhen($subdepartments, fn() => [
                'subdepartments' => SubdepartmentResource::collection($subdepartments),
                'n_subdepartments' => count($subdepartments)
            ]),

            $this->mergeWhen($upperDepartment, fn() => [
                'upper_department_name' => $upperDepartment->name,
                'upper_department_n_employees' => $upperDepartment->n_employees,
                'upper_department_level' => $upperDepartment->level,
                'upper_department_ambassador' => $upperDepartment->ambassador,
            ]),
        ];
    }
}
