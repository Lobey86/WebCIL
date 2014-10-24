$(document).ready(function(){
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
});