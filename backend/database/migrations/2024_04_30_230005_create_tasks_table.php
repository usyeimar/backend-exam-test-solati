<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->comment('Identificador numérico de la tarea');
            $table->uuid()->unique()->comment('Identificador único de la tarea');
            $table->string('title')->comment('Título de la tarea');
            $table->text('description')->comment('Descripción de la tarea')
                ->nullable();
            $table->boolean('completed')->default(false)
                ->comment('Indica si la tarea está completada');
            $table->dateTime('completed_at')->comment('Fecha en la que se completó la tarea')
                ->nullable();
            $table->dateTime('due_at')->comment('Fecha de vencimiento de la tarea')
                ->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
