<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subdepartment extends Model
{
    protected $table = 'tbl_subdepartment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'iddepartment',
        'idsubdepartment',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'level_label'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    # Relations
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'iddepartment');
    }

    public function subdepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'idsubdepartment');
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

    # Query scopes
    public function scopeActiveForID($query, $id)
    {
        return $query->where('id', $id)
            ->where('status', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    # Filters
    public function scopeFilters($query)
    {
        $query->when(
            request('iddepartment'),
            fn($query) => $query->where('iddepartment', request('iddepartment'))
        );

        $query->when(
            request('status'),
            fn($q) => $q->where('status', request('status')),
            fn($q) => $q->where('status', 1)
        );

        $query->when(
            request('field') && request('order'),
            fn($q) => $q->orderBy(request('field'), request('order')),
            fn($q) => $q->orderBy('id', 'desc')
        );
    }
}
