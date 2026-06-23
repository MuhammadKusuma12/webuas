<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('selesai', 'dibatalkan', 'menunggu_pembayaran', 'menunggu_konfirmasi') NOT NULL DEFAULT 'menunggu_pembayaran'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('selesai', 'dibatalkan') NOT NULL DEFAULT 'selesai'");
    }
};