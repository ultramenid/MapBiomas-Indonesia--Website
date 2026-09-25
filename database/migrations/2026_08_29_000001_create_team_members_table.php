<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20)->index(); // technical | scientific
            $table->string('team_group', 20)->nullable(); // koordinator | inti | regio (technical only)
            $table->string('region')->nullable(); // e.g. Sumatera, for regio members
            $table->string('name');
            $table->string('position_id')->nullable();
            $table->string('position_en')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('email')->nullable();
            $table->longText('bio_id')->nullable();
            $table->longText('bio_en')->nullable();
            $table->json('collections')->nullable(); // {"landy":[1,2],"fire":[1],"alerta":[1]}
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
