<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_subdepartment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddepartment')->comment('Departamento principal');
            $table->unsignedBigInteger('idsubdepartment')->comment('Subdepartamento');
            $table->char('status', 1)->default(1)->comment('0: Inactivo, 1: Activo, 2: Eliminado');

            $table->foreign('iddepartment')->references('id')->on('tbl_department');
            $table->foreign('idsubdepartment')->references('id')->on('tbl_department');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_subdepartment');
    }
};
