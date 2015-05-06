$(document).ready(function () {
    $('#modalNotif').modal();

    $(".usersDeroulant").chosen({no_results_text: "Aucun résultat trouvé pour", width: '100%'});
    $('[data-toggle="popover"]').popover();

    $('[id^="collapse"]').on('shown.bs.collapse', function () {
        var coucou = $(this).attr('id');
        console.log(coucou);
        $('html, body').animate({
            scrollTop: $('#' + coucou).offset().top - 100
        }, 'fast');
        return false;
    });

});