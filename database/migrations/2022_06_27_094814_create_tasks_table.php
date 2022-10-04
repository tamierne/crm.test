<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->date('deadline');
            $table->unsignedBigInteger('user_id')
                ->foreignId('user_id')
                ->constrained();
            $table->unsignedBigInteger('project_id')
                ->foreignId('project_id')
                ->constrained();
            $table->tinyInteger('status_id')
                ->foreignId('status_id')
                ->constrained()
                ->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
