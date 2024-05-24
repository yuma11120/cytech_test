<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body class="antialiased">
    <div class="create">
        <h1>登録画面</h1>
        <form id="createProductForm" action="{{ route('product.new') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="table">
                <p>商品名<span>*</span></p>
                <input type="text" name="product_name" id="product_name">
            </div>
            <select name="company_id" class="select">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}"{{ request('company_id') == $company->id ? ' selected' : '' }}>{{ $company->company_name }}</option>
                @endforeach
            </select>
            <div class="table">
                <p>在庫数<span>*</span></p>
                <input type="text" name="stock" id="stock">
            </div>
            <div class="table">
                <p>価格<span>*</span></p>
                <input type="text" name="price" id="price">
            </div>
            <div class="table">
                <p>コメント</p>
                <textarea name="comment"></textarea>
            </div>
            <div>
                <input type="file" name="img_path" id="img_path">
            </div>
            <div>
                <input type="submit" value="新規作成">
            </div>
        </form>
        <div>
            <a href="/cytech_test/public/home">戻る</a>
        </div>
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($message = Session::get('success'))
            <div>
                <strong>{{ $message }}</strong>
            </div>
            <img src="/images/{{ Session::get('image') }}">
        @endif
    </div>
</body>
</html>
