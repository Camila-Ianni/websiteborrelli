<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('customer_dni');
                $table->string('customer_email');
                $table->string('customer_phone')->nullable();
                $table->string('order_number')->unique();
                $table->decimal('total', 10, 2);
                $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
                $table->string('payment_method')->default('transfer');
                $table->string('payment_id')->nullable();
                $table->string('payment_status')->nullable();
                $table->string('payment_preference_id')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->default('transfer')->after('status');
            }
            if (!Schema::hasColumn('orders', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_id');
            }
            if (!Schema::hasColumn('orders', 'payment_preference_id')) {
                $table->string('payment_preference_id')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_phone', 'payment_method', 'payment_id', 'payment_status', 'payment_preference_id']);
        });
    }
};
