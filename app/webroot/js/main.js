$(document).ready(function () {
    $('#modalNotif').modal();

    $(".usersDeroulant").chosen({
        no_results_text: "Aucun résultat trouvé pour", 
        width: '100%'
    });
    
    $('.my-tooltip').tooltip({
        delay: {"show": 800, "hide": 0},
        container: "body"
    }
    );

    $('[id^="collapse"]').on('shown.bs.collapse', function () {
        var coucou = $(this).attr('id');
        $('html, body').animate({
            scrollTop: $('#' + coucou).offset().top - 100
        }, 'fast');
        return false;
    });

    $('.btn-del-file').click(function () {
        var value = $(this).attr('data');
        $('.tr-file-' + value).css('color', '#d3d3d3');
        $('.boutonsFile' + value).hide();
        $('.boutonsFileHide' + value).show();
        var new_element = jQuery('<input type="hidden" class="hiddenFile' + value + '" name="delfiles[]" value="' + value + '"/>');
        $('.hiddenfields').append(new_element);
    });
    
    $('.btn-cancel-file').click(function () {
        var value = $(this).attr('data');
        $('.tr-file-' + value).css('color', '#000000');
        $('.boutonsFileHide' + value).hide();
        $('.boutonsFile' + value).show();
        $('.hiddenFile' + value).remove();

    });
    
    $('.btn-edit-registre').click(function () {
        var id = $(this).attr('data');
        $('#idEditRegistre').val(id);
    })
    
    $('.btn-insert-registre').click(function () {
        var id = $(this).attr('data-id');
        $('#idFiche').val(id);
        
        if ($(this).attr('data-type') === 'false'){
            $('#typedeclaration').parent().parent().show();
        } else{
            $('#typedeclaration').parent().parent().hide();
        }
        
    })
    
    $('.btn-upload-modele').click(function () {
        var id = $(this).attr('data');
        $('#idUploadModele').val(id);
    })
});

function openTarget(idFicheNotification) {
    var scrollToTarget = function (idFicheNotification) {
        $("html, body").animate({scrollTop: $("#" + idFicheNotification).offset().top - 90}, 1000);
        if (idFicheNotification !== 0) {
            $("#" + idFicheNotification).click();
        }
    };

    if (idFicheNotification) {
        scrollToTarget(idFicheNotification);
    }
}

function verificationExtension() {
    $('#fileAnnexe').change(function () {
    var annexes = this.files;

    for (var i = 0; i< annexes.length; i++ ) {
        var tmpName = annexes[i]['name'];

        if (tmpName !== '') {
            //suppression du fakepath ajouter par webkit
            tmpName = tmpName.replace("C:\\fakepath\\", "");

            //verification de l'extension odt OU pdf
            var ctrlName = tmpName.split('.');

            if (ctrlName[ctrlName.length - 1] !== 'odt' && ctrlName[ctrlName.length - 1] !== 'pdf') {
                $(this).val("");
                $('#errorExtentionAnnexe').modal('show');
            } 
        }
    }
});
}