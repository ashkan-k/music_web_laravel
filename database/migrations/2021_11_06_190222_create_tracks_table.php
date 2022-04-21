<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_vip')->default(false);
            $table->text('cover')->nullable();
            $table->text('lyrics')->nullable();
            $table->date('published_date')->nullable();

            $table->text('file')->nullable();
            $table->boolean('is_file_link')->default(true)->nullable();

            $table->foreignId('singer_id')->nullable()->constrained('singers')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('album_id')->nullable()->constrained('albums')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('genre_id')->nullable()->constrained('genres')->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('tracks');
    }
}
