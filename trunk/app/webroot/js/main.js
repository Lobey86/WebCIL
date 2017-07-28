$(document).ready(function () {
	try {
		$('#modalNotif').modal();
	} catch(exception) {
		console.log(exception);
	}

	try {
		$(".chosen-select, .usersDeroulant").chosen({
			no_results_text: "Aucun résultat trouvé pour",
			allow_single_deselect: true,
			width: '100%'
		});
	} catch(exception) {
		console.log(exception);
	}

	try {
		$('.my-tooltip').tooltip({
			delay: {"show": 800, "hide": 0},
			container: "body"
		}
		);
	} catch(exception) {
		console.log(exception);
	}

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

    $('.btn_envoyerValideur').click(function () {
        var id = $(this).attr('data-id');
        $('#ficheNumVal').val(id);

        var fiche = $(this).attr('data-fiche');
        $('#etatFicheVal').val(fiche);
    })

    $('.btn_envoyerConsultation').click(function () {
        var id = $(this).attr('data-id');
        $('#ficheNumCons').val(id);

        var fiche = $(this).attr('data-fiche');
        $('#etatFicheCons').val(fiche);
    })

    $('.btn_ReorienterTraitement').click(function () {
        var id = $(this).attr('data-id');
        $('#ficheNumReo').val(id);

        var fiche = $(this).attr('data-fiche');
        $('#etatFicheReo').val(fiche);
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

var WebcilForm = {
	chosenFields: function(form) {
		var selects;
		if (true === $.isFunction( $.fn.chosen )) {
			try {
				selects = $(form).find('select').chosen();
			} catch(exception) {
				selects = [];
			}
		} else {
			selects = [];
		}

		return $(selects);
	},
	reset: function(event) {
		var form = $(event.target).closest('form'),
			typesWithValue = ['color', 'date', 'datetime', 'email', 'month', 'number', 'password', 'range', 'research', 'search', 'tel', 'text', 'url', 'week'],
			typesWithChecked = ['checkbox', 'radio'],
			fieldsWithValue = $(form).find('input[type="'+typesWithValue.join('"], input[type="')+'"]'+', select, textarea'),
			fieldsWithChecked = $(form).find('input[type="'+typesWithChecked.join('"], input[type="')+'"]');

		$(fieldsWithValue).val('');
		$(fieldsWithValue).trigger('change');

		$(fieldsWithChecked).prop('checked', false);
		$(fieldsWithValue).trigger('click');

		// On indique au plugin chosen qu'il doit mettre à jour les champs
		WebcilForm.chosenFields(form).trigger('chosen:updated');

		event.preventDefault();
		event.stopPropagation();
	},
	init: function(form) {
		$(form).find('.search-reset').bind('click', WebcilForm.reset);

		WebcilForm.chosenFields(form).each(function(idx, select){
			var chosen = $('#'+$(select).attr('id')+'_chosen'),
				span = $(chosen).find('a.chosen-single.chosen-default span');

			$(select).on('change', function(event, params) {
				if('undefined' === typeof params) {
					$(span).addClass('text-muted');
				} else {
					$(span).removeClass('text-muted');
				}
			});

			$(span).addClass('text-muted');
		});
	}
};

$(function(){
	$('form.search-form').each(function(idx, form){WebcilForm.init(form)});
});