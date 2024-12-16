$(document).ready(function () {
    $('.puchase_form').trigger("reset");
    var inv_type = $('#inv_type').val();
    checkInv(inv_type);
    $('body').on('change', '#inv_type', function () {
        var inv_type = $(this).val();
        checkInv(inv_type);
    })
});

function checkInv(inv_type){
    if (inv_type == 1) {
        $('.safe').removeClass('d-none');
        $('.bank_id').addClass('d-none');
        $('.uncash_type').addClass('d-none');
        $('.pay_date').addClass('d-none');
        $('#bank_id').val("");
        $('#uncash_type').val("");
        $('#pay_date').val("");
        $('span.supplier_id').remove();
        $('span.client_id').remove();
    }
    else if (inv_type == 2) {
        $('.safe').addClass('d-none');
        $('.bank_id').removeClass('d-none');
        $('.uncash_type').addClass('d-none');
        $('.pay_date').addClass('d-none');
        $('#uncash_type').val("");
        $('#pay_date').val("");
        $('span.supplier_id').remove();
        $('span.client_id').remove();
        }
    else if (inv_type == 3) {
        $('.safe').addClass('d-none');
        $('.uncash_type').removeClass('d-none');
        $('.pay_date').removeClass('d-none');
        $('.bank_id').addClass('d-none');
        $('#bank_id').val("");
        $('<span class="require-insert supplier_id">*</span>').insertBefore('label.supplier_id');
        $('<span class="require-insert client_id">*</span>').insertBefore('label.client_id');
    }
}