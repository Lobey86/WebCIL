$(document).ready(function(){

    if($('#FichePublicautres').is(':checked')){
        $('#publicAutresPrec').show();
    }
    if($('#FicheInformerautres').is(':checked')){
        $('#informerAutresPrec').show();
    }
    if($('#FicheDroitssurplace').is(':checked')){
        $('#droitsQui').show();
    }
    if($('#FicheDroitsemail').is(':checked')){
        $('#droitsAdresseMail').show();
    }
    if($('#FicheExternradioO').is(':checked')){
        $('#donneesExterne').show();
    }
    if($('#FicheArchivageO').is(':checked')){
        $('#archivagePrec').show();
    }
    if($('#FicheInterconnexionouiexterne').is(':checked')){
        $('#interconnexionPrec').show();
    }
    if($('#FicheInterconnexionouiinterne').is(':checked')){
        $('#interconnexionPrec').show();
    }


    if($('#checkA').is(':checked'))
    {
        $('#donneesCatA').show();
    }
    if($('#checkB').is(':checked'))
    {
        $('#donneesCatB').show();
    }
    if($('#checkC').is(':checked'))
    {
        $('#donneesCatC').show();
    }
    if($('#checkE').is(':checked'))
    {
        $('#donneesCatE').show();
    }
    if($('#checkH').is(':checked'))
    {
        $('#donneesCatH').show();
    }
    if($('#checkI').is(':checked'))
    {
        $('#donneesCatI').show();
    }
    if($('#checkJ').is(':checked'))
    {
        $('#donneesCatJ').show();
    }
    if($('#checkK').is(':checked'))
    {
        $('#donneesCatK').show();
    }
    if($('#checkL').is(':checked'))
    {
        $('#donneesCatL').show();
    }
    if($('#checkM').is(':checked'))
    {
        $('#donneesCatM').show();
    }
    if($('#checkP').is(':checked'))
    {
        $('#donneesCatP').show();
    }




    $('#FicheInterconnexionouiinterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#interconnexionPrec').show();
        }
        else if($(this).not(':checked')){
            if($('#FicheInterconnexionouiexterne').is(':checked')){
                $('#interconnexionPrec').show();
            }
            else{
                $('#interconnexionPrec').hide();
            }
        }
    });

    $('#FicheInterconnexionouiexterne').click (function ()
    {
        if($(this).is(':checked')){
            $('#interconnexionPrec').show();
        }
        else if($(this).not(':checked')){
            if($('#FicheInterconnexionouiinterne').is(':checked')){
                $('#interconnexionPrec').show();
            }
            else{
                $('#interconnexionPrec').hide();
            }
        }
    });

    $('#FicheInterconnexionnon').click (function ()
    {
        $('#FicheInterconnexionouiexterne').attr('checked', false);
        $('#FicheInterconnexionouiinterne').attr('checked', false);
        $('#interconnexionPrec').hide();
    });
    $('#FicheInterconnexionouiexterne').click (function ()
    {
        $('#FicheInterconnexionnon').attr('checked', false);
    });
    $('#FicheInterconnexionouiinterne').click (function ()
    {
        $('#FicheInterconnexionnon').attr('checked', false);
    });

    $('#FicheArchivageO').click (function ()
    {
        $('#archivagePrec').show();
    });
    $('#FicheArchivageN').click (function ()
    {
        $('#archivagePrec').hide();
    });

    $('#FicheExternradioO').click (function ()
    {
        $('#donneesExterne').show();
    });
    $('#FicheExternradioN').click (function ()
    {
        $('#donneesExterne').hide();
    });

    $('#FicheDroitsemail').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#droitsAdresseMail').show();
        }
        else{
            $('#droitsAdresseMail').hide();
        }
    });


    $('#FicheDroitssurplace').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#droitsQui').show();
        }
        else{
            $('#droitsQui').hide();
        }
    });

    $('#FicheInformerautres').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#informerAutresPrec').show();
        }
        else{
            $('#informerAutresPrec').hide();
        }
    });

    $('#FichePublicautres').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#publicAutresPrec').show();
        }
        else{
            $('#publicAutresPrec').hide();
        }
    });
    $('#checkA').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatA').show();
        }
        else{
            $('#donneesCatA').hide();
        }
    });
    $('#checkB').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatB').show();
        }
        else{
            $('#donneesCatB').hide();
        }
    });
    $('#checkC').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatC').show();
        }
        else{
            $('#donneesCatC').hide();
        }
    });
    $('#checkE').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatE').show();
        }
        else{
            $('#donneesCatE').hide();
        }
    });
    $('#checkH').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatH').show();
        }
        else{
            $('#donneesCatH').hide();
        }
    });
    $('#checkI').click (function ()
    {

        if($(this).is(':checked'))
        {
            $('#donneesCatI').show();
        }
        else{
            $('#donneesCatI').hide();
        }
    });
    $('#checkJ').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatJ').show();
        }
        else{
            $('#donneesCatJ').hide();
        }
    });
    $('#checkK').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatK').show();
        }
        else{
            $('#donneesCatK').hide();
        }
    });
    $('#checkL').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatL').show();
        }
        else{
            $('#donneesCatL').hide();
        }
    });
    $('#checkM').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatM').show();
        }
        else{
            $('#donneesCatM').hide();
        }
    });
    $('#checkP').click (function ()
    {
        if($(this).is(':checked'))
        {
            $('#donneesCatP').show();
        }
        else{
            $('#donneesCatP').hide();
        }
    });



    $('#popovera').popover({
            content: "Identification (nom, prénom, sexe, adresse, photographie, date de naissance…) ou autre code identifiant (numéro CAF..)",
            placement: 'top',
            title: 'Catégorie A',
            trigger: 'hover'
        }
    );
    $('#popoverb').popover({
            content: "NIR (numéro sécurité sociale)",
            placement: 'top',
            title: 'Catégorie B',
            trigger: 'hover'
        }
    );
    $('#popoverc').popover({
            content: "Vie personnelle (situation familiale, habitudes de vie / situation militaire, loisirs…)",
            placement: 'top',
            title: 'Catégorie C',
            trigger: 'hover'
        }
    );
    $('#popovere').popover({
            content: "Vie professionelle (CV, scolarité, formation, distinctions…)",
            placement: 'top',
            title: 'Catégorie E',
            trigger: 'hover'
        }
    );
    $('#popoverh').popover({
            content: "Situation économique et financière (revenus, situation fiscale, situation financière, endettement, charges, retraite, aides diverses, biens immobiliers, références cadastrales, RIB, taxe foncière, taxe d’habitation, revenu du capital, livret…)",
            placement: 'top',
            title: 'Catégorie H',
            trigger: 'hover'
        }
    );
    $('#popoveri').popover({
            content: "Déplacement et localisation des personnes (données GPS, GSM…)",
            placement: 'top',
            title: 'Catégorie I',
            trigger: 'hover'
        }
    );
    $('#popoverj').popover({
            content: "Données de connexions : adresse IP, journaux de connexion, information d’horodatage, code d’accès ou procédés automatiques de collecte (cookies, applet JAVA, active X…). Utilisation des médias et moyens de communications.",
            placement: 'top',
            title: 'Catégorie J',
            trigger: 'hover'
        }
    );
    $('#popoverk').popover({
            content: "Données à caractère personnel faisant apparaître les origines raciales ou ethniques, les opinions politiques, philosophiques, religieuses ou les appartenances syndicales des personnes. La vie sexuelle.",
            placement: 'top',
            title: 'Catégorie K',
            trigger: 'hover'
        }
    );
    $('#popoverl').popover({
            content: "Données biométriques, génétiques.",
            placement: 'top',
            title: 'Catégorie L',
            trigger: 'hover'
        }
    );
    $('#popoverm').popover({
            content: "Santé (antécédents familiaux, pathologie, soins, situation ou comportement à risque…) et/ou appréciations sur les difficultés sociales de la personne (évaluation sociale, fragilité, dépendance…)",
            placement: 'top',
            title: 'Catégorie M',
            trigger: 'hover'
        }
    );
    $('#popoverp').popover({
            content: "Informations en rapport avec la police. Informations relatives aux infractions, condamnations ou mesures de sûreté.",
            placement: 'top',
            title: 'Catégorie P',
            trigger: 'hover'
        }
    );


});