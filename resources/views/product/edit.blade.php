<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                        <form action="{{ route('product.update', ['id'=>$product->id]) }}" method = "post" class = 'form-container' enctype="multipart/form-data" id = 'updateForm'>
                        @csrf
                        @method('PUT')
                        <h1>商品情報編集画面</h1> 
                            <div class = 'editContent' >                              
                                <p>ID : {{ $product->id }}</p>
                                <input type="hidden" name="company_id" value="{{ $product->id }}">
                            </div>
                            <div class = 'editContent'>
                                <p>商品画像<span>*</span></p><input type="file" name="img_path" id="img_path_update">
                            </div>
                            <div class = 'editContent'>
                                <p>商品名<span>*</span></p>
                                <input type="text" value = '{{ $product->product_name }}' name = 'product_name' id="product_name_update">                      
                            </div>
                            <div class = 'editContent'>
                                <p>価格<span>*</span></p>
                                <input type="text" value = '{{ $product->price }}' name = 'price' id="price_update">
                            </div>
                            <div class = 'editContent'>
                                <p>在庫数<span>*</span></p>
                                <input type="text" value = '{{ $product->stock }}' name = 'stock' id="stock_update">
                            </div>
                            <div class="editContent">
                                <p>メーカー名<span>*</span></p>
                                <select name="company_id" class="select">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}"{{ $product->company_id == $company->id ? ' selected' : '' }}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class = 'editContent'>
                                <p>コメント</p>
                                <textarea  name="comment" >{{ $product->comment }}</textarea>                            
                            </div>
                            <div class = 'editContent'>
                                <input type="submit" value = "編集">
                            </div>
                            <div>
                                <a href="/cytech_test/public/">一覧画面に戻る</a>
                            </div>
                        </form>
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
    </body>
</html>
