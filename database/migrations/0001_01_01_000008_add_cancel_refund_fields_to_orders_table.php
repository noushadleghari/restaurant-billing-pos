<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cancel = an OPEN (unpaid) order that never went through. No money involved.
            $table->text('cancel_reason')->nullable()->after('note');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');

            // Refund = a COMPLETED (paid) order where money is given back, in full or in part.
            // The original order->total is kept as the historical/gross amount; refund_amount
            // is netted off separately in reports rather than mutating the original total.
            $table->decimal('refund_amount', 10, 2)->nullable()->after('cancelled_at');
            $table->text('refund_reason')->nullable()->after('refund_amount');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancelled_at', 'refund_amount', 'refund_reason', 'refunded_at']);
        });
    }
};
