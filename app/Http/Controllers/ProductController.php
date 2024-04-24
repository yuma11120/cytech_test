<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Fileファサードを使用
use Illuminate\Support\Facades\Log; // 必ずLogファサードをuse宣言

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {//商品一覧画面
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');
        // すべてのメーカーを取得
        $companies = Company::all(); 
    
        // プロダクトクエリビルダーを取得
        $query = Product::query();
    
        // キーワードが入力されていたら商品名とメーカー名で絞り込む
        if ($keyword) {
            $query->where('product_name', 'like', "%{$keyword}%")
                ->orWhereHas('company', function ($q) use ($keyword) {
                    $q->where('company_name', 'like', "%{$keyword}%");
                });
        }
    
        // メーカーIDが選択されていたら絞り込む
        if ($company_id) {
            $query->whereHas('company', function ($q) use ($company_id) {
                $q->where('id', $company_id);
            });
        }
    
        // ページネーションを使って結果を取得
        $products = $query->paginate(4);
    
        // リクエストされた検索条件をページネーションリンクに引き継ぐ
        if ($keyword) {
            $products->appends(['keyword' => $keyword]);
        }
        if ($company_id) {
            $products->appends(['company_id' => $company_id]);
        }
    
        // ビューに変数を渡す
        return view('product.index', compact('products', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { //新規作成
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable|max:1000',
                'img_path' => 'required|image|max:2048',
            ]);

            if ($request->hasFile('img_path')) {
                $imageName = time().'.'.$request->img_path->extension();
                $request->img_path->move(public_path('images'), $imageName);
                $validatedData['img_path'] = $imageName;
            }

            $product = new Product($validatedData);
            $product->company_id = $validatedData['company_id'];
            $product->save();

            return redirect()->route('product.index')->with('success', 'product created successfully.');
        } catch (\Exception $e) {
            // エラーログに例外を記録
            \Log::error($e->getMessage());

            // 前ページにリダイレクト、エラーメッセージ表示
            return back()->withErrors('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //登録画面処理
        // すべてのメーカーを取得（メーカーをセレクトボックスで選択できるようにする）
        $company_id = $request->input('company_id');
        $companies = Company::all(); 
        return view('product.create',compact('companies'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //詳細ページの閲覧
        $product = Product::find($id);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {//レコード編集画面
        $product = Product::find($id);

        // すべてのメーカーを取得（メーカーをセレクトボックスで選択できるようにする）
        $company_id = $request->input('company_id');
        $companies = Company::all(); 
        return view('product.edit',compact('product','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {//更新処理
        try {
            $validated = $request->validate([
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable|max:1000',
                'img_path' => 'nullable|image|max:2048',
            ]);

            $product = Product::findOrFail($id);
            $product->fill($validated);

            if ($request->hasFile('img_path')) {
                // 古い画像が存在する場合は削除
                $existingImagePath = public_path('images/' . $product->img_path);
                if ($product->img_path && File::exists($existingImagePath)) {
                    File::delete($existingImagePath);
                }
                
                // 画像を保存し、一意のファイル名を生成
                $file = $request->file('img_path');
                $imageName = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images'), $imageName);
                
                // 新しい画像名をデータベースに保存
                $product->img_path = $imageName;
            }

            $product->save();

            return redirect()->route('product.index')->with('success', '商品情報が更新されました。');
        } catch (\Exception $e) {
            // エラーログに例外を記録
            \Log::error($e->getMessage());

            // エラーメッセージを表示
            return back()->withErrors('更新中にエラーが発生しました。もう一度試してください。');
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            // 画像ファイルがある場合は、それも削除
            if ($product->img_path) {
                // Storageを使用してファイルが存在するか確認し、存在する場合は削除
                if (Storage::disk('public')->exists($product->img_path)) {
                    Storage::disk('public')->delete($product->img_path);
                }
            }
            $product->delete();
            
            return redirect()->route('product.index')->with('success', '商品が正常に削除されました。');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return back()->withErrors('削除中にエラーが発生しました。もう一度試してください。');
        }
    }

   // public function destroy($id)
   // {
   //    DB::beginTransaction(); // トランザクションの開始
   //     try {
   //         $product = Product::findOrFail($id);
   //         $this->deleteImage($product->img_path); // 画像削除を専用メソッドに委ねる
   //         $product->delete();
            
   //         DB::commit(); // トランザクションをコミット
   //         return redirect()->route('product.index')->with('success', '商品が正常に削除されました。');
   //     } catch (\Exception $e) {
   //         DB::rollBack(); // エラーが発生した場合はロールバック
   //         \Log::error($e->getMessage());
   //         return back()->withErrors('削除中にエラーが発生しました。もう一度試してください。');
   //     }
   // }
}
