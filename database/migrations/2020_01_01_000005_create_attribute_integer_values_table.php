<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeIntegerValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('marshmallow-attributes.tables.attribute_integer_values'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('content');
            $table->integer('attribute_id')->unsigned();
            $table->integer('entity_id')->unsigned();
            $table->string('entity_type');
            $table->timestamps();

            // Indexes
            $table->foreign('attribute_id')->references('id')->on(config('marshmallow-attributes.tables.attributes'))
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('marshmallow-attributes.tables.attribute_integer_values'));
    }
}
