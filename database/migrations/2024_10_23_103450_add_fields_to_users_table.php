<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->default('employee');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'role', 'address', 'phone_number', 'birth_date', 'gender']);
        });
    }
}
