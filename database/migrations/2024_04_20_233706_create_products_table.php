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
    {//テーブル作成時にはコピペ
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('company_id');
            $table->string('product_name');
            $table->bigInteger('price');
            $table->bigInteger('stock');
            $table->text('comment')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps(); 
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id('id');
            $table->string('company_name');
            $table->string('street_address')->nullable();
            $table->string('representative_name')->nullable();
            $table->timestamps(); 
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('product_id');
            $table->timestamps(); 
        });

        //ここから記述自由
        $qb = \DB::table('companies');
        $insert = [
            [
                'id' => null,
                'company_name' => '伊藤園',

            ],
            [
                'id' => null,
                'company_name' => 'スターバックス',
            ],
            [
                'id' => null,
                'company_name' => 'りんご製菓',

            ],
            [
                'id' => null,
                'company_name' => 'Coca-Cola',

            ]
        ];
        $qb->insert($insert);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
