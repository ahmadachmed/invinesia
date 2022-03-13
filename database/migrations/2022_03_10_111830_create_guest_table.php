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
        Schema::create('guest', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('guest_name');
            $table->string('guest_address');
            $table->char('stage')->comment('0:normal, 1:vip')->default(0);
            $table->char('message_status')->comment('0:notSend, 1:send')->default(0);
            $table->string('qr_code');
            $table->string('keterangan')->nullable();
            $table->char('kehadiran')->comment('0:tidak_hadir, 1:hadir, 2:akan_hadir')->default(false);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('event');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest');
    }
};
