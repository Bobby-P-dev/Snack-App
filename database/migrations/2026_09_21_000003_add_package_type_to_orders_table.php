<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('package_type', ['snack_box', 'satuan', 'campuran'])
                ->default('satuan')
                ->after('total_amount')
                ->index();
        });

        // Backfill existing orders based on their order_items
        DB::table('orders')->get()->each(function ($order) {
            $hasBox = DB::table('order_items')
                ->where('order_id', $order->id)
                ->where('type', 'kustom_box')
                ->exists();

            $hasSatuan = DB::table('order_items')
                ->where('order_id', $order->id)
                ->where('type', 'satuan')
                ->exists();

            $packageType = ($hasBox && $hasSatuan) 
                ? 'campuran' 
                : ($hasBox ? 'snack_box' : 'satuan');

            DB::table('orders')
                ->where('id', $order->id)
                ->update(['package_type' => $packageType]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['package_type']);
            $table->dropColumn('package_type');
        });
    }
};
