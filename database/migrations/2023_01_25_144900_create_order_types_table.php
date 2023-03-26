<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
        });

        \Illuminate\Support\Facades\DB::table('order_types')->insert([
            [ 'id' => 1, 'name' => 'Интернет заказ', 'short_name' => 'и'],
            [ 'id' => 2, 'name' => 'Золотой номер', 'short_name' => 'з'],
            [ 'id' => 3, 'name' => 'Мессенджер', 'short_name' => 'м'],
            [ 'id' => 4, 'name' => 'Партнерский исходящий', 'short_name' => 'пи'],
            [ 'id' => 5, 'name' => 'Партнерский входящий', 'short_name' => 'пв'],
            [ 'id' => 6, 'name' => 'Корпоративный заказ', 'short_name' => 'к'],
            [ 'id' => 7, 'name' => 'Диспетчера (Интернет)', 'short_name' => 'ди'],
            [ 'id' => 8, 'name' => 'Деливери', 'short_name' => 'д'],
            [ 'id' => 9, 'name' => 'Яндекс Маркет', 'short_name' => 'я'],
            [ 'id' => 10, 'name' => 'Флорист.ру', 'short_name' => 'ф'],
            [ 'id' => 11, 'name' => 'Бронибой', 'short_name' => 'Б'],
            [ 'id' => 12, 'name' => 'TEA.RU', 'short_name' => 'т'],
            [ 'id' => 13, 'name' => 'Ozon', 'short_name' => 'о'],
            [ 'id' => 14, 'name' => 'Мобильное приложение', 'short_name' => 'мп'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
}
