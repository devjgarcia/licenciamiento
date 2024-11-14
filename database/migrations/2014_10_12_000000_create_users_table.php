<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_lice', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('rif', 15);
            $table->string('direction')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('frkcanal', 10)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('codigo', 20)->nullable();
            $table->integer('role_id')->nullable();
            $table->enum('status', ['Activo', 'Inactivo', 'Bloqueado']);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_lice');
    }
}
