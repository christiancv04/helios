<?php

namespace App\Services;

use App\Http\Resources\DepartmentResource;
use App\Http\Resources\SubdepartmentResource;
use App\Models\Department;
use App\Models\Subdepartment;
use App\Traits\HasResponse;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    use HasResponse;

    public function get($withPagination, $params)
    {
        try {
            $data = Department::filters()
                ->with([
                    'upperDepartment',
                    'subdepartments'
                ]);

            $data = !empty($withPagination)
                ? $data->paginate($withPagination['perPage'], page: $withPagination['page'])
                : $data->get();

            $paginationTotal = null;
            if (!empty($withPagination)) $paginationTotal = $data->total();

            $data = DepartmentResource::collection($data);

            return $this->successPaginationResponse('Lectura exitosa.', $paginationTotal, $data);
        } catch (\Throwable $th) {
            return $this->errorResponse('Error al listar.', 500, $th->getMessage());
        }
    }

    public function create($params)
    {
        DB::beginTransaction();
        try {
            # Validar que el nombre sea único por empresa
            $exist = Department::where('name', $params['name'])
                ->company()
                ->active()
                ->exists();
            if ($exist) {
                return $this->errorResponse('El nombre del departamento ya está registrado.', 400);
            }

            $params['n_employees'] = rand(1, 100);
            $department = Department::create($params);

            DB::commit();
            return $this->successResponse('Creado con éxito.', $department);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al crear.', 500, $th->getMessage());
        }
    }

    public function update($id, $params)
    {
        DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            $department->update($params);

            DB::commit();
            return $this->successResponse('Actualizado con éxito.', $department);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar.', 500, $th->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            $department->update(['status' => 2]);

            DB::commit();
            return $this->successResponse('Eliminado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al eliminar.', 500, $th->getMessage());
        }
    }
}
