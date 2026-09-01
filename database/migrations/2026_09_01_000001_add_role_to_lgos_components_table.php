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
        if (Schema::hasTable('lgos_components') && !Schema::hasColumn('lgos_components', 'role')) {
            Schema::table('lgos_components', function (Blueprint $table) {
                $table->string('role')->nullable()->after('component_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lgos_components') && Schema::hasColumn('lgos_components', 'role')) {
            Schema::table('lgos_components', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
