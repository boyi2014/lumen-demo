<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('username')->unique()->index();
            $table->string('password');
            $table->string('nick_name', 20)->index();
            $table->string('email', 50);
            $table->tinyInteger('sex', false, true);
            $table->smallInteger('age', false, true);
            $table->string('avatar', 255)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Add the constraint
            //DB::statement("ALTER TABLE users ADD CONSTRAINT chk_age CHECK (age < 200);");
            //DB::statement("ALTER TABLE users ADD CONSTRAINT chk_age CHECK (sex in (0, 1));");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
