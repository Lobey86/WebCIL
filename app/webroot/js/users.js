$(document).ready(function () {
    $('.boutonDelete').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Supprimer",
            placement: 'top',
            trigger: 'hover'
        }
    );
    $('.boutonEdit').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Modifier",
            placement: 'top',
            trigger: 'hover'
        }
    );

    $(".multiDeroulant").chosen({no_results_text: "Aucun résultat trouvé pour", width: '100%'});

    $("#deroulant").change(function () {
        $('.droitsVille').hide();
        $("#deroulant option:selected").each(function () {
            var clickedOptionValue = $(this).attr('value');
            $('#droitsVille' + clickedOptionValue).show();
        });
    }).trigger("change");


    $('.btnDroitsParticuliers').click(function () {
        var valeur = $(this).attr('value');
        if ($('#droitsParticuliers' + valeur).is(':visible')) {
            $('#droitsParticuliers' + valeur).hide();
        }
        else {
            $('#droitsParticuliers' + valeur).show();
        }
    });

    $("[class*='deroulantRoles']").change(function () {
        var val = $(this).attr('id');
        $('.checkDroits' + val).prop('checked', false);
        $(".deroulantRoles" + val + " option:selected").each(function () {
            var id = $(this).attr('value');
            $('.checkDroits' + val + '.' + id).prop('checked', true);

        });
    }).trigger("change");

});