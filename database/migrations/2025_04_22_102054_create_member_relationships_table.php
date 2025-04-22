<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_member_id')->constrained('members')->onDelete('cascade');
            $table->enum('relationship_type', ['spouse', 'adopted', 'other'])->default('other');
            $table->timestamps();

            // Ensure unique relationship pairs
            $table->unique(['member_id', 'related_member_id', 'relationship_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_relationships');
    }
};
