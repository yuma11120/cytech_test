$(document).ready(function() {

    console.log("Document ready");  // ドキュメントが読み込まれたことを確認

    $('#keywordForm').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted");  // フォームが送信されたことを確認

        var formData = $(this).serialize();
        console.log("Form data: ", formData);  // フォームデータを確認

        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: formData,
            success: function(response) {
                console.log("AJAX success: ", response);  // AJAX成功時のレスポンスを確認
                $('#products-container').html(response.html);
                bindDeleteButtons();
                
                if (response.total <= 4) {
                    $('.pagination').hide();
                } else {
                    $('.pagination').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: ", error);  // AJAXエラー時のメッセージを確認
                alert('検索中にエラーが発生しました。');
            }
        });
    });

    $('#priceForm').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted");  // フォームが送信されたことを確認

        var formData = $(this).serialize();
        console.log("Form data: ", formData);  // フォームデータを確認

        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: formData,
            success: function(response) {
                console.log("AJAX success: ", response);  // AJAX成功時のレスポンスを確認
                $('#products-container').html(response.html);
                bindDeleteButtons();
                
                if (response.total <= 4) {
                    $('.pagination').hide();
                } else {
                    $('.pagination').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: ", error);  // AJAXエラー時のメッセージを確認
                alert('検索中にエラーが発生しました。');
            }
        });
    });

    $('#stockForm').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted");  // フォームが送信されたことを確認

        var formData = $(this).serialize();
        console.log("Form data: ", formData);  // フォームデータを確認

        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: formData,
            success: function(response) {
                console.log("AJAX success: ", response);  // AJAX成功時のレスポンスを確認
                $('#products-container').html(response.html);
                bindDeleteButtons();
                
                if (response.total <= 4) {
                    $('.pagination').hide();
                } else {
                    $('.pagination').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: ", error);  // AJAXエラー時のメッセージを確認
                alert('検索中にエラーが発生しました。');
            }
        });
    });

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
                    type: 'POST',
                    data: {
                        '_method': 'delete',
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

    function bindSortHeaders() {
        $('th[data-sort]').each(function() {
            $(this).data('clicks', 0);  // クリックカウンターの初期化
        });

        $('th[data-sort]').off('click').on('click', function() {
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
    }

    function bindAll() {
        bindDeleteButtons();
        bindSortHeaders();
    }

    bindAll();

    $('#companyForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: formData,
            success: function(data) {
                $('#products-container').html(data.html);
                bindAll();
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




function validateForm() {//一覧表示ページの検索
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

$(document).ready(function() {
    $('form').on('submit', function(e) {
        var productName = $('#product_name_update').val().trim();
        var price = $('#price_update').val().trim();
        var stock = $('#stock_update').val().trim();
        var imgPath = $('#img_path_update').val().trim();

        if (!productName) {
            alert('商品名を入力してください。');
            e.preventDefault();
            return;
        }

        if (!price) {
            alert('価格を入力してください。');
            e.preventDefault();
            return;
        }

        if (!stock) {
            alert('在庫数を入力してください。');
            e.preventDefault();
            return;
        }

        if (!imgPath) {
            alert('画像を挿入してください。');
            e.preventDefault();
            return;
        }
    });
});


$(document).ready(function() {
    $('#createProductForm').on('submit', function(e) {
        var productName = $('#product_name').val();
        var stock = $('#stock').val();
        var price = $('#price').val();
        var imgPath = $('#img_path').val();

        if (!productName) {
            alert('商品名を入力してください。');
            e.preventDefault();
            return;
        }

        if (!stock) {
            alert('在庫数を入力してください。');
            e.preventDefault();
            return;
        }

        if (!price) {
            alert('価格を入力してください。');
            e.preventDefault();
            return;
        }

        if (!imgPath) {
            alert('画像を挿入してください。');
            e.preventDefault();
            return;
        }
    });
});