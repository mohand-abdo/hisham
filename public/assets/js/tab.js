$(document).ready(function () {

    $('.tab ul li').click(function () {
        var id = $(this).attr('id'),
            route = $(this).attr('data-route');
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        // $('.tab .tab-content.d-flex').removeClass('d-flex');
        // $('#' + id + '-content').addClass('d-flex');
        $('.tab .tab-content').hide();
        $('#' + id + '-content').fadeIn(500);

        $('.btn-add').attr('href', route);
    });
});
