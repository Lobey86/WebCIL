$(document).ready(function(){
   $(".error").addClass("has-error");

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
    $('.boutonShow').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Voir",
            placement: 'top',
            trigger: 'hover'
        }
    );
    $(".usersDeroulant").chosen({no_results_text: "Aucun résultat trouvé pour", width:'100%'});
});