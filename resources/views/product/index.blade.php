<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>商品一覧画面</title>

        <!-- Fonts -->

        <!-- style -->
        <link href="{{ asset('css/step7welcome.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="{{ asset('js/app.js') }}"></script>
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
                <div class = 'formContent' >
                    <form action="{{ route('product.index') }}" id="keywordForm"  method="GET"><p>キーワード：</p>
                        <input type="search" name="keyword" placeholder="検索" class="input-text" />
                        <input type="submit" id="search" value="検索" class="input" />
                    </form>
                </div>
                <div class = 'formContent'>
                    
                    <form id="companyForm" action="{{ route('product.index') }}" method="GET">
                        <p>メーカー名：</p>
                        <select name="company_id" class="select">
                            <option value=""></option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"{{ request('company_id') == $company->id ? ' selected' : '' }}>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                        <input type="submit" id="search" value="検索" class="input" />
                    </form>
                </div>
                <div class = 'formContent'>
                    <form action="{{ route('product.index') }}" id="priceForm" method="GET" onsubmit="return validateForm()">
                        <p>最低価格：</p>
                        <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" class="input-text" placeholder="最低価格" />
                        <p>最高価格：</p>
                        <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" class="input-text" placeholder="最高価格"/>
                        <input type="submit" id="search" value="検索" class="input" />
                    </form>
                </div>
                <div class = 'formContent'>
                    <form action="{{ route('product.index') }}" id="stockForm" method="GET" onsubmit="return validateForm()">
                        <p>最低在庫：</p>
                        <input type="number" id="min_stock" name="min_stock" value="{{ request('min_stock') }}" class="input-text" placeholder="最低在庫数"/>
                        <p>最高在庫：</p>
                        <input type="number" id="max_stock" name="max_stock" value="{{ request('max_stock') }}" class="input-text" placeholder="最高在庫数"/>
                        <input type="submit" id="search" value="検索" class="input" />
                    </form>
                </div>
                <button type="button" onclick="window.location='{{ route('product.index') }}'" class="">検索解除</button>

            </div>
            <div id="products-container">
                    @include('partials.product_table', ['products' => $products])
            </div>

    </body>
</html>
