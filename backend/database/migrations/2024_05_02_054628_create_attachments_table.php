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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id()->comment('Identificador único del registro');
            $table->uuid()->unique()->comment('Identificador único del archivo');
            $table->string('display_name')->comment('Nombre del archivo');
            $table->string('hash_name')->comment('Nombre del archivo encriptado');
            $table->string('path')->nullable()->comment('Ruta del archivo');
            $table->longText('base_64')->nullable()->comment('Archivo en base64');
            $table->binary('binary')->nullable()->comment('Archivo en binario');
            $table->string('mime_type')->comment('Tipo MIME del archivo');
            $table->unsignedBigInteger('size')->comment('Tamaño del archivo en bytes');
            $table->foreignId('task_id')
                ->nullable()
                ->constrained('tasks')
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
