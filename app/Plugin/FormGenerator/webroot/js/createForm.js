$(document).ready(function () {
    var incrementation_id = 0;
    refresh();
    $("#form-container").resizable({
        handles: "s"
    });


    /**
     * Création des éléments du formulaire lors du clic sur un bouton.
     * Pour rajouter des boutons il faut les rajouter sur l'élément HTML et rajouter un case dans le switch
     */
    $(".btn-input").click(function () {
        var id = $(this).attr('id');
        $('.ui-selected').removeClass('ui-selected');
        switch (id) {
            case 'btn-small-text':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 small-text ui-selected">\n\
                        <div class="col-md-4">\n\
                            <label>\n\
                                <span class="labeler">Petit champ texte</span>\n\
                            </label>\n\
                        </div>\n\
                        <div class="col-md-8">\n\
                            <input type="text" class="form-control" placeholder="Aide à la saisie"/>\n\
                        </div>\n\
                    </div>'
                );
                
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
                
            case 'btn-long-text':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 long-text ui-selected">\n\
                        <div class="col-md-4">\n\
                            <label>\n\
                                <span class="labeler">Grand champ texte</span>\n\
                            </label>\n\
                        </div>\n\
                        <div class="col-md-8">\n\
                            <textarea class="form-control" placeholder="Aide à la saisie"/>\n\
                        </div>\n\
                    </div>'
                );
                
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
                
            case 'btn-date':
                var new_element = jQuery(
                        '<div class="draggable form-group col-md-6 date ui-selected">\n\
                            <div class="col-md-4">\n\
                                <label>\n\
                                    <span class="labeler">Champ date</span>\n\
                                </label>\n\
                            </div>\n\
                            <div class="container">\n\
                                <div class="row">\n\
                                    <div class="col-sm-2">\n\
                                        <input type="text" class="form-control" placeholder="jj/mm/aaaa" id="datetimepicker'+incrementation_id+'"/>\n\
                                    <\div>\n\
                                <\div>\n\
                            </div>\n\
                        </div>'
                );
        
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                incrementation_id ++;
                break;
                
            case 'btn-title':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 title ui-selected text-center">\n\
                        <h1>Titre de catégorie</h1>\n\
                    </div>'
                );
                
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
                
            case 'btn-help':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 help text-center ui-selected">\n\
                        <div class="col-md-12 alert alert-info">\n\
                            <div class="col-md-12">\n\
                                <i class="fa fa-fw fa-info-circle fa-2x"></i>\n\
                            </div>\n\
                            <div class="col-md-12 messager">Champ d\'information</div>\n\
                        </div>\n\
                    </div>'
                );
               
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
                
            case 'btn-checkbox':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 checkboxes ui-selected">\n\
                        <div class="col-md-4">\n\
                            <label>\n\
                                <span class="labeler">Cases à cocher</span>\n\
                            </label>\n\
                        </div>\n\
                        <div class="col-md-8 contentCheckbox"> Aucune option sélectionnée</div>\n\
                    </div>');
                
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
                
            case 'btn-radio':
                var new_element = jQuery(
                    '<div class="draggable form-group col-md-6 radios ui-selected">\n\
                        <div class="col-md-4">\n\
                            <label>\n\
                                <span class="labeler">Choix unique </span>\n\
                            </label>\n\
                        </div>\n\
                        <div class="col-md-8 contentRadio"> Aucune option sélectionnée</div>\n\
                    </div>'
                );
                
                $('#form-container').append(new_element);
                hideDetails();
                displayDetails($('.ui-selected'));
                refresh();
                break;
        }

        for (var i = 0; i < incrementation_id; i++){
            $('#datetimepicker'+ i).datetimepicker({
                viewMode: 'year',
                startView: "decade",
                format: 'dd/mm/yyyy',
                minView: 2,
                language: 'fr'
            });
        }
    });


    /**
     * Permet de raffraichir les fonctions onClick lors du rajout d'un champ.
     * En son absence, aucun onclick ne sera ajouté
     */
    function refresh() {
        $(".draggable").unbind('click');
        $('#form-container').unbind('click');
        var size = $('#form-container').width();
        size = Math.round(size / 2);
        $(".draggable").draggable({
            containment: "parent",
            opacity: 0.70,
            grid: [size, 35]
        });
        
        $(".draggable").click(function () {
            if ($(this).hasClass("ui-selected")) {
                $(this).removeClass("ui-selected");
                hideDetails();
            }
            else {
                $(".ui-selected").removeClass("ui-selected");
                hideDetails();
                $(this).addClass("ui-selected");
                displayDetails($(this));
            }
        });
        
        $(".draggable").on('dragstop', function () {
            var position = $(this).position();
            if (position.left < size / 2) {
                $(this).css('left', 0);
            }
            else {
                $(this).css('left', size + 1);
            }

            $(this).css('top', Math.ceil(position.top));
        });
    }

    /**
     * Affiche le formulaire avec les détails à remplir sur chaque champ ajouté lors de sa séléction
     * @param object
     */
    function displayDetails(object) {
        if ($('.ui-selected').attr('data') == 'checked') {
            var check = '<div class="checkbox"><input type="checkbox" name="name" id="oblig-small-text" class="obligForm" checked="true"> Champ obligatoire</div>';
        }
        else {
            var check = '<div class="checkbox"><input type="checkbox" name="name" id="oblig-small-text" class="obligForm"> Champ obligatoire</div>';
        }
        
        if (object.hasClass('small-text') || object.hasClass('date')) {
            var options = jQuery('' +
                    '<div class="col-md-12">' +
                    '<div class="form-group"><label>Nom de variable <span class="obligatoire">*</span></label><input type="text" class="form-control nameForm" name="name" id="name-small-text" placeholder="Nom du champ" value="' + $('.ui-selected').find('input').attr('name') + '"></div>' +
                    '<div class="form-group"><label>Nom du champ</label><input type="text" class="form-control labelForm" name="name" id="label-small-text" placeholder="Label du champ" value="' + $('.ui-selected').find('.labeler').html() + '"></div>' +
                    '<div class="form-group"><label>Aide à la saisie</label><input type="text" class="form-control placeholderForm" name="name" id="placeholder-small-text" placeholder="' + $('.ui-selected').find('input').attr('placeholder') + '"></div>' +
                    check +
                    '<div class=" btn-group text-center"><button type="button" class="btn btn-default-danger btn-sm" id="closer"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-default-success btn-sm" id="applicable"><i class="fa fa-check"></i> Appliquer</button> </div>' +
                    '</div>'
                )
                ;
        }
        else if (object.hasClass('long-text')) {
            var options = jQuery('' +
                    '<div class="col-md-12">' +
                    '<div class="form-group"><label>Nom de variable <span class="obligatoire">*</span></label><input type="text" class="form-control nameForm" name="name" id="name-long-text" placeholder="Nom du champ" value="' + $('.ui-selected').find('textarea').attr('name') + '"></div>' +
                    '<div class="form-group"><label>Nom du champ</label><input type="text" class="form-control labelForm" name="name" id="label-long-text" placeholder="Label du champ" value="' + $('.ui-selected').find('.labeler').html() + '"></div>' +
                    '<div class="form-group"><label>Aide à la saisie</label><input type="text" class="form-control placeholderForm" name="name" id="placeholder-long-text" placeholder="' + $('.ui-selected').find('textarea').attr('placeholder') + '"></div>' +
                    check +
                    '<div class=" btn-group text-center"><button type="button" class="btn btn-default-danger btn-sm" id="closer"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-default-success btn-sm" id="applicable"><i class="fa fa-check"></i> Appliquer</button> </div>' +
                    '</div>'
                )
                ;
        }
        else if (object.hasClass('title')) {
            var options = jQuery('' +
                    '<div class="col-md-12">' +
                    '<div class="form-group">Contenu<input type="text" class="form-control titleForm" name="content-title" id="content-title" value="' + $('.ui-selected').find('h1').html() + '"></div>' +
                    '<div class=" btn-group text-center"><button type="button" class="btn btn-default-danger btn-sm" id="closer"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-default-success btn-sm" id="applicable"><i class="fa fa-check"></i> Appliquer</button> </div>' +
                    '</div>'
                );
        
        } else if (object.hasClass('help')) {
            var options = jQuery('' +
                    '<div class="col-md-12">' +
                    '<div class="form-group">Contenu<input type="text" class="form-control helpForm" name="content-help" id="content-help" value="' + $('.ui-selected').find('.messager').html() + '"></div>' +
                    '<div class=" btn-group text-center"><button type="button" class="btn btn-default-danger btn-sm" id="closer"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-default-success btn-sm" id="applicable"><i class="fa fa-check"></i> Appliquer</button> </div>' +
                    '</div>'
                );
        
        } else if (object.hasClass('checkboxes') || object.hasClass('radios')) {
            var list = '';
            $('.ui-selected').find('input').each(function () {
                if (list == '') {
                    list = list + $(this).val();
                }
                else {
                    list = list + '\n' + $(this).val();
                }
            });

            if (!$('.ui-selected').find('input').attr('name')) {
                var nom = 'undefined';
            }
            else {
                var nom = $('.ui-selected').find('input').attr('name').replace('[]', '');
            }

            var options = jQuery('' +
                '<div class="col-md-12">' +
                '<div class="form-group"><label>Nom de variable <span class="obligatoire">*</span></label><input type="text" class="form-control nameForm" name="name" id="name-date" placeholder="Nom du champ" value="' + nom + '"></div>' +
                '<div class="form-group"><label>Nom du champ</label><input type="text" class="form-control labelForm" name="name" id="label-checkbox" placeholder="Label du champ" value="' + $('.ui-selected').find('.labeler').html() + '"></div>' +
                '<div class="form-group"><label>Options (1 par ligne)</label><textarea class="form-control checkboxForm">' + list + '</textarea></div>' +
                '<div class=" btn-group text-center"><button type="button" class="btn btn-default-danger btn-sm" id="closer"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-default-success btn-sm" id="applicable"><i class="fa fa-check"></i> Appliquer</button> </div>' +
                '</div>'
            );
        }

        $('#field-options').append(options);
        
        $('#applicable').click(function () {
            $('#applicable').parent().parent().find('input').each(function () {
                if ($(this).hasClass('labelForm')) {
                    $('.ui-selected').find('label').html('<span class="labeler">' + $(this).val() + '</span>');
                }
                
                if ($(this).hasClass('nameForm')) {
                    checkName($(this).val());
                    $('.ui-selected').find('input').attr('name', $(this).val());
                    $('.ui-selected').find('textarea').attr('name', $(this).val());
                }
                
                if ($(this).hasClass('placeholderForm')) {
                    $('.ui-selected').find('input').attr('placeholder', $(this).val());
                }
                
                if ($(this).hasClass('placeholderForm')) {
                    $('.ui-selected').find('textarea').attr('placeholder', $(this).val());
                }
                
                if ($(this).hasClass('obligForm')) {
                    if ($(this).prop('checked')) {
                        $('.ui-selected').find('label').html('<span class="labeler">' +
                            $('.labelForm').val() + '</span><span class="obligatoire"> *</span>'
                        );
                        $('.ui-selected').attr('data', 'checked');
                    }
                    else {
                        $('.ui-selected').find('label').html('<span class="labeler">' + $('.labelForm').val() + '</span>');
                        $('.ui-selected').attr('data', 'unchecked');
                    }
                }
                
                if ($(this).hasClass('titleForm')) {
                    $('.ui-selected').find('h1').html($(this).val());
                }
                
                if ($(this).hasClass('helpForm')) {
                    $('.ui-selected').find('.messager').html($(this).val());
                }
            });

            $('#applicable').parent().parent().find('textarea').each(function () {
                if ($(this).hasClass('checkboxForm')) {
                    var options = $(this).val().split('\n');
                    var objet = '';
                    var nom = $(this).parent().parent().find('.nameForm').val();
                    $.each(options, function (index, value) {
                        objet = objet + '<div class="checkbox"><input type="checkbox" name="' + nom + '" value="' + value + '">' + value + '</div>';
                    });
                    $('.ui-selected').find('.contentCheckbox').html(jQuery(objet));
                    $('.ui-selected').attr('style', function (i, style) {
                        return style.replace(/height[^;]+;?/g, '');
                    });
                    $('.ui-selected').attr('style', function (i, style) {
                        return style.replace(/width[^;]+;?/g, '');
                    });
                }
                else if ($(this).hasClass('radioForm')) {
                    var options = $(this).val().split('\n');
                    var objet = '';
                    var nom = $(this).parent().parent().find('.nameForm').val();
                    $.each(options, function (index, value) {
                        objet = objet + '<div class="radio"><input type="radio" name="' + nom + '" value="' + value + '">' + value + '</div>';
                    });
                    $('.ui-selected').find('.contentRadio').html(jQuery(objet));
                    $('.ui-selected').attr('style', function (i, style) {
                        return style.replace(/height[^;]+;?/g, '');
                    });
                    $('.ui-selected').attr('style', function (i, style) {
                        return style.replace(/width[^;]+;?/g, '');
                    });
                }
            });
        });

        $("#closer").click(function () {
            $('.ui-selected').remove();
            hideDetails();
        });
    }

    /**
     * Cache les détails du champ lors de sa déselection
     */
    function hideDetails() {
        $("#field-options>div").remove();
    }

    function checkName(nom) {
        $('#form-container').find('input').each(function () {
            if ($(this).attr('name') == nom && !$(this).parent().parent().hasClass('ui-selected')) {
                alert('Un champ possède déjà ce nom');
                return false;
            }
            if (nom == 'undefined') {
                alert('Vous devez spécifier un nom pour ce champ');
                return false;
            }
        });
        $('#form-container').find('textarea').each(function () {
            if ($(this).attr('name') == nom && !$(this).parent().parent().hasClass('ui-selected')) {
                alert('Un champ possède déjà ce nom');
                return false;
            }
            if (nom == 'undefined') {
                alert('Vous devez spécifier un nom pour ce champ');
                return false;
            }
        });
    }

    $('#successForm').click(function () {
        var success = true;
        var retour = [];
        $('#form-container').find('.draggable').each(function () {
            var contenu = {};
            contenu['ligne'] = $(this).position().top / 35 + 1;
            if ($(this).position().left < 10) {
                contenu['colonne'] = 1;
            }
            else {
                contenu['colonne'] = 2;
            }
            if ($(this).hasClass('small-text')) {
                if (typeof $(this).find('input').attr('name') === 'undefined') {
                    alert('Un champ n\'a pas de nom');
                    success = false;
                    return;
                }
                contenu['type'] = 'input';
                contenu['name'] = $(this).find('input').attr('name');
                contenu['placeholder'] = $(this).find('input').attr('placeholder');
                contenu['label'] = $(this).find('.labeler').html();

            }
            else if ($(this).hasClass('long-text')) {
                if (typeof $(this).find('textarea').attr('name') === 'undefined') {
                    alert('Un champ n\'a pas de nom');
                    success = false;
                    return;
                }
                contenu['type'] = 'textarea';
                contenu['name'] = $(this).find('textarea').attr('name');
                contenu['placeholder'] = $(this).find('textarea').attr('placeholder');
                contenu['label'] = $(this).find('.labeler').html();
            }
            else if ($(this).hasClass('date')) {
                if (typeof $(this).find('input').attr('name') === 'undefined') {
                    alert('Un champ n\'a pas de nom');
                    success = false;
                    return;
                }
                contenu['type'] = 'date';
                contenu['name'] = $(this).find('input').attr('name');
                contenu['placeholder'] = $(this).find('input').attr('placeholder');
                contenu['label'] = $(this).find('.labeler').html();
            }

            else if ($(this).hasClass('title')) {
                contenu['type'] = 'title';
                contenu['content'] = $(this).find('h1').html();
            }
            else if ($(this).hasClass('help')) {
                contenu['type'] = 'help';
                contenu['content'] = $(this).find('.messager').html();
            }
            else if ($(this).hasClass('checkboxes')) {
                if (typeof $(this).find('input').attr('name') === 'undefined') {
                    alert('Un champ n\'a pas de nom');
                    success = false;
                    return;
                }
                contenu['type'] = 'checkboxes';
                contenu['name'] = $(this).find('input').attr('name');
                contenu['label'] = $(this).find('.labeler').html();
                var option = [];
                $(this).find('input').each(function () {
                    option.push($(this).attr('value'));
                });
                contenu['options'] = option;
            }
            else if ($(this).hasClass('radios')) {
                if (typeof $(this).find('input').attr('name') === 'undefined') {
                    alert('Un champ n\'a pas de nom');
                    success = false;
                    return;
                }
                contenu['type'] = 'radios';
                contenu['name'] = $(this).find('input').attr('name');
                contenu['label'] = $(this).find('.labeler').html();
                var option = [];
                $(this).find('input').each(function () {
                    option.push($(this).attr('value'));
                });
                contenu['options'] = option;
            }
            else if ($(this).hasClass('fichiers')) {
                contenu['type'] = 'file';
                contenu['name'] = $(this).find('input').attr('name');
                contenu['label'] = 'Fichiers';

            }

            if ($(this).attr('data') == 'checked') {
                contenu['obligatoire'] = true;
            }
            else {
                contenu['obligatoire'] = false;
            }
            retour.push(contenu);
        });
        console.log(retour);
        var ret = JSON.stringify(retour, null, '\t');
        console.log(ret);
        if (success) {
            $("#hiddenForm").attr('value', ret);
            document.forms["addForm"].submit();
        }
    });
});
