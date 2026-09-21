<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop old check constraint first
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");

            // Update existing data to match new statuses
            DB::statement("UPDATE orders SET status = 'diterima' WHERE status = 'confirmed'");
            DB::statement("UPDATE orders SET status = 'selesai' WHERE status = 'completed'");

            // Add new check constraint
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending','diterima','diproses','dikemas','dikirim','selesai','batal'))");

            // Set default
            DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
            DB::statement("UPDATE orders SET status = 'confirmed' WHERE status IN ('diterima','diproses','dikemas','dikirim')");
            DB::statement("UPDATE orders SET status = 'completed' WHERE status = 'selesai'");
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending','confirmed','completed'))");
            DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }
};
