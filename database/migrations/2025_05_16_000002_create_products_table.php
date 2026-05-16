<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->decimal('price', 12, 2);
            $table->date('date_available');
            $table->timestamps();

            $table->index('date_available');
            $table->index('price');
            $table->index(['title', 'date_available']);
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_search_fulltext (title, description)');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_search_fulltext');
            });
        }

        Schema::dropIfExists('products');
    }
};
