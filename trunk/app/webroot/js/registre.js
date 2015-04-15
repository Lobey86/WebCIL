$(document).ready(function(){

    $('#filtrage').click(function(){
           $('#divFiltrage').show();
           $('#filtrage').hide();   
    });

    $('#checkArch').change(function(){
        $('#checkNonArch').prop('checked', false);
    });
        $('#checkNonArch').change(function(){
        $('#checkArch').prop('checked', false);
    });

    $('.boutonShow').popover({
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
    $('.boutonEdit').popover({
        delay: {show: 500, hide: 100},
        animation: true,
        content: "Modifier",
        placement: 'top',
        trigger: 'hover'
    }
    );
$('.boutonArchive').popover({
        delay: {show: 500, hide: 100},
        animation: true,
        content: "Archiver",
        placement: 'top',
        trigger: 'hover'
    }
    );

});