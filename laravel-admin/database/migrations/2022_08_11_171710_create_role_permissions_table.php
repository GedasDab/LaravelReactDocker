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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissionss')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
        // Schema::table('role_permissions', function (Blueprint $table) {
        //     $table->dropForeign(['role_id']);
        //     $table->dropColumn('role_id');

        //     $table->dropForeign(['permission_id']);
        //     $table->dropColumn('permission_id');
        // });
    }
};
