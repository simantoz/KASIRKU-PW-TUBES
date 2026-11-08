<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->unique(); // e.g. PO-20261102-0001
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('supplier_name');
            $table->string('invoice_ref')->nullable(); // No faktur/surat jalan supplier
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status')->default('received'); // received, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_code');
            $table->string('product_name');
            $table->unsignedBigInteger('cost_price')->default(0);
            $table->integer('qty')->default(1);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
        Schema::dropIfExists('purchases');
    }
};
