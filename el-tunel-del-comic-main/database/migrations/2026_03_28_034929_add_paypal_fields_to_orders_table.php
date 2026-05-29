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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'paypal_order_id')) {
                $table->string('paypal_order_id')->nullable()->after('payment_preference_id');
            }
            if (!Schema::hasColumn('orders', 'paypal_capture_id')) {
                $table->string('paypal_capture_id')->nullable()->after('paypal_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['paypal_order_id', 'paypal_capture_id']);
        });
    }
};
