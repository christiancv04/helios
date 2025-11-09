<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Services\DepartmentService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use HasResponse;

    /** @var DepartmentService */
    private $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        $withPagination = $this->validatePagination($request->only('perPage', 'page'));

        return $this->departmentService->get($withPagination, $request->all());
    }

    public function store(DepartmentRequest $request)
    {
        return $this->departmentService->create($request->validated());
    }

    public function update($id, DepartmentRequest $request)
    {
        return $this->departmentService->update($id, $request->validated());
    }

    public function delete($id)
    {
        return $this->departmentService->delete($id);
    }
}
