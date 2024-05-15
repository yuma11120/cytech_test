<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>商品一覧画面</title>

        <!-- Fonts -->

        <!-- style -->
        <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
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
</html>