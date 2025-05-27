<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservation_payment', function (Blueprint $table) {
            $table->integer('amount')->after('token'); // o puedes usar decimal si lo prefieres
        });
    }

    public function down(): void
    {
        Schema::table('reservation_payment', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};
