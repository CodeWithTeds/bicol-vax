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
        if (!Schema::hasColumn('patients', 'source')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('source')->default('admin')->after('case_no');
            });
        }

        // Migrate existing WEB- prefixed card_no records to source='web'
        DB::table('patients')
            ->where('card_no', 'like', 'WEB-%')
            ->update(['source' => 'web']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
