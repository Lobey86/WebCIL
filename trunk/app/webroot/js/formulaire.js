$(document).ready(function(){

/**
 *affichage ou non des champs de coordonnées du responsable du traitement
 */

    if($('#FicheTraitementexterne').is(':checked')){
        $('#coordoneestraitement').show();
    }
    $('#FicheTraitementexterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#coordoneestraitement').show();
        }
        else if($(this).not(':checked')){
            $('#coordoneestraitement').hide();
        }
    });


/**
*affichage ou non du champ details autres des personnes concernées par le traitement
*/

    if($('#FicheFinalitecibleautres').is(':checked')){
        $('#finalitecibleautresdiv').show();
    }
    $('#FicheFinalitecibleautres').click (function ()
    {
        if($(this).is(':checked')){
            $('#finalitecibleautresdiv').show();
        }
        else if($(this).not(':checked')){
            $('#finalitecibleautresdiv').hide();
        }
    });

/**
*affichage ou non du champ details autres des technologies particulières
*/

    if($('#FicheTechnologiesparticulieresautres').is(':checked')){
        $('#technologiesparticulieresautresdiv').show();
    }
    $('#FicheTechnologiesparticulieresautres').click (function ()
    {
        if($(this).is(':checked')){
            $('#technologiesparticulieresautresdiv').show();
        }
        else if($(this).not(':checked')){
            $('#technologiesparticulieresautresdiv').hide();
        }
    });

/**
*affichage ou non des champs de coordonées de l'organisme qui traite la mise en oeuvre
*/

    if($('#FicheMiseenoeuvreexterne').is(':checked')){
        $('#miseenoeuvreexternecoordonneesdiv').show();
    }
    $('#FicheMiseenoeuvreexterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#miseenoeuvreexternecoordonneesdiv').show();
        }
        else if($(this).not(':checked')){
            $('#miseenoeuvreexternecoordonneesdiv').hide();
        }
    });

/**
*affichage ou non des champs de transfert de données hors ue
*/

    if($('#FicheTransferthorsuesuffisant').is(':checked')){
        $('#transfertdiv').show();
    }
    if($('#FicheTransferthorsueinsuffisant').is(':checked')){
        $('#transfertdiv').show();
        $('#transfert6div').show();
    }

    $('#FicheTransferthorsuesuffisant').click (function ()
    {
        if($(this).is(':checked')){
            $('#transfertdiv').show();
            $('#FicheTransferthorsuenon').prop('checked',false);
        }
        else if($(this).not(':checked')){
            if($('#FicheTransferthorsueinsuffisant').not(':checked')) {
                $('#transfertdiv').hide();
            }
            else{
                $('#FicheTransferthorsuenon').prop('checked',false);
            }
        }
    });

    $('#FicheTransferthorsueinsuffisant').click (function ()
    {
        if($(this).is(':checked')){
            $('#transfertdiv').show();
            $('#transfert6div').show();
            $('#fichetransferthorsuenon').prop('checked',false);
        }
        else if($(this).not(':checked')){
            if($('#FicheTransferthorsuesuffisant').is(':checked')) {
                $('#transfert6div').hide();
                $('#FicheTransferthorsuenon').prop('checked',false);
            }
            else{
                $('#transfert6div').hide();
                $('#transfertdiv').hide();
            }
        }
    });

    $('#FicheTransferthorsuenon').click (function () {
        $('#transfertdiv').hide();
        $('#transfert6div').hide();
        $('#FicheTransferthorsueinsuffisant').prop('checked',false);
        $('#FicheTransferthorsuesuffisant').prop('checked',false);
    });


/**
*affichage ou non du champ detail pour les categories concernées par le transfert
*/

    if($('#FicheTransfertconcerneautres').is(':checked')){
        $('#transfertconcerneautresdiv').show();
    }
    $('#FicheTransfertconcerneautres').click (function ()
    {
        if($(this).is(':checked')){
            $('#transfertconcerneautresdiv').show();
        }
        else if($(this).not(':checked')){
            $('#transfertconcerneautresdiv').hide();
        }
    });

/**
*affichage ou non du champ detail pour les categories concernées par le transfert
*/

    if($('#FicheAccesinformationautres').is(':checked')){
        $('#accesinformationautresdiv').show();
    }
    $('#FicheAccesinformationautres').click (function ()
    {
        if($(this).is(':checked')){
            $('#accesinformationautresdiv').show();
        }
        else if($(this).not(':checked')){
            $('#accesinformationautresdiv').hide();
        }
    });

/**
*affichage ou non du champ detail pour les categories concernées par le transfert
*/

    if($('#FicheAccesreponseexterne').is(':checked')){
        $('#coordoneesreponse').show();
    }
    $('#FicheAccesreponseexterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#coordoneesreponse').show();
            $('#FicheAccesreponseinterne').prop('checked', false);
        }
        else if($(this).not(':checked')){
            $('#coordoneesreponse').hide();
        }
    });

    $('#FicheAccesreponseinterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#coordoneesreponse').hide();
            $('#FicheAccesreponseexterne').prop('checked', false);
        }
    });

     $('.itemfiles').hover(function(){
        var num = $(this).attr('data');
         if($('.checkFile' + num).is(':checked')){
        
    }
    else{
        $('.btn' + num).fadeIn(1);
    }
    },
    function(){
    var num = $(this).attr('data');
        $('.btn' + num).fadeOut(1);

    });

     $('.boutondelfile').click(function(){
         var numero = $(this).attr('data');
         $('.checkFile' + numero).attr('checked', 'checked');
         $('.btn' + numero).hide();
         $('.btn' + numero).hide();
         $('.boutonannuler' + numero).show();
     });

     $('.boutonannuler').click(function(){
        var numero = $(this).attr('data');
         $('.checkFile' + numero).prop('checked', false);
         $('.boutonannuler' + numero).hide();
     });
});