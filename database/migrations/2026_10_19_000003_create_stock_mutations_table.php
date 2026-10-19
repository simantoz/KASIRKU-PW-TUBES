<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type')->default('in'); // 'in' (pembelian/masuk) or 'out' (penyesuaian)
            $table->string('supplier_name')->nullable();
            $table->integer('qty');
            $table->unsignedBigInteger('cost_price')->default(0);
            $table->unsignedBigInteger('total_cost')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
