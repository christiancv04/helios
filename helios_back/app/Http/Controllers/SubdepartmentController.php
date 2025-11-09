<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubdepartmentRequest;
use App\Services\SubdepartmentService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class SubdepartmentController extends Controller
{
    use HasResponse;

    /** @var SubdepartmentService */
    private $subdepartmentService;

    public function __construct(SubdepartmentService $subdepartmentService)
    {
        $this->subdepartmentService = $subdepartmentService;
    }

    public function index(Request $request)
    {
        $withPagination = $this->validatePagination($request->only('perPage', 'page'));

        return $this->subdepartmentService->get($withPagination, $request->all());
    }

    public function store(SubdepartmentRequest $request)
    {
        return $this->subdepartmentService->create($request->validated());
    }

    public function delete($id)
    {
        return $this->subdepartmentService->delete($id);
    }
}
