$(document).ready(function() {
    checkStock();

    //Add Product For Transform
    $(document).on("click",".btn-add-product", function (e) {
        e.preventDefault();
        var product_id = $(this).data("product"),
            stock_id = $(this).data("id"),
            name = $(this).data("name"),
            stock = $(this).data("stock"),
            url = $(this).data("url"),
            qty = $(this).data("qty");
        $.ajax({
            url: url,
            data: {
                id: product_id,
                name: name,
                stock: stock,
                qty: qty,
                stock_id: stock_id
            },
            method: "get",
            success: function(data) {
                $(".body-list").append(data);
                checkSelect();
            }
        });
        quantity(qty, stock_id, product_id);
        $(".product_" + product_id).addClass("disabled ch");
        $(this)
            .removeClass("btn-success")
            .addClass("btn-white");
        checkStock();
        
    });

    // Remove Product From Transform
    $(document).on("click", ".btn-remove-product", function(e) {
        e.preventDefault();
        var id = $(this).attr("id"),
            qty = $(this).data("qty"),
            qty2 = $(this).data("qty2"),
            stock = $(this).data("stock"),
            stock2 = $(this).data("stock2"),
            check = -1;
        $(this)
            .closest("tr")
            .remove();
        $(".product-list .stock-" + stock + "-" + id).html(qty);
        $(".product-list .stock-" + stock2 + "-" + id).html(qty2);
        $("#product-" + stock + "-" + id)
            .removeClass("btn-white ch")
            .addClass("btn-success");
        $(".product_" + id).removeClass("disabled");
        $(".body-list .input-qty").each(function(index) {
            check = index;
        });
        if (check < 0) {
            $(".btn-form-product").addClass("disabled");
        }
        checkStock();
        checkSelect();
    });

    // Disabled btn
    $("body").on("click", ".disabled", function(e) {
        e.preventDefault();
    });

    // Calculate The Qty Of Product You Want Add To Anthor Stock
    $("body").on("keyup change", ".input-qty", function() {
        var id = $(this).data("id"),
            stock = $(this).data("stock"),
            qty = $(this).data("qty"),
            val = $(this).val(),
            qty2 = Number($(".to_qty_" + stock + "_" + id).data("qty")),
            stock2 = Number(val) + Number(qty2),
            select = $(".select_" + stock + "_" + id).val();
        $(".to_qty_" + stock + "_" + id).val(stock2);
        $(".product-list .stock-" + select + "-" + id).html(stock2);
        
        if (qty - val < 0) {
            $(".input-qty")
            .not(".input_" + stock + "_" + id)
            .prop("disabled", true)
            .addClass("dis");
            $(".btn-form-product").addClass("disabled");
        } else {
            $(".btn-form-product").removeClass("disabled");
            $(".select_stock").each(function(index) {
                var val = $(this).val(),
                product = $(this).data("id"),
                stock = $(this).data("stock");
                if (val == "") {
                    $(".input_" + stock + "_" + product)
                    .prop("disabled", true)
                    .addClass("dis");
                    $(".btn-form-product").addClass("disabled");
                } else {
                    $(".input_" + stock + "_" + product)
                    .prop("disabled", false)
                    .removeClass("dis");
                }
            });
        }
        
        quantity(qty, stock, id, val);
        checkStock();
        $("#product-" + select + "-" + id).addClass('disabled');
    });

    $("body").on("keyup change", ".select_stock", function() {
        var id = $(this).val(),
            product = $(this).data("id"),
            stock = $(this).data("stock"),
            url = $(this).data("url"),
            Qty = $(this).data("qty"),
            val = Number($(".input_" + stock + "_" + product).val()),
            re = Qty - val,
            qty = Number(
                $(".product-list .stock-" + id + "-" + product).html()
            );
        $(".product-list .stock-" + id + "-" + product).html(qty + 1);
        $("#product-" + id + "-" + product).addClass('disabled');

        $.ajax({
            url: url,
            data: { id: id, product: product },
            method: "get",
            success: function(data) {
                $(".to_qty_" + stock + "_" + product).val(data.new_qty);
                $(".to_qty_" + stock + "_" + product).attr(
                    "data-qty",
                    data.old_qty
                );
                $(".remove_" + stock + "_" + product).attr(
                    "data-qty2",
                    data.old_qty
                );
                $(".remove_" + stock + "_" + product).attr("data-stock2", id);
            }
        });
        quantity(Qty, stock, product, val);
        checkStock()
        if (id == "" || re < 0) {
            $(".input_" + stock + "_" + product).prop("disabled", true);
            $(".input_" + stock + "_" + product).addClass("dis");
            $(".btn-form-product").addClass("disabled");
        } else {
            $(".input_" + stock + "_" + product).prop("disabled", false);
            $(".input_" + stock + "_" + product).removeClass("dis");
            $(".btn-form-product").removeClass("disabled");
        }

        $(".select_stock").each(function(index) {
            var val = $(this).val(),
                product = $(this).data("id"),
                stock = $(this).data("stock");
            if (val == "") {
                $(".btn-form-product").addClass("disabled");
                return false;
            } else {
                $(".btn-form-product").removeClass("disabled");
            }
        });
    });

    $(document).on('click', '.page-link', function () {
    
        $('.btn-add-product').each(function () {
            var id = $(this).data('product'),
                stock = $(this).data('id'),
                qty = $(this).data('qty');
            $(this).removeClass('btn-white  disabled').addClass('btn-success');
            $(".product-list .stock-" + stock + "-" + id).html(qty);

            $('.input-qty').each(function () {
                var id1 = $(this).data('id'),
                    stock1 = $(this).data('stock'),
                    qty1 = $(this).data('qty'),
                    val = $(this).val();
                
                if (id == id1 && stock == stock1) {
                    var qty2 = qty1 - val;
                    $("#product-" + stock + "-" + id).addClass('btn-white ch disabled').removeClass('btn-success');
                    $(".product-list .stock-" + stock + "-" + id).html(qty2);
                    $(".from_qty_" + stock + "_" + id).val(qty);
                    return false;
                }
                else if (id == id1 && stock != stock1) {
                    $("#product-" + stock + "-" + id).addClass('ch disabled');
                    return false;
                }
                else {
                    console.log('d');
                    $("#product-" + stock + "-" + id).removeClass('ch disabled');
                }
                

                if (val > qty1){
                    $('.btn-add-product').addClass('disabled');
                        return false;
                } else{
                    $(".btn-add-product").removeClass("disabled");
                }
            }); 
        });

        //  $('.btn-add-product.ch').addClass('disabled');

        $('.select_stock').each(function () {
            var val2 = $(this).val();
            $('.input-qty').each(function () {
                var val6 = $(this).val(),
                id6 = $(this).data('id');
                if (val2 !='' ) {
                    qty6 = $(".product-list .stock-" + val2 + "-" + id6).html();
                    qty5 = Number(val6) + Number(qty6);
                    $(".product-list .stock-" + val2 + "-" + id6).html(qty5);
                    $("#product-" + val2 + "-" + id6).addClass('disabled');
                    return false;
                }
            });
        });

        checkStock();
    });

   
});

// calcuate The QTY Of Product In Stock
function quantity(q, stock_id, product_id, v = 1) {
    var stock = q - v;
    $(".product-list .stock-" + stock_id + "-" + product_id).html(stock);
    $(".from_qty_" + stock_id + "_" + product_id).val(stock);

    if (stock < 0) {
        $(".alert-custom").removeClass("d-none");
        $(".btn-add-product").addClass("disabled");
        $(".btn-remove-product").addClass("disabled");
        $(".select_stock")
            .prop("disabled", true)
            .addClass("dis");
    } else {
        $(".alert-custom").addClass("d-none");
        $(".btn-add-product.btn-success")
            .not(".ch")
            .removeClass("disabled");
        $(".btn-remove-product").removeClass("disabled");
        $(".select_stock")
            .prop("disabled", false)
            .removeClass("dis");
    }
}

// Check The Quantity Of Stock
function checkStock() {
    $(".product-list .qty").each(function(index) {
        var qty = $(this).html();
        if (qty == 0) {
            $(this)
                .closest("tr")
                .find(".btn-add-product")
                .addClass("disabled");
        }
    });
}

function checkSelect() {
    $(".select_stock").each(function() {
        var val = $(this).val();
        if (val == "") {
            $(".btn-form-product").addClass("disabled");
            return false;
        } else {
            $(".btn-form-product").removeClass("disabled");
        }
    });
}
