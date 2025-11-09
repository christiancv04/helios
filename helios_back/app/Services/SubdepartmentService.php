<?php

namespace App\Services;

use App\Http\Resources\SubdepartmentResource;
use App\Models\Department;
use App\Models\Subdepartment;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;

class SubdepartmentService
{
    use HasResponse;

    public function get($withPagination, $params)
    {
        try {
            $data = Subdepartment::filters()
                ->with([
                    'subdepartment'
                ]);

            $data = !empty($withPagination)
                ? $data->paginate($withPagination['perPage'], page: $withPagination['page'])
                : $data->get();

            $paginationTotal = null;
            if (!empty($withPagination)) $paginationTotal = $data->total();

            $data = SubdepartmentResource::collection($data);

            return $this->successPaginationResponse('Lectura exitosa.', $paginationTotal, $data);
        } catch (\Throwable $th) {
            return $this->errorResponse('Error al listar.', 500, $th->getMessage());
        }
    }

    public function create($params)
    {
        DB::beginTransaction();
        try {
            # Validar que los departamentos no sean iguales
            if ($params['iddepartment'] === $params['idsubdepartment']) {
                return $this->errorResponse('Un departamento no puede ser su propio subdepartamento.', 400);
            }

            # Validar que la relación no exista
            $exist = Subdepartment::where('iddepartment', $params['iddepartment'])
                ->where('idsubdepartment', $params['idsubdepartment'])
                ->active()
                ->exists();

            if ($exist) {
                return $this->errorResponse('Esta relación ya existe.', 400);
            }

            # Validar que el departamento no esté como subdepartamento de otro
            $exists = Subdepartment::where('idsubdepartment', $params['idsubdepartment'])
                ->where('iddepartment', '!=', $params['iddepartment'])
                ->active()
                ->exists();

            if ($exists) {
                return $this->errorResponse('El subdepartamento ya está asignado a otro departamento.', 400);
            }

            # Validar que el subdepartamento no tenga subdepartamentos asignados
            $exist = Subdepartment::where('iddepartment', $params['idsubdepartment'])
                ->active()
                ->exists();

            if ($exist) {
                return $this->errorResponse('El subdepartamento ya tiene subdepartamentos asignados.', 400);
            }

            $subdepartment = Subdepartment::create($params);

            # Actualizar el nivel del subdepartamento
            $department = Department::find($params['idsubdepartment']);
            if ($department && $department->level != 2) {
                $department->update(['level' => 2]);
            }

            DB::commit();
            return $this->successResponse('Creado con éxito.', $subdepartment);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al crear.', 500, $th->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $subdepartment = Subdepartment::findOrFail($id);
            $subdepartment->update(['status' => 2]);

            # Validar que el subdepartamento no tenga otras relaciones activas
            $exists = Subdepartment::where('iddepartment', $subdepartment->idsubdepartment)
                ->active()
                ->exists();

            if (!$exists) {
                # Actualizar el nivel del subdepartamento
                $department = Department::find($subdepartment->idsubdepartment);
                if ($department && $department->level != 1) {
                    $department->update(['level' => 1]);
                }
            }

            DB::commit();
            return $this->successResponse('Eliminado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al eliminar.', 500, $th->getMessage());
        }
    }
}
