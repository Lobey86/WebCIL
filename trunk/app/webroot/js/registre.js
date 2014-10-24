$(document).ready(function(){
    $('.boutonVoir').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Voir",
            placement: 'top',
            trigger: 'hover'
        }
    );
    $('.boutonDl').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Télécharger",
            placement: 'top',
            trigger: 'hover'
        }
    );
    $('.boutonModifier').popover({
            delay: {show: 500, hide: 100},
            animation: true,
            content: "Modifier",
            placement: 'top',
            trigger: 'hover'
        }
    );
});