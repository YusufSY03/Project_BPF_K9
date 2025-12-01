<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->increments('faq_id');
            $table->string('question', 300);
            $table->longText('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq');
    }
};
