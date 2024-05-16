$(document).ready(function() {
    $('#companyForm').on('submit', function(e) {
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
    // 削除ボタンにイベントリスナーをバインドする関数
    function bindDeleteButtons() {
        $('.button-delete').off('click').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            console.log('削除ボタンがクリックされました。商品ID:', productId);
            if (!productId) {
                alert('商品IDが見つかりません。');
                return;
            }
            var token = $('meta[name="csrf-token"]').attr('content');
            var productRow = $(this).closest('tr');
            if (confirm('この商品を削除してもよろしいですか？')) {
                $.ajax({
                    url: '/cytech_test/public/product/destroy/' + productId,
                    type: 'DELETE',
                    data: {
                        "_token": token,
                    },
                    success: function(result) {
                        if (result.success) {
                            productRow.fadeOut(400, function() {
                                $(this).remove();
                            });
                            alert('商品が正常に削除されました。');
                        } else {
                            alert('削除中にエラーが発生しました。');
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON ? xhr.responseJSON.exception : '削除中にエラーが発生しました。';
                        alert(errorMessage);
                    }
                }).catch(function(e) {
                    console.error("AJAX request failed: ", e.message);
                    alert("AJAX request failed: " + e.message);
                });
            }
        });
    }

    // 初期バインド
    bindDeleteButtons();

    // 検索フォームの送信イベントに対する処理
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateForm()) {
            return;
        }
        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: $(this).serialize(),
            success: function(response) {
                $('#products-container').html(response);
                bindDeleteButtons(); // 検索結果に対して再度バインド
            },
            error: function(xhr, status, error) {
                alert('検索中にエラーが発生しました。');
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