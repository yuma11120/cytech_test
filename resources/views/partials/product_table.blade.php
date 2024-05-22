<table class="table">
    <thead class="header">
        <tr>
            <th data-sort="id">ID</th>
            <th>商品画像</th>
            <th data-sort="product_name">商品名</th>
            <th data-sort="price">価格</th>
            <th data-sort="stock">在庫数</th>
            <th>メーカー名</th>
            <th><a href="{{ route('product.create') }}" class="button-create">登録</a></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td><img src="{{ asset('images/' . $product->img_path) }}" alt="商品画像" style="width: 30px;"></td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->company->company_name }}</td>
                <td><a href="{{ route('product.show', ['id'=>$product->id]) }}" class="detail">詳細</a></td>
                <td>
                    <button class="button-delete" data-id="{{ $product->id }}">削除</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@if ($products->count() > 0 && $products->total() > $products->perPage())
                    <div class="pagination">
                        {{ $products->links() }}
                    </div>
@endif