$(document).ready(function () {
    $('#modalNotif').modal();

    $(".usersDeroulant").chosen({no_results_text: "Aucun résultat trouvé pour", width: '100%'});
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
        var tmpName = $(this).val();
        if (tmpName !== '' /*&& $('#newProjet').val() == ''*/) {

            //suppression du fakepath ajouter par webkit
            tmpName = tmpName.replace("C:\\fakepath\\", "");

            //verification de l'extension odt
            var ctrlName = tmpName.split('.');
            if (ctrlName[ctrlName.length - 1] !== 'odt' && ctrlName[ctrlName.length - 1] !== 'pdf') {
                $(this).val("");
                $('#errorExtentionAnnexe').modal('show');
            } 
//            else {
//                alert(tmpName);
//                
//                tmpName = tmpName.replace(/ /g, '_');
//                
//                alert(tmpName);
//                
//                $(this).val(tmpName);
//                
//            }
//                } else {
//                    var newName = "";
//                    for (var i = 0, ln = ctrlName.length - 1; i < ln; i++) {
//                        newName += (i === 0 ? '' : ' ') + ctrlName[i];
//                    }
//                    var newName = newName.replace(/_/g, ' ');
//                    $('#newProjet').val((newName[0].toUpperCase() + newName.substring(1)).trim());
//                }
        }
    });
}