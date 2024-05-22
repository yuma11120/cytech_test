<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Fileファサードを使用
use Illuminate\Support\Facades\Log; // 必ずLogファサードをuse宣言
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

        public function index(Request $request)
    {
        // 商品一覧画面
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');
        $minPrice = $request->input('min_price'); // 最低価格の取得
        $maxPrice = $request->input('max_price'); // 最高価格の取得
        $minStock = $request->input('min_stock'); // 最低在庫数の取得
        $maxStock = $request->input('max_stock'); // 最高在庫数の取得

        try {
            // バリデーション
            $request->validate([
                'sort' => 'in:stock,price',
                'direction' => 'in:asc,desc',
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'min_stock' => 'nullable|numeric|min:0',
                'max_stock' => 'nullable|numeric|min:0',
            ]);

            // 会社データを全て取得
            $companies = Company::all(); 

            // プロダクトクエリビルダーを取得
            $query = Product::query();

            // 各検索条件の適用
            if ($keyword) {
                $query->where(function($query) use ($keyword) {
                    $query->where('product_name', 'like', "%{$keyword}%")
                        ->orWhereHas('company', function ($q) use ($keyword) {
                            $q->where('company_name', 'like', "%{$keyword}%");
                        });
                });
            }

            if ($company_id) {
                $query->whereHas('company', function ($q) use ($company_id) {
                    $q->where('id', $company_id);
                });
            }

            if ($minPrice !== null) {
                $query->where('price', '>=', $minPrice);
            }

            if ($maxPrice !== null) {
                $query->where('price', '<=', $maxPrice);
            }

            if ($minStock !== null) {
                $query->where('stock', '>=', $minStock);
            }

            if ($maxStock !== null) {
                $query->where('stock', '<=', $maxStock);
            }

            if ($request->has('sort') && $request->has('direction')) {
                $sort = $request->query('sort');
                $direction = $request->query('direction');
                $query->orderBy($sort, $direction);
            } else {
                $query->orderBy('id', 'asc');
            }

            $products = $query->paginate(4);

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('partials.product_table', compact('products'))->render(),
                    'total' => $products->total()
                ]);
            }

            return view('product.index', compact('products', 'companies'));
        } catch (\Exception $e) {
            Log::error('商品一覧の取得に失敗しました。', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => '内部サーバーエラーが発生しました。', 'message' => $e->getMessage()], 500);
        }
    }

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

    public function store(Request $request)
    {
        //登録画面処理
        // すべてのメーカーを取得（メーカーをセレクトボックスで選択できるようにする）
        $company_id = $request->input('company_id');
        $companies = Company::all(); 
        return view('product.create',compact('companies'));
    }

    public function show($id)
    {
        //詳細ページの閲覧
        $product = Product::find($id);
        return view('product.show', compact('product'));
    }

    public function edit(Request $request, $id)
    {//レコード編集画面
        $product = Product::find($id);

        // すべてのメーカーを取得（メーカーをセレクトボックスで選択できるようにする）
        $company_id = $request->input('company_id');
        $companies = Company::all(); 
        return view('product.edit',compact('product','companies'));
    }

    public function update(Request $request, $id)
    {//更新処理
        try {
            // バリデーション
            $validated = $request->validate([
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable|max:1000',
                'img_path' => 'nullable|image|max:2048',
            ]);

            // 画像がアップロードされていない場合はバリデーションエラーを返す
            if (!$request->hasFile('img_path')) {
                return back()->withErrors('画像を挿入してください。');
            }

            // 商品情報の取得
            $product = Product::findOrFail($id);
            $product->fill($validated);

            // 画像の処理
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

            // 商品情報の保存
            $product->save();

            return redirect()->route('product.index')->with('success', '商品情報が更新されました。');
        } catch (\Exception $e) {
            // エラーログに例外を記録
            \Log::error($e->getMessage());

            // エラーメッセージを表示
            return back()->withErrors('更新中にエラーが発生しました。もう一度試してください。');
        }
    }


    public function destroy($id)
        {
            try {
                $product = Product::findOrFail($id);
                if ($product->img_path && Storage::disk('public')->exists($product->img_path)) {
                    Storage::disk('public')->delete($product->img_path);
                }
                $product->delete();

                return response()->json(['success' => '商品が正常に削除されました。']);
            } catch (\Exception $e) {
                \Log::error('商品削除中にエラーが発生しました: ' . $e->getMessage(), [
                    'id' => $id,
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => '削除中にエラーが発生しました。もう一度試してください。', 'exception' => $e->getMessage()], 500);
            }
        }

    

}
