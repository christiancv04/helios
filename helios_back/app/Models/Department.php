<?php

namespace App\Models;

use Faker\Provider\ar_EG\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Department extends Model
{
    protected $table = 'tbl_department';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'n_employees',
        'level',
        'ambassador',
        'status',
        'idcompany',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'status_label',
        'level_label'
    ];

    protected $casts = [
        'level' => 'integer',
        'status' => 'integer',
    ];

    # Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'idcompany');
    }

    public function upperDepartment(): HasOneThrough
    {
        return $this->hasOneThrough(
            Department::class,
            Subdepartment::class,
            'idsubdepartment',
            'id',
            'id',
            'iddepartment'
        );
    }
    public function subdepartments(): HasMany
    {
        return $this->hasMany(Subdepartment::class,  'iddepartment', 'id')
            ->active();
    }

    # Accessors
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0       => 'Inactivo',
            1       => 'Activo',
            2       => 'Eliminado'
        };
    }

    public function getLevelLabelAttribute()
    {
        return match ($this->level) {
            1       => 'Departamento',
            2       => 'Subdepartamento',
        };
    }

    # Query scopes
    public function scopeActiveForID($query, $id)
    {
        return $query->where('id', $id)
            ->active()
            ->company();
    }

    public function scopeActive($query)
    {
        return $query->where('tbl_department.status', 1);
    }

    public function scopeCompany($query, $idcompany = null)
    {
        return $query->when(
            request('idcompany'),
            fn($q) => $q->where('idcompany', request('idcompany'))
        );
    }

    # Filters
    public function scopeFilters($query)
    {
        $query->when(
            request('search'),
            fn($query) => $query->where('name', 'LIKE', '%' . request('search') . '%')
        );

        $query->when(
            request('column') && request('search'),
            fn($query) => $query->where(request('column'), 'LIKE', '%' . request('search') . '%')
        );

        $query->when(
            request('level'),
            fn($query) => $query->where('level', request('level'))
        );

        $query->company();

        $query->when(
            request('status'),
            fn($q) => $q->where('tbl_department.status', request('status')),
            fn($q) => $q->where('tbl_department.status', 1)
        );

        $query->when(
            request('field') && request('order'),
            fn($q) => $q->orderByField(request('field'), request('order')),
            fn($q) => $q->orderBy('id', 'desc')
        );
    }

    public function scopeOrderByField($query, string $field, string $order = 'asc')
    {
        switch ($field) {
            case 'upper_department_name':
                $query->leftJoin('tbl_subdepartment', 'tbl_subdepartment.idsubdepartment', '=', 'tbl_department.id')
                    ->leftJoin('tbl_department as upper_dept', 'upper_dept.id', '=', 'tbl_subdepartment.iddepartment')
                    ->orderBy('upper_dept.name', $order)
                    ->select('tbl_department.*');
                break;

            default:
                $query->orderBy("tbl_department.$field", $order)
                    ->select('tbl_department.*');
                break;
        }
    }
}
