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
       if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('address');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid','paid'])->default('unpaid')->after('status');
            }

            if (!Schema::hasColumn('orders', 'confirmed_by_user')) {
                $table->boolean('confirmed_by_user')->default(false)->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {  
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['address','payment_method','payment_status','confirmed_by_user']);
        });
    }
};
