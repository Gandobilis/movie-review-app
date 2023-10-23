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
        Schema::table('movies', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id')->nullable();

            $table->foreign('book_id')
                ->references('id')
                ->on(config('database.connections.mysql2.database') . '.books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign('movies_book_id_foreign');
            $table->dropColumn('book_id');
        });
    }
};
