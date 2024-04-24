<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>商品一覧画面</title>

        <!-- Fonts -->

        <!-- style -->
        <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">

    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                    @else

                        @if (Route::has('register'))
                        @endif
                    @endauth
                </div>
            @endif
            <h1 class = 'title'>商品一覧画面</h1>
            <div class="form">
                <form action="{{ route('product.index') }}" method="GET">
                    <input type="search" name="keyword" placeholder="検索" class="input-text" />
                                        <!-- メーカーを選択するためのセレクトボックスを追加 -->
                    <select name="company_id" class="select">
                        <option>メーカー名を検索</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}"{{ request('company_id') == $company->id ? ' selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>

                    <input type="submit" id="search" value="検索" class="input" />
                    <button type="button" onclick="window.location='{{ route('product.index') }}'" class="">検索解除</button>
                </form>


            </div>
            <div>
                <table class = 'table'>
                    
                    <thead class = 'header'>
                        <tr>
                            <th>ID</th>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>メーカー名</th>
                            <th><a href="{{ route('product.create') }}" class = 'button-create' >登録</a></th> <!-- 送信先のファイルを作成する必要あり（step7_testを参考にする）-->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                            <td>{{ $product->id }}</td>
                            <td><img src="{{ asset('images/' . $product->img_path) }}" alt="商品画像" style="width: 30px; "> <!-- 画像の幅を150pxに設定 -->
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->company->company_name }}</td>
                            <td ><a href="{{ route('product.show', ['id'=>$product->id]) }}" class = 'detail'>詳細</a></td>
                            <td>
                                <form action="{{ route('product.destroy', ['id'=>$product->id]) }}" method="POST"> <!-- 送信先のファイルを作成する必要あり（step7_testを参考にする）-->
                                @csrf
                                <input type="hidden" name="id">
                                <button  class = 'button-delete' type="submit" class="btn btn-danger">削除</button>
                                </form>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class = "pagination">
            {{ $products->links() }}
        </div>
    </body>
</html>
