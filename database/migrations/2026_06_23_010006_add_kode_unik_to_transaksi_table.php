<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'metode_bayar')) {
                $table->string('metode_bayar')->nullable();
            }
            if (!Schema::hasColumn('transaksi', 'kode_unik')) {
                $table->integer('kode_unik')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['metode_bayar', 'kode_unik']);
        });
    }
};