<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


        <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">

    </head>
    <body class="antialiased">
        <div class="confirm-container">

            <h1>商品情報詳細画面</h1>        
                    <table class = ''>
                        <tr>
                            <th>ID</th>
                            <th>{{ $product->id }}</th>
                        </tr>
                        <tr>
                            <th>商品画像</th>
                            <th>
                                @if ($product->img_path)
                                    <img src="{{ asset('images/' . $product->img_path) }}" alt="商品画像" style="width: 30px; "> <!-- 画像の幅を150pxに設定 -->
                                @else
                                    画像がありません。
                                @endif
                            </th>
                        </tr>
                            <th>商品名</th>
                            <th>{{ $product->product_name }}</th>
                        </tr>
                        <tr>
                            <th>価格</th>
                            <th>{{ $product->price }}</th>
                        </tr>
                        <tr>
                            <th>在庫数</th>
                            <th>{{ $product->stock }}</th>
                        </tr>
                        <tr>
                            <th>メーカー名</th>
                            <th>{{ $product->company->company_name }}</th>
                        </tr>
                        <tr>
                            <th>コメント</th>
                            <th>{{ $product->comment }}</th>
                        </tr>
                    </table>
                    <a href="{{ route('product.edit', ['id'=>$product->id]) }}">編集</a>
        
        <div>
            <a href="/cytech_test/public/">一覧画面に戻る</a>
        </div>
            
        </div>


    </body>
</html>
