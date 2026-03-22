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
    // DESACTIVAMOS las comprobaciones de llaves foráneas temporalmente
    Schema::disableForeignKeyConstraints();
    // 1. Añadimos columnas polimórficas
    Schema::table('likes', function (Blueprint $table) {
        $table->unsignedBigInteger('likeable_id')->after('user_id')->nullable();
        $table->string('likeable_type')->after('likeable_id')->nullable();
    });
    // 2. Transpasamos los datos existentes
    \Illuminate\Support\Facades\DB::statement(
        "UPDATE likes SET likeable_id = post_id, likeable_type = 'App\\\\Models\\\\Post' WHERE post_id IS NOT NULL"
    );
    // 3. Reestructuración física de la tabla
    Schema::table('likes', function (Blueprint $table) {
        $table->dropUnique(['user_id', 'post_id']);
        $table->dropForeign(['post_id']);
        $table->dropColumn('post_id');
        
        // Creamos los nuevos índices polimórficos
        $table->index(['likeable_id', 'likeable_type']);
        $table->unique(['user_id', 'likeable_id', 'likeable_type'], 'likes_polymorphic_unique');
    });
    // REACTIVAMOS las comprobaciones
    Schema::enableForeignKeyConstraints();
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
