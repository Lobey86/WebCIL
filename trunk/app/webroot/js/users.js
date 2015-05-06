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
        var idOrga = $(this).attr('id'); //id de l'organisation

        $(".deroulantRoles" + idOrga + " option:selected").each(function () {
            var idRole = $(this).attr('value'); // id du rôle
            for (var key in data = eval("tableau_js" + idRole)) {
                $('.checkDroits' + idOrga + data[key]).prop('checked', true);
            }
        });
        $(".deroulantRoles" + idOrga + " option:not(:selected)").each(function () {
            var idRole = $(this).attr('value'); // id du rôle
            for (var key in data = eval("tableau_js" + idRole)) {
                $('.checkDroits' + idOrga + data[key]).prop('checked', false);
            }
        });
    }).trigger("change");

    $("#filtrageUsers").click(function () {
        $("#filtreUsers").slideToggle(400);
    });
});