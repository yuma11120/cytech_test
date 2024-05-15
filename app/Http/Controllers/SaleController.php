<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request)
    { // productsテーブルのid(auto_increment)でAPIを叩いている。
        
        // リクエストから必要なデータを取得する
        $product_name = $request->input('id'); 
        $quantity = $request->input('quantity', 1); // 購入する数を代入する 、”quantity”というデータが送られていない場合は1を代入する

        // データベースから対象の商品を検索・取得
        $product = Product::find($product_name);
        
        // 商品が存在しない、または在庫が不足している場合のバリデーションを行う 
        if (!$product) {
            return response()->json(['message' => '商品が存在しません'], 404);
        }
        if ($product->stock < $quantity) {
            return response()->json(['message' => '商品が在庫不足です'], 400);
        }

        try {
            DB::beginTransaction();
            $product_id = $product->id;
            // 在庫を減少させる
            $product->stock -= $quantity;
            $product->save();

            // Salesテーブルに商品IDと購入日時を記録する
            $sale = new Sale([
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
            $sale->save();

            DB::commit(); // トランザクションをコミット
            return response()->json(['message' => '購入成功'], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // エラーが発生した場合はロールバック
            return response()->json(['message' => '購入処理中にエラーが発生しました', 'error' => $e->getMessage()], 500);
        }
    }
        

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(sales $sales)
    {
        //
    }

    
    
}
