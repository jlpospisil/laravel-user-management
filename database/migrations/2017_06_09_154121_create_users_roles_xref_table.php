<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRolesXrefTable extends Migration
{
    public function up()
    {
        Schema::create('users_roles_xref', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
        });

        Schema::table('users_roles_xref', function ($table) {
            $table->foreign('user_id','user_id_foreign')->references('id')->on('users');
            $table->foreign('role_id','role_id_foreign')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_roles_xref');
    }
}
