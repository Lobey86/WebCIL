$(document).ready(function(){
    $('#EtapeCptRetard').change(changeCptRetard);
    $('#EtapeCptRetard').keyup(changeCptRetard);
});

function changeCptRetard(){
    var cpt_current = parseInt($('#EtapeCptRetard').val());
    var cpt_max = parseInt($('#EtapeCptRetard').prop('max'));
    if (cpt_max && cpt_current > cpt_max){
        $('#EtapeCptRetard').val(cpt_max);
        $.jGrowl('<p>Nombre maximum : '+cpt_max+' jours.</p><p>Cette valeur ne peut pas dépasser celle de l\'étape précédente</p>');
    }
}