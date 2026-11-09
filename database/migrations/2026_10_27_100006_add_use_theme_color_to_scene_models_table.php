<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scene_models', function (Blueprint $table) {
            $table->boolean('use_theme_color')->default(true)->after('model_file');
        });

        // The dark server rack keeps its own body color; only its LEDs glow in the brand color.
        DB::table('scene_models')->where('shape', 'server')->update(['use_theme_color' => false]);
    }

    public function down(): void
    {
        Schema::table('scene_models', function (Blueprint $table) {
            $table->dropColumn('use_theme_color');
        });
    }
};
