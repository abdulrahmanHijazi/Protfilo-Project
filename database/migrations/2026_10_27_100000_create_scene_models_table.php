<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scene_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('placement')->default('hero');
            $table->string('shape')->default('torus_knot');
            $table->string('model_file')->nullable();
            $table->string('color')->default('#C9A227');
            $table->string('emissive_color')->default('#000000');
            $table->float('emissive_intensity')->default(0);
            $table->float('metalness')->default(0.8);
            $table->float('roughness')->default(0.25);
            $table->float('opacity')->default(1);
            $table->boolean('wireframe')->default(false);
            $table->float('scale')->default(1);
            $table->float('position_x')->default(0);
            $table->float('position_y')->default(0);
            $table->float('position_z')->default(0);
            $table->float('rotation_speed')->default(0.4);
            $table->float('float_intensity')->default(0.3);
            $table->float('mouse_follow')->default(0.5);
            $table->boolean('draggable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scene_models');
    }
};
