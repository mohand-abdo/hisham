$(document).ready(function () {

    // checkStock();

    //Add Product To Order
    $(document).on("click",".btn-add-product", function (e) {
        e.preventDefault();
        var id = $(this).data('id'),
            name = $(this).data('name'),
            price = $(this).data('price'),
            qty = $(this).data('qty'),
            tr = '<tr class="row-' + id + '"><td>' + name + '</td><td><input type="number" name="item_qty[]" class="form-control form-control-alternative input-sm input-qty input-'+id+'" min="1" value="1" data-price="' + price + '" data-id="' + id + '" data-qty="' + qty + '"/></td><td class="price" required>' + price.toLocaleString() + '</td><td><a href="" class="btn btn-danger btn-sm btn-remove-product  rounded-circle" id="' + id + '" data-qty="' + qty + '"><i class="fa fa-trash"></i></a><input type="hidden" class="qtystock-'+id+'" value="'+qty+'" required name="total_qty[]"><input type="hidden" name="id_item[]" value="'+id+'"><input type="hidden" name="item_price[]" value="'+price+'"></td></tr>';
        $('.body-list').append(tr);
        $(this).removeClass('btn-success').addClass('disabled').addClass('btn-white');
        totalPrice();
        quantity(qty, id);
        // if ()
        // checkStock();
    });

    // Remove Product From Order
    $('body').on('click', '.btn-remove-product', function (e) {
        e.preventDefault();
        var id = $(this).attr('id'),
            stock = $(this).data('qty');
        $(this).closest('tr').remove();
        $('.product-list .stock-' + id).html(stock);
        $('#product-' + id).removeClass('btn-white disabled').addClass('btn-success');
        totalPrice();
        // checkStock();
    });

    // Disabled btn
    $('body').on('click', '.disabled', function (e) {
        e.preventDefault();
    });

    // Calculate The Price OF Product
    $('body').on('keyup change', '.input-qty', function () {
        var id = $(this).data('id'),
            stock = $(this).data('qty'),
            qty = $(this).val(),
            unitPrice = $(this).data('price');
        $(this).closest('tr').find('.price').html(Number(qty * unitPrice).toLocaleString());
        totalPrice();
        quantity(stock, id, qty);
        // checkStock();
        
    });

    $(document).on('click', '.page-link', function () {
    
        $('.btn-add-product').each(function () {
            var id = $(this).data('id'),
                qty = $(this).data('qty');
            $(this).removeClass('btn-white ch disabled').addClass('btn-success');
            $(".stock" + "-" + id).html(qty);

            $('.input-qty').each(function () {
                var id1 = $(this).data('id'),
                    qty1 = $(this).data('qty'),
                    val = $(this).val();
                
                if (id == id1) {
                    var qty2 = Number(qty1) + Number(val);
                    $("#product-" + id).addClass('btn-white ch disabled').removeClass('btn-success');
                    $(".product-list .stock-" + id).html(qty2);
                     $(".qtystock-"  + id).val(qty);
                    return false;
                }  
            }); 
        });
    });

});

// Calculate The Total Price Of Order
function totalPrice() {
    var totalPrice = 0;
    $('.body-list .price').each(function () {
        totalPrice += parseFloat($(this).html().replace(/,/g,''));
    });

    $('.total-price').html(totalPrice.toLocaleString());
    $('.total_price').val(totalPrice);
    if (totalPrice > 0) {
        $('.btn-form-product').removeClass('disabled');

    } else {
        $('.btn-form-product').addClass('disabled');
    }
}

// calcuate The QTY Of Product In Stock
function quantity(q, id, v = 1) {
    var stock = Number(q) + Number(v);
    $('.product-list .stock-' + id).html(stock);
    $('.qtystock-'+id).val(stock);
    if (stock < 0) {
        $('input').not('.input-'+id).attr('disabled','disabled');
        $('.btn-form-product').addClass('disabled');
        $('.alert-custom').removeClass('d-none');
        $('.btn-add-product').addClass('disabled');
        $('.btn-remove-product').addClass('disabled');
    } else {
        $('input').removeAttr('disabled');
        $('.btn-form-product').removeClass('disabled');
        $('.alert-custom').addClass('d-none');
        $('.btn-add-product.btn-success').removeClass('disabled');
        $('.btn-remove-product').removeClass('disabled');
    }
}

// Check The Quantity Of Stock
// function checkStock() {
//     $('.product-list .qty').each(function (index) {
//         var qty = $(this).html();
//         if (qty == '0') {
//             $(this).closest('tr').find('.btn-add-product').addClass('disabled');
//         }
//     });
// }
