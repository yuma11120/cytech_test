$(document).ready(function() {
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();  // 標準のフォーム送信をキャンセル

        var formData = $(this).serialize();  // フォームのデータをシリアライズ

        //問題なく検索機能は機能している。
        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: formData,
            success: function(data) {
                $('#products-container').html(data);  // 部分ビューをコンテナにロード
                $('#pagination-container .pagination').css({
                'font-size': '12px',
                'color': '#007bff'
                });
                console.log(data);
            },
            error: function(xhr, status, error) {
                console.error("エラー発生:", error);
            }
        });
    });
});

$(document).ready(function() {
    $('th[data-sort]').each(function() {
        $(this).data('clicks', 0);  // クリックカウンターの初期化
    });

    $('th[data-sort]').on('click', function() {
        var table = $(this).closest('table');
        var tbody = table.find('tbody');
        var index = $(this).index();
        var clicks = $(this).data('clicks') + 1;
        $(this).data('clicks', clicks);

        if (clicks === 3) {
            // 3回クリックされたらソート解除
            $(this).data('clicks', 0);  // カウンターをリセット
            $('th').removeClass('asc desc');
            tbody.find('tr').sort(function(a, b) {
                return $(a).data('original-index') - $(b).data('original-index');
            }).appendTo(tbody);
            return;
        }

        var asc = clicks % 2 === 1;
        var rows = tbody.find('tr').toArray().sort(function(a, b) {
            var valA = getCellValue(a, index);
            var valB = getCellValue(b, index);
            valA = $.isNumeric(valA) ? parseFloat(valA) : valA.toLowerCase();
            valB = $.isNumeric(valB) ? parseFloat(valB) : valB.toLowerCase();
            return (asc ? 1 : -1) * (valA < valB ? -1 : (valA > valB ? 1 : 0));
        });

        $.each(rows, function(index, row) {
            tbody.append(row);
        });

        $('th').removeClass('asc desc');  // 他の列のソート状態をクリア
        $(this).addClass(asc ? 'asc' : 'desc');
    });

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text();
    }

    // 元の順序を保存
    $('tbody tr').each(function(index, row) {
        $(row).data('original-index', index);
    });
});

$(document).ready(function() {
    // CSRFトークンのセットアップ
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.button-delete').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        var productRow = $(this).closest('tr'); // 商品がリストされている行を特定

            $.ajax({
                url: '/cytech_test/public/destroy/' + productId, // URLを修正
                type: 'DELETE',
                success: function(result) {
                    if (result.success) {
                        // フェードアウトして行を削除
                        productRow.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert('削除に失敗しました。');
                    }
                },
                error: function(xhr) {
                    // より詳細なエラーメッセージの取得
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? 
                                    xhr.responseJSON.error : 
                                    '削除中にエラーが発生しました。';
                    alert(errorMessage);
                }
            });
        
    });
});
function validateForm() {
    let minPrice = document.getElementById('min_price').value;
    let maxPrice = document.getElementById('max_price').value;
    let minStock = document.getElementById('min_stock').value;
    let maxStock = document.getElementById('max_stock').value;

   // 価格の検証
    if ((minPrice || maxPrice) && (minPrice === "" || maxPrice === "")) {
        alert('価格を検索する場合、最低価格と最高価格の両方を入力してください。');
        return false;
    }

    if (minPrice && maxPrice && parseInt(minPrice) > parseInt(maxPrice)) {
        alert('最高価格は最低価格以上である必要があります。');
        return false;
    }

    // 在庫数の検証
    if ((minStock || maxStock) && (minStock === "" || maxStock === "")) {
        alert('在庫数を検索する場合、最低在庫数と最高在庫数の両方を入力してください。');
        return false;
    }

    if (minStock && maxStock && parseInt(minStock) > parseInt(maxStock)) {
        alert('最高在庫数は最低在庫数以上である必要があります。');
        return false;
    }

    return true;  // すべての検証が通った場合、フォームを送信
}