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
        Schema::create('tbl_department', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->unsignedInteger('n_employees')->default(0);
            $table->unsignedBigInteger('level')->default(1)->comment('1: Departamento principal, 2: Subdepartamento');
            $table->string('ambassador')->nullable();
            $table->char('status', 1)->default(1)->comment('0: Inactivo, 1: Activo, 2: Eliminado');
            $table->unsignedBigInteger('idcompany');

            $table->foreign('idcompany')->references('id')->on('tbl_company');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_department');
    }
};
