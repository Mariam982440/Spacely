<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id')->nullable()->after('transaction_id');
            $table->string('stripe_charge_id')->nullable()->after('stripe_payment_intent_id');
            $table->string('currency', 3)->default('mad')->after('amount');
            $table->string('payment_method')->nullable()->after('currency');
            $table->timestamp('paid_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_charge_id',
                'currency',
                'payment_method',
                'paid_at',
            ]);
        });
    }
};
